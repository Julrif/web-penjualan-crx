@extends('layouts.app')
@section('content')

<div class="min-h-screen bg-gray-600 py-18">
    <x-navbar />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                
                <!-- Icon Sukses -->
                <div class="text-center mb-6">
                    <div class="w-24 h-24 mx-auto bg-green-500/20 rounded-full flex items-center justify-center">
                        <i class="bi bi-check-circle text-green-400 text-5xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white mt-4">🎉 Pesanan Berhasil!</h2>
                    <p class="text-gray-400 mt-2">Terima kasih telah berbelanja di {{ config('app.name') }}</p>
                </div>

                <!-- Alert Penting -->
                <div class="bg-yellow-500/20 border border-yellow-500/30 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-clock-history text-yellow-400 text-xl mt-1"></i>
                        <div>
                            <p class="text-yellow-400 font-semibold">⚠️ Selesaikan Pembayaran</p>
                            <p class="text-yellow-300 text-sm">
                                Pesanan Anda harus segera dibayar. Anda memiliki waktu 
                                <span class="font-bold text-white">1 jam</span> untuk menyelesaikan pembayaran.
                            </p>
                            <p class="text-yellow-300 text-sm mt-1">
                                Batas waktu: <span class="font-semibold text-white">{{ $order->expired_at ? $order->expired_at->format('d M Y, H:i') : '-' }}</span>
                            </p>
                            <p class="text-yellow-300 text-sm mt-1">
                                Sisa waktu: <span class="font-semibold text-white" id="countdown">59:59</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Info Order -->
                <div class="bg-white/5 rounded-xl p-6 mb-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-400 text-sm">Nomor Order</p>
                            <p class="text-white font-medium">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Tanggal</p>
                            <p class="text-white font-medium">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Total</p>
                            <p class="text-white font-bold text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Status</p>
                            <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-sm font-semibold">
                                ⏳ Menunggu Pembayaran
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pembayaran -->
                <div class="bg-white/5 rounded-xl p-6 mb-6">
                    <h4 class="text-white font-medium mb-4">
                        <i class="bi bi-credit-card text-indigo-400"></i>
                        Cara Pembayaran: <span class="text-indigo-400">{{ $order->payment_method }}</span>
                    </h4>
                    
                    @if($order->payment_method == 'Bank BCA')
                        <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
                            <div class="text-center">
                                <p class="text-gray-400 text-sm mb-2">Virtual Account BCA</p>
                                <p class="text-white text-3xl font-bold tracking-widest bg-blue-500/20 rounded-lg p-3">
                                    {{ '888' . str_pad($order->user_id, 10, '0', STR_PAD_LEFT) }}
                                </p>
                                <p class="text-blue-300 text-sm mt-2">a.n. CRACKXUIT</p>
                                <p class="text-gray-400 text-xs mt-3">Transfer ke nomor VA di atas melalui ATM/Mobile Banking</p>
                            </div>
                        </div>
                    @elseif($order->payment_method == 'QRIS')
                        <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 text-center">
                            <p class="text-gray-400 text-sm mb-2">Scan QRIS untuk membayar</p>
                            <div class="bg-white p-4 rounded-lg inline-block">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=CRACKXUIT-{{ $order->order_number }}" 
                                     alt="QRIS"
                                     class="w-32 h-32">
                            </div>
                            <p class="text-green-400 text-sm mt-3">Gopay • OVO • ShopeePay • Dll</p>
                        </div>
                    @endif
                </div>

                <!-- Detail Pesanan -->
                <div class="bg-white/5 rounded-xl p-4 mb-6">
                    <h4 class="text-white font-medium mb-3">
                        <i class="bi bi-box-seam text-indigo-400"></i>
                        Ringkasan Pesanan
                    </h4>
                    
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center py-2 border-b border-white/5 last:border-0">
                            <div>
                                <span class="text-gray-300">{{ $item->product_name }}</span>
                                @if($item->size)
                                    <span class="text-indigo-400 text-sm">({{ $item->size }})</span>
                                @endif
                                <span class="text-gray-400 text-sm ml-2">x{{ $item->quantity }}</span>
                            </div>
                            <span class="text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    
                    <div class="flex justify-between font-bold pt-3 border-t border-white/10 mt-3">
                        <span class="text-white">Total</span>
                        <span class="text-indigo-400 text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('order.detail', $order->id) }}" 
                       class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all">
                        <i class="bi bi-eye"></i>
                        Lihat Detail Order
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="flex-1 px-6 py-3 bg-white/10 text-white text-center font-semibold rounded-xl hover:bg-white/20 transition-all">
                        <i class="bi bi-bag"></i>
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Countdown timer untuk batas waktu pembayaran
document.addEventListener('DOMContentLoaded', function() {
    const expiredAt = new Date('{{ $order->expired_at }}').getTime();
    const countdownElement = document.getElementById('countdown');
    
    if (!countdownElement) return;
    
    const timer = setInterval(function() {
        const now = new Date().getTime();
        const distance = expiredAt - now;
        
        if (distance < 0) {
            clearInterval(timer);
            countdownElement.textContent = '⏰ Waktu habis!';
            countdownElement.className = 'text-red-400 font-semibold';
            return;
        }
        
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        countdownElement.textContent = 
            String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        
        // Warning jika kurang dari 5 menit
        if (minutes < 5) {
            countdownElement.className = 'text-red-400 font-semibold';
        }
    }, 1000);
});
</script>

@endsection