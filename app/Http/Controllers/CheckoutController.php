<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    function index() {
        $user = Auth::user();
        
        // Ambil alamat default user
        $defaultAddress = Address::where('user_id', $user->id)
            ->where('is_default', true)
            ->first();
        
        $cartItems = Cart::with(['product.primaryImage', 'product.images'])
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.keranjang.index')
                ->with('error', 'Keranjang kosong!');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        $shippingCost = 20000;
        $operationalCost = 6000;
        $total = $subtotal + $shippingCost + $operationalCost;

        $shippingMethods = ['JNE', 'JNT'];
        $paymentMethods = ['Bank BCA', 'QRIS'];

        return view('pages.checkout.index', compact(
            'user', 
            'cartItems', 
            'subtotal', 
            'shippingCost', 
            'operationalCost',
            'total',
            'shippingMethods',
            'paymentMethods',
            'defaultAddress'
        ));
    }

    public function process(Request $request)
    {
        $request->validate([
            'address' => 'required|string|min:5',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'required|string|min:10',
            'shipping_method' => 'required|string|in:JNE,JNT',
            'payment_method' => 'required|string|in:Bank BCA,QRIS',
            'notes' => 'nullable|string'
        ]);

        $user = Auth::user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.keranjang.index')
                ->with('error', 'Keranjang kosong!');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Biaya pengiriman
        $shippingCost = $request->shipping_method == 'JNE' ? 20000 : 20000;
        $operationalCost = 6000; // <-- TAMBAHKAN
        $total = $subtotal + $shippingCost + $operationalCost;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'INV-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'total' => $total,
                'status' => 'pending',
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $shippingCost,
                'operational_cost' => $operationalCost, // <-- TAMBAHKAN
                'courier' => $request->shipping_method,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'address' => $request->address,
                'city' => $request->city,              // TAMBAHKAN
                'province' => $request->province,      // TAMBAHKAN
                'postal_code' => $request->postal_code, // TAMBAHKAN
                'phone' => $request->phone,
                'notes' => $request->notes,
                'expired_at' => now()->addHours(1)
            ]);

            foreach ($cartItems as $cartItem) {
                $variant = null;
                if ($cartItem->size) {
                    $variant = ProductVariant::where('product_id', $cartItem->product_id)
                        ->where('size', $cartItem->size)
                        ->first();
                    
                    if ($variant) {
                        $variant->decrement('stock', $cartItem->quantity);
                    }
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

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        
        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        return view('pages.checkout.success', compact('order'));
    }
}