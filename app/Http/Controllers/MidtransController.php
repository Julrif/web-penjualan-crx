<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function checkout(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
            }

            $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('user.keranjang.index')
                    ->with('error', 'Keranjang kosong!');
            }

            $request->validate([
                'address' => 'required|string|min:5',
                'city' => 'nullable|string|max:100',
                'province' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:10',
                'phone' => 'required|string|min:10',
                'shipping_method' => 'required|string|in:JNE,JNT',
            ]);

            $subtotal = $cartItems->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

            $shippingCost = $request->shipping_method == 'JNE' ? 20000 : 15000;
            $operationalCost = 6000;
            $total = $subtotal + $shippingCost + $operationalCost;

            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'INV-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'total' => $total,
                'status' => 'pending',
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $shippingCost,
                'operational_cost' => $operationalCost,
                'courier' => $request->shipping_method,
                'payment_method' => 'Midtrans',
                'payment_status' => 'pending',
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'notes' => $request->notes,
                'expired_at' => now()->addHours(1)
            ]);

            foreach ($cartItems as $cartItem) {
                $variant = ProductVariant::where('product_id', $cartItem->product_id)
                    ->where('size', $cartItem->size)
                    ->first();

                if ($variant) {
                    $variant->decrement('stock', $cartItem->quantity);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $variant->id ?? null,
                    'product_name' => $cartItem->product->name,
                    'size' => $cartItem->size,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'subtotal' => $cartItem->product->price * $cartItem->quantity
                ]);
            }

            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // Proses Midtrans
            try {
                $midtrans = new MidtransService();
                $transaction = $midtrans->createTransaction($order, $user);
                
                return redirect($transaction->redirect_url);
                
            } catch (\Exception $e) {
                Log::error('Midtrans Error: ' . $e->getMessage());
                return redirect()->route('order.detail', $order->id)
                    ->with('warning', 'Pesanan berhasil dibuat, tapi pembayaran gagal diproses.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        try {
            $orderId = $request->order_id;
            $order = Order::where('order_number', $orderId)->firstOrFail();
            
            $order->update([
                'payment_status' => 'verified',
                'verified_at' => now()
            ]);
            
            return redirect()->route('order.detail', $order->id)
                ->with('success', '✅ Pembayaran berhasil! Pesanan Anda sedang diproses.');
            
        } catch (\Exception $e) {
            return redirect()->route('user.profile')
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function failed(Request $request)
    {
        try {
            $orderId = $request->order_id;
            $order = Order::where('order_number', $orderId)->firstOrFail();
            
            return view('pages.midtrans.failed', compact('order'));
            
        } catch (\Exception $e) {
            return redirect()->route('user.profile')
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function notification(Request $request)
    {
        $payload = $request->all();
        
        try {
            $order = Order::where('order_number', $payload['order_id'])->first();
            
            if ($order) {
                $status = $payload['transaction_status'] ?? 'pending';
                
                switch ($status) {
                    case 'capture':
                    case 'settlement':
                        $order->update([
                            'payment_status' => 'verified',
                            'verified_at' => now()
                        ]);
                        break;
                        
                    case 'expire':
                    case 'cancel':
                        $order->update([
                            'status' => 'cancelled',
                            'payment_status' => 'expired'
                        ]);
                        break;
                }
                
                Log::info('Midtrans Webhook: Order ' . $order->order_number . ' status = ' . $status);
            }
            
            return response()->json(['status' => 'ok']);
            
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function pay($orderId)
    {
        try {
            $order = Order::with('items')->findOrFail($orderId);
            $user = Auth::user();
            
            if ($order->user_id != $user->id) {
                abort(403, 'Anda tidak memiliki akses ke order ini!');
            }
            
            if ($order->payment_status != 'pending') {
                return redirect()->route('order.detail', $order->id)
                    ->with('error', 'Pesanan sudah dibayar!');
            }
            
            // Reset snap_token biar bikin transaksi baru
            $order->update(['snap_token' => null]);
            
            $midtrans = new MidtransService();
            $transaction = $midtrans->createTransaction($order, $user);
            
            // Cek apakah transaction berhasil
            if (!$transaction || !isset($transaction->redirect_url)) {
                throw new \Exception('Gagal membuat transaksi Midtrans!');
            }
            
            return redirect($transaction->redirect_url);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans Pay Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}