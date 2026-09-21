@extends('layouts.app')
@section('content')

<div class="min-h-screen bg-white">
    <x-navbar />

    <!-- ============================================ -->
    <!-- HEADER -->
    <!-- ============================================ -->
    <div class="pt-24 lg:pt-32 border-b border-gray-200">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="flex items-center justify-between py-6">
                <div>
                    <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500 mb-2">
                        Order Detail
                    </p>
                    <h1 class="text-3xl lg:text-4xl font-black text-black tracking-tight uppercase leading-none">
                        {{ $order->order_number }}
                    </h1>
                </div>
                <a href="{{ route('user.profile') }}" 
                   class="text-[11px] tracking-[0.2em] uppercase text-black border-b border-black pb-1 hover:opacity-60 transition-opacity">
                    ← Back to Profile
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-6 lg:px-12 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- ============================================ -->
            <!-- LEFT: MAIN CONTENT -->
            <!-- ============================================ -->
            <div class="lg:col-span-2 space-y-12">

                <!-- ORDER INFO -->
                <div>
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
                        <h2 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                            Order Information
                        </h2>
                        <span class="text-[10px] tracking-[0.15em] uppercase font-medium px-3 py-1 border
                            @if($order->status == 'completed') border-black bg-black text-white
                            @elseif($order->status == 'pending') border-gray-400 text-gray-700
                            @elseif($order->status == 'shipped') border-black text-black
                            @elseif($order->status == 'cancelled') border-red-500 text-red-500
                            @else border-gray-300 text-gray-500
                            @endif">
                            {{ $order->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 mb-1">Order Number</p>
                            <p class="text-[12px] font-medium text-black tracking-wider">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 mb-1">Date</p>
                            <p class="text-[12px] font-medium text-black">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 mb-1">Total</p>
                            <p class="text-[13px] font-bold text-black">IDR {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 mb-1">Method</p>
                            <p class="text-[12px] font-medium text-black tracking-wider uppercase">{{ $order->payment_method }}</p>
                        </div>
                    </div>
                </div>

                <!-- COUNTDOWN / PAYMENT DEADLINE -->
                @if($order->payment_status == 'pending' && $order->status != 'cancelled')
                <div class="border border-black p-6">
                    <div class="flex items-center gap-4">
                        <i class="bi bi-clock text-black text-xl"></i>
                        <div>
                            <p class="text-[11px] tracking-[0.2em] uppercase font-bold text-black mb-1">
                                Payment Deadline
                            </p>
                            <p class="text-[12px] text-gray-600">
                                {{ $order->expired_at ? $order->expired_at->format('d M Y, H:i') : '-' }}
                                <span class="text-black font-bold ml-2" id="countdown-timer">
                                    ({{ $order->expired_at ? now()->diffInMinutes($order->expired_at) : 0 }} min left)
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- VIRTUAL ACCOUNT (BCA) -->
                @if($order->payment_method == 'Bank BCA' && $order->payment_status == 'pending')
                <div class="border border-black p-6">
                    <p class="text-[11px] tracking-[0.2em] uppercase text-gray-500 mb-2">
                        Virtual Account BCA
                    </p>
                    <p class="text-2xl lg:text-3xl font-black text-black tracking-widest mb-1">
                        {{ '888' . str_pad($order->user_id, 10, '0', STR_PAD_LEFT) }}
                    </p>
                    <p class="text-[11px] tracking-wider uppercase text-gray-500">a.n. {{ env('APP_NAME') }}</p>
                </div>
                @endif

                <!-- QRIS -->
                @if($order->payment_method == 'QRIS' && $order->payment_status == 'pending')
                <div class="border border-black p-6 text-center">
                    <i class="bi bi-qr-code text-black text-4xl mb-3 block"></i>
                    <p class="text-[11px] tracking-[0.2em] uppercase font-bold text-black mb-1">
                        Scan QRIS to Pay
                    </p>
                    <p class="text-[11px] tracking-wider uppercase text-gray-500 mb-4">
                        Gopay, OVO, ShopeePay, etc.
                    </p>
                    <div class="bg-white border border-gray-200 p-4 inline-block">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=CRACKXUIT-{{ $order->order_number }}" 
                             alt="QRIS"
                             class="w-32 h-32">
                    </div>
                </div>
                @endif

                <!-- SHIPPING ADDRESS -->
                <div>
                    <div class="pb-4 mb-6 border-b border-gray-200">
                        <h2 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                            Shipping Address
                        </h2>
                    </div>

                    <div class="space-y-2">
                        <p class="text-[13px] text-black">{{ $order->address }}</p>
                        @if($order->city || $order->province)
                            <p class="text-[12px] text-gray-500">
                                {{ $order->city }}{{ $order->city && $order->province ? ', ' : '' }}{{ $order->province }}
                                @if($order->postal_code) — {{ $order->postal_code }} @endif
                            </p>
                        @endif
                        <p class="text-[12px] text-gray-500 tracking-wider">Phone: {{ $order->phone }}</p>
                        <p class="text-[11px] tracking-wider uppercase text-gray-500">
                            Courier: {{ $order->courier ?? $order->shipping_method }} · 
                            IDR {{ number_format($order->shipping_cost, 0, ',', '.') }}
                        </p>

                        @if($order->tracking_number)
                            <div class="mt-4 border-l-2 border-black pl-4 py-2">
                                <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 mb-1">
                                    Tracking Number
                                </p>
                                <p class="text-[13px] font-bold text-black tracking-wider mb-1">
                                    {{ $order->tracking_number }}
                                </p>
                                <p class="text-[11px] text-gray-500 tracking-wider uppercase mb-3">
                                    Courier: {{ $order->courier }}
                                </p>
                                <a href="https://www.jne.co.id/id/tracking" 
                                   target="_blank"
                                   class="inline-block border border-black text-black px-6 py-2 text-[10px] font-medium tracking-[0.2em] uppercase hover:bg-black hover:text-white transition-all">
                                    Track Package
                                </a>
                            </div>
                        @elseif($order->status == 'shipped')
                            <div class="mt-4 border-l-2 border-gray-400 pl-4 py-2">
                                <p class="text-[11px] tracking-wider uppercase text-gray-500">
                                    Package is being shipped, tracking number will be updated soon
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- PRODUCTS -->
                <div>
                    <div class="pb-4 mb-6 border-b border-gray-200">
                        <h2 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                            Products Ordered
                        </h2>
                    </div>

                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between py-4 border-b border-gray-100 last:border-0">
                                <div class="flex items-center gap-4">
                                    @php
                                        $productImage = null;
                                        if($item->product && $item->product->primaryImage) {
                                            $productImage = asset('storage/' . $item->product->primaryImage->path);
                                        } elseif($item->product && $item->product->images && $item->product->images->first()) {
                                            $productImage = asset('storage/' . $item->product->images->first()->path);
                                        }
                                    @endphp

                                    @if($productImage)
                                        <img src="{{ $productImage }}" 
                                             alt="{{ $item->product_name }}"
                                             class="w-16 h-16 object-cover border border-gray-200">
                                    @else
                                        <div class="w-16 h-16 bg-gray-50 border border-gray-200 flex items-center justify-center">
                                            <i class="bi bi-image text-gray-300"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-[12px] font-medium text-black tracking-wider uppercase">
                                            {{ $item->product_name }}
                                        </p>
                                        <p class="text-[11px] text-gray-500 tracking-wider uppercase mt-1">
                                            Size: {{ $item->size ?? '-' }} · Qty: {{ $item->quantity }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[12px] font-bold text-black">
                                        IDR {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- TOTAL BREAKDOWN -->
                    @php
                        $subtotal = $order->items->sum(function($item) {
                            return $item->price * $item->quantity;
                        });
                    @endphp

                    <div class="pt-6 mt-6 border-t border-gray-200 space-y-2">
                        <div class="flex justify-between text-[12px]">
                            <span class="text-gray-500 tracking-wider uppercase">Subtotal</span>
                            <span class="text-black">IDR {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[12px]">
                            <span class="text-gray-500 tracking-wider uppercase">Shipping</span>
                            <span class="text-black">IDR {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[12px]">
                            <span class="text-gray-500 tracking-wider uppercase">Handling</span>
                            <span class="text-black">IDR {{ number_format($order->operational_cost ?? 6000, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 mt-4 border-t border-gray-200">
                            <span class="text-[13px] font-bold tracking-[0.15em] uppercase text-black">Total</span>
                            <span class="text-[16px] font-bold text-black">IDR {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- RIGHT: SIDEBAR -->
            <!-- ============================================ -->
            <div class="lg:col-span-1 space-y-8">

                <!-- STATUS TIMELINE -->
                <div class="border border-gray-200 p-6">
                    <div class="pb-4 mb-6 border-b border-gray-200">
                        <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase text-black">
                            Order Status
                        </h3>
                    </div>

                    <div class="space-y-6">
                        <!-- Step 1 -->
                        <div class="flex items-start gap-3">
                            <div class="w-3 h-3 rounded-full mt-1.5
                                @if(in_array($order->status, ['pending', 'shipped', 'completed'])) bg-black
                                @else bg-gray-300 @endif">
                            </div>
                            <div>
                                <p class="text-[11px] tracking-[0.15em] uppercase font-bold
                                    @if(in_array($order->status, ['pending', 'shipped', 'completed'])) text-black
                                    @else text-gray-400 @endif">
                                    Order Placed
                                </p>
                                <p class="text-[10px] text-gray-500 tracking-wider uppercase mt-0.5">
                                    Confirming order
                                </p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex items-start gap-3">
                            <div class="w-3 h-3 rounded-full mt-1.5
                                @if(in_array($order->status, ['shipped', 'completed'])) bg-black
                                @else bg-gray-300 @endif">
                            </div>
                            <div>
                                <p class="text-[11px] tracking-[0.15em] uppercase font-bold
                                    @if(in_array($order->status, ['shipped', 'completed'])) text-black
                                    @else text-gray-400 @endif">
                                    Shipped
                                </p>
                                <p class="text-[10px] text-gray-500 tracking-wider uppercase mt-0.5">
                                    On delivery
                                </p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-start gap-3">
                            <div class="w-3 h-3 rounded-full mt-1.5
                                @if($order->status == 'completed') bg-black
                                @else bg-gray-300 @endif">
                            </div>
                            <div>
                                <p class="text-[11px] tracking-[0.15em] uppercase font-bold
                                    @if($order->status == 'completed') text-black
                                    @else text-gray-400 @endif">
                                    Completed
                                </p>
                                <p class="text-[10px] text-gray-500 tracking-wider uppercase mt-0.5">
                                    Order finished
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($order->payment_status == 'paid')
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500">
                                Awaiting admin verification
                            </p>
                        </div>
                    @endif

                    @if($order->payment_status == 'verified')
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <p class="text-[10px] tracking-[0.2em] uppercase text-black font-bold">
                                ✓ Payment Verified
                            </p>
                        </div>
                    @endif
                </div>

                <!-- MIDTRANS PAY BUTTON -->
                @if($order->payment_method == 'Midtrans' && $order->payment_status == 'pending' && $order->status != 'cancelled')
                    <div class="border border-black p-6">
                        <div class="pb-4 mb-4 border-b border-gray-200">
                            <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase text-black">
                                Continue Payment
                            </h3>
                        </div>
                        <p class="text-[11px] text-gray-500 tracking-wider uppercase mb-4">
                            Click to continue to Midtrans payment page
                        </p>
                        <a href="{{ route('midtrans.pay', $order->id) }}" 
                           class="block bg-black text-white text-center py-4 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                            Pay Now
                        </a>
                    </div>
                @endif

                <!-- MANUAL CONFIRM PAYMENT -->
                @if($order->payment_method != 'Midtrans' && $order->payment_status == 'pending' && $order->status != 'cancelled')
                    <div class="border border-gray-200 p-6">
                        <div class="pb-4 mb-4 border-b border-gray-200">
                            <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase text-black">
                                Confirm Payment
                            </h3>
                        </div>
                        
                        <div class="bg-gray-50 p-4 mb-4">
                            <p class="text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                Transfer to:
                            </p>
                            @if($order->payment_method == 'Bank BCA')
                                <p class="text-[13px] font-bold text-black tracking-wider">
                                    BCA - 888{{ str_pad($order->user_id, 10, '0', STR_PAD_LEFT) }}
                                </p>
                            @elseif($order->payment_method == 'QRIS')
                                <p class="text-[13px] font-bold text-black tracking-wider">
                                    Scan QRIS Above
                                </p>
                            @else
                                <p class="text-[13px] font-bold text-black tracking-wider">
                                    {{ $order->payment_method }}
                                </p>
                            @endif
                            <p class="text-[10px] tracking-wider uppercase text-gray-500 mt-1">a.n. {{ env('APP_NAME') }}</p>
                        </div>

                        <form action="{{ route('order.confirm.payment', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    onclick="return confirm('Confirm that you have made the payment?')"
                                    class="w-full bg-black text-white py-4 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                                I Have Paid
                            </button>
                        </form>
                    </div>
                @endif

                <!-- CANCEL BUTTON -->
                @if($order->status == 'pending' && $order->payment_status == 'pending')
                    <div class="border border-gray-200 p-6">
                        <form action="{{ route('order.cancel', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    onclick="return confirm('Cancel this order?')"
                                    class="w-full border border-red-500 text-red-500 py-4 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-red-500 hover:text-white transition-all duration-300">
                                Cancel Order
                            </button>
                        </form>
                    </div>
                @endif

                <!-- CANCELLED MESSAGE -->
                @if($order->status == 'cancelled')
                    <div class="border border-red-500 p-6 text-center">
                        <i class="bi bi-x-circle text-red-500 text-3xl block mb-3"></i>
                        <p class="text-[12px] tracking-[0.2em] uppercase font-bold text-red-500 mb-2">
                            Order Cancelled
                        </p>
                        @if($order->payment_status == 'expired')
                            <p class="text-[11px] tracking-wider uppercase text-red-400">
                                Payment deadline expired
                            </p>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- COUNTDOWN TIMER -->
<!-- ============================================ -->
@if($order->payment_status == 'pending' && $order->expired_at)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const expiredAt = new Date('{{ $order->expired_at }}').getTime();
    const timerElement = document.getElementById('countdown-timer');
    
    if (!timerElement) return;
    
    const timer = setInterval(function() {
        const now = new Date().getTime();
        const distance = expiredAt - now;
        
        if (distance < 0) {
            clearInterval(timer);
            timerElement.textContent = 'Time expired!';
            timerElement.className = 'text-red-500 font-bold ml-2';
            return;
        }
        
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        timerElement.textContent = 
            '(' + String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0') + ' min left)';
        
        if (minutes < 5) {
            timerElement.className = 'text-red-500 font-bold ml-2';
        }
    }, 1000);
});
</script>
@endif

@endsection