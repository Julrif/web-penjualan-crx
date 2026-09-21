<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // View - HANYA menampilkan cart milik user yang login
    function index() {
    $cartItems = Cart::with(['product.primaryImage', 'product.images'])->get();
    
    $subtotal = $cartItems->sum(function($item) {
        if ($item->product) {
            return $item->product->price * $item->quantity;
        }
        return 0;
    });

    // Biaya Operasional dan Ongkir
    $operationalCost = 6000;
    $shippingCost = 20000;
    $total = $subtotal + $shippingCost + $operationalCost;
    
    return view("pages.cart.index", [
        "cartItems" => $cartItems,
        'subtotal' => $subtotal,
        'shippingCost' => $shippingCost,
        'operationalCost' => $operationalCost,
        "total" => $total
    ]);
}

    // Services - HANYA untuk user yang login
    // ============================================
    // TAMBAH KE KERANJANG (DENGAN QUANTITY)
    // ============================================
    function create(Request $req)
    {
        // Validasi input
        $req->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $size = strtoupper($req->size);
        $quantity = (int) $req->quantity;

        // Cek apakah size tersedia di variant
        $variant = \App\Models\ProductVariant::where('product_id', $req->product_id)
            ->where('size', $size)
            ->first();

        if (!$variant) {
            return back()->with('error', 'Size ' . $size . ' tidak tersedia!');
        }

        // Cek stok
        if ($variant->stock < 1) {
            return back()->with('error', 'Stok habis!');
        }

        // Cek apakah sudah ada cart dengan product dan size yang sama untuk user ini
        $existingCart = Cart::where('user_id', $userId)
            ->where('product_id', $req->product_id)
            ->where('size', $size)
            ->first();

        if ($existingCart) {
            // Total quantity jika digabung
            $newQuantity = $existingCart->quantity + $quantity;

            // Validasi stok
            if ($newQuantity > $variant->stock) {
                return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $variant->stock . ' pcs (sudah ada ' . $existingCart->quantity . ' di keranjang)');
            }

            // Update quantity
            $existingCart->update([
                'quantity' => $newQuantity
            ]);
        } else {
            // Validasi stok
            if ($quantity > $variant->stock) {
                return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $variant->stock . ' pcs');
            }

            // Create new cart
            Cart::create([
                'user_id' => $userId,
                'product_id' => $req->product_id,
                'size' => $size,
                'quantity' => $quantity
            ]);
        }

        return back()->with('message', 'Item berhasil dimasukan ke keranjang!');
    }

    function update(Request $req, $id)
    {
        // Pastikan cart item milik user yang login
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $req->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->update([
            'quantity' => $req->quantity
        ]);

        return response()->json(['success' => true]);
    }

    function delete($id)
    {
        // Pastikan cart item milik user yang login
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cart->delete();
        return back()->with("message", "Item berhasil dihapus dari keranjang!");
    }
}