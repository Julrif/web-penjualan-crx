<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    // Detail Order
    public function detail($id)
    {
        $order = Order::with(['user', 'items.product', 'items.variant'])
            ->findOrFail($id);

        if (Auth::user()->role_id != 1 && $order->user_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini!');
        }

        $this->checkExpiredOrder($order);

        if (Auth::user()->role_id == 1) {
            return view('pages.dashboard.order.detail', compact('order'));
        }

        return view('pages.order.detail', compact('order'));
    }

    // Cek expired order
    private function checkExpiredOrder($order)
    {
        if ($order->payment_status == 'pending' && $order->expired_at && now()->gt($order->expired_at)) {
            // Kembalikan stok
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->increment('stock', $item->quantity);
                    }
                }
            }
            
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'expired'
            ]);
            session()->flash('error', 'Waktu pembayaran telah habis! Pesanan dibatalkan.');
        }
    }

    // ============================================
    // USER: CANCEL ORDER
    // ============================================
    public function cancel($id)
    {
        $order = Order::with('items')->findOrFail($id);
        
        if ($order->user_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses!');
        }
        
        if ($order->status != 'pending') {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan!');
        }
        
        // Kembalikan stok
        foreach ($order->items as $item) {
            if ($item->product_variant_id) {
                $variant = ProductVariant::find($item->product_variant_id);
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                }
            }
        }
        
        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'cancelled'
        ]);
        
        return back()->with('success', 'Pesanan berhasil dibatalkan! Stok produk dikembalikan.');
    }

    // ============================================
    // USER: KONFIRMASI PEMBAYARAN
    // ============================================
    public function confirmPayment($id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        if ($order->payment_status != 'pending') {
            return back()->with('error', 'Pembayaran sudah diproses!');
        }

        if ($order->expired_at && now()->gt($order->expired_at)) {
            $order->update(['status' => 'cancelled', 'payment_status' => 'expired']);
            return back()->with('error', 'Waktu pembayaran telah habis! Pesanan dibatalkan.');
        }

        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now()
        ]);

        return back()->with('success', 'Terima kasih! Pembayaran Anda sedang kami verifikasi.');
    }

    // ============================================
    // ADMIN: VERIFIKASI PEMBAYARAN
    // ============================================
    public function verifyPayment($id)
    {
        if (Auth::user()->role_id != 1) {
            abort(403, 'Hanya admin yang bisa verifikasi!');
        }

        $order = Order::findOrFail($id);

        if ($order->payment_status != 'paid' && $order->payment_status != 'pending') {
            return back()->with('error', 'Order belum dibayar!');
        }

        $order->update([
            'payment_status' => 'verified',
            'verified_at' => now()
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    // ============================================
    // ADMIN: TOLAK PEMBAYARAN
    // ============================================
    public function rejectPayment($id)
    {
        if (Auth::user()->role_id != 1) {
            abort(403, 'Hanya admin yang bisa menolak!');
        }

        $order = Order::findOrFail($id);

        if ($order->payment_status != 'paid') {
            return back()->with('error', 'Order belum dibayar!');
        }

        $order->update([
            'payment_status' => 'pending',
            'paid_at' => null
        ]);

        return back()->with('success', 'Pembayaran ditolak!');
    }

    // ============================================
    // ADMIN: INPUT RESI PENGIRIMAN
    // ============================================
    public function addTrackingNumber(Request $request, $id)
    {
        if (Auth::user()->role_id != 1) {
            abort(403, 'Hanya admin yang bisa input resi!');
        }

        $request->validate([
            'tracking_number' => 'required|string|min:3',
            'courier' => 'required|string|in:JNE,JNT'
        ]);

        $order = Order::findOrFail($id);

        if ($order->payment_status != 'verified') {
            return back()->with('error', 'Pembayaran belum diverifikasi!');
        }

        $order->update([
            'tracking_number' => $request->tracking_number,
            'courier' => $request->courier,
            'status' => 'shipped'
        ]);

        return back()->with('success', 'Resi pengiriman berhasil ditambahkan! Status order: Shipped');
    }

    // ============================================
    // ADMIN: UPDATE STATUS ORDER
    // ============================================
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role_id != 1) {
            abort(403, 'Hanya admin yang bisa update status!');
        }

        $request->validate([
            'status' => 'required|in:pending,shipped,completed,cancelled'
        ]);

        $order = Order::with('items')->findOrFail($id);

        if ($request->status == 'shipped' && $order->payment_status != 'verified') {
            return back()->with('error', 'Pembayaran belum diverifikasi!');
        }

        // Jika status diubah menjadi cancelled, stok dikembalikan
        if ($request->status == 'cancelled' && $order->status != 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->increment('stock', $item->quantity);
                    }
                }
            }
        }

        $order->update([
            'status' => $request->status
        ]);

        $statusLabels = [
            'pending' => 'Pending',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        return back()->with('success', 'Status berhasil diubah menjadi: ' . ($statusLabels[$request->status] ?? $request->status));
    }

    // ============================================
    // ADMIN: INDEX (DAFTAR SEMUA ORDER + PAGINATION)
    // ============================================
    public function adminIndex(Request $request)
    {
        if (Auth::user()->role_id != 1) {
            abort(403, 'Hanya admin yang bisa akses!');
        }

        // Base query
        $query = Order::with(['user', 'items']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Ambil data dengan pagination (10 per halaman)
        // withQueryString() supaya search & filter tetap ada saat pindah halaman
        $orders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Stats (ambil dari seluruh data, bukan cuma halaman ini)
        $allOrders = Order::query();

        // Terapkan filter yang sama untuk stats
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $allOrders->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                });
            });
        }
        if ($request->has('status') && $request->status != '') {
            $allOrders->where('status', $request->status);
        }

        $stats = [
            'total' => (clone $allOrders)->count(),
            'pending' => (clone $allOrders)->where('status', 'pending')->count(),
            'shipped' => (clone $allOrders)->where('status', 'shipped')->count(),
            'completed' => (clone $allOrders)->where('status', 'completed')->count(),
        ];

        return view('pages.dashboard.order.index', compact('orders', 'stats'));
    }
}