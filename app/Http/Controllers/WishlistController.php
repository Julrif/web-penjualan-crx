<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // ============================================
    // HALAMAN MY WISHLIST (User)
    // ============================================
    public function index()
    {
        $wishlists = Wishlist::with(['product.primaryImage', 'product.images', 'product.variants'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.wishlist.index', compact('wishlists'));
    }

    // ============================================
    // TOGGLE WISHLIST (Tambah/Hapus)
    // ============================================
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Cek apakah sudah ada di wishlist
        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            // Hapus dari wishlist
            $existing->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Produk dihapus dari wishlist'
            ]);
        } else {
            // Tambah ke wishlist
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Produk ditambahkan ke wishlist'
            ]);
        }
    }

    // ============================================
    // HAPUS DARI WISHLIST
    // ============================================
    public function delete($id)
    {
        $wishlist = Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $wishlist->delete();

        return back()->with('success', 'Produk dihapus dari wishlist!');
    }
}