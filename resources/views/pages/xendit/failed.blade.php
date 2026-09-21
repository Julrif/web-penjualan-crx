@extends('layouts.app')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-violet-800 py-8">
    <x-navbar />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20 text-center">
                
                <div class="w-24 h-24 mx-auto mb-6 bg-red-500/20 rounded-full flex items-center justify-center">
                    <i class="bi bi-x-circle text-red-400 text-5xl"></i>
                </div>

                <h2 class="text-3xl font-bold text-white mb-2">❌ Pembayaran Gagal</h2>
                <p class="text-gray-400 mb-6">Terjadi kesalahan saat memproses pembayaran Anda</p>

                <div class="bg-white/5 rounded-xl p-6 text-left mb-6">
                    <p class="text-gray-400 text-sm">Nomor Order</p>
                    <p class="text-white font-medium">{{ $order->order_number }}</p>
                    <p class="text-gray-400 text-sm mt-2">Total</p>
                    <p class="text-white font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('checkout.index') }}" 
                       class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Coba Lagi
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