<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class XenditController extends Controller
{
    protected $xendit;

    public function __construct(XenditService $xendit)
    {
        $this->xendit = $xendit;
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
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
        $total = $subtotal + $shippingCost;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'INV-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'total' => $total,
                'status' => 'pending',
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $shippingCost,
                'courier' => $request->shipping_method,
                'payment_method' => 'Xendit',
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

            $invoice = $this->xendit->createInvoice($order, $user);

            DB::commit();

            return redirect($invoice->getInvoiceUrl());

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        return view('pages.xendit.success', compact('order'));
    }

    public function failed($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        return view('pages.xendit.failed', compact('order'));
    }
}