@extends('layouts.app')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-violet-800 py-8">
    <x-navbar />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20 text-center">
                
                <div class="w-24 h-24 mx-auto mb-6 bg-green-500/20 rounded-full flex items-center justify-center">
                    <i class="bi bi-check-circle text-green-400 text-5xl"></i>
                </div>

                <h2 class="text-3xl font-bold text-white mb-2">✅ Pembayaran Berhasil!</h2>
                <p class="text-gray-400 mb-6">Terima kasih telah berbelanja di {{ config('app.name') }}</p>

                @php
                    // Hitung subtotal dari semua item
                    $subtotal = $order->items->sum(function($item) {
                        return $item->price * $item->quantity;
                    });
                @endphp

                <div class="bg-white/5 rounded-xl p-6 text-left mb-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-400 text-sm">Nomor Order</p>
                            <p class="text-white font-medium">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Total</p>
                            <p class="text-white font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-400">
                        <p>Subtotal: Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                        <p>Ongkos Kirim: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                        <p>Biaya Operasional: Rp {{ number_format($order->operational_cost ?? 6000, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('user.profile') }}" 
                       class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                        <i class="bi bi-box-seam"></i>
                        Lihat Pesanan
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="flex-1 px-6 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 transition">
                        <i class="bi bi-bag"></i>
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection