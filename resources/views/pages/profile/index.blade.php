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
                        Account
                    </p>
                    <h1 class="text-3xl lg:text-5xl font-black text-black tracking-tight uppercase leading-none">
                        My Profile
                    </h1>
                </div>
                <a href="{{ route('user.profile.settings') }}" 
                   class="text-[11px] tracking-[0.2em] uppercase text-black border-b border-black pb-1 hover:opacity-60 transition-opacity">
                    Settings
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-6 lg:px-12 py-12">

        <!-- ============================================ -->
        <!-- USER INFO -->
        <!-- ============================================ -->
        <div class="border border-gray-200 p-8 mb-12">
            <div class="flex items-center gap-6">
                
                <!-- Avatar -->
                <div class="w-20 h-20 bg-black flex items-center justify-center text-3xl font-black text-white">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                
                <!-- Info -->
                <div>
                    <p class="text-[11px] tracking-[0.2em] uppercase text-gray-500 mb-1">
                        Signed in as
                    </p>
                    <h2 class="text-2xl font-black text-black tracking-tight uppercase">
                        {{ Auth::user()->name }}
                    </h2>
                    <p class="text-[12px] text-gray-500 tracking-wider mt-1">
                        {{ Auth::user()->email }}
                    </p>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- ORDERS SECTION -->
        <!-- ============================================ -->
        <div>
            <div class="flex items-end justify-between mb-6 pb-4 border-b border-gray-200">
                <div>
                    <p class="text-[11px] tracking-[0.2em] uppercase text-gray-500 mb-1">
                        History
                    </p>
                    <h3 class="text-2xl lg:text-3xl font-black text-black tracking-tight uppercase">
                        My Orders
                    </h3>
                </div>
                <span class="text-[11px] tracking-wider uppercase text-gray-500">
                    {{ $orders->count() }} {{ $orders->count() == 1 ? 'Order' : 'Orders' }}
                </span>
            </div>

            @if($orders->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-20">
                    <i class="bi bi-inbox text-5xl text-gray-300 block mb-6"></i>
                    <p class="text-[13px] tracking-[0.2em] uppercase text-black mb-3">
                        No orders yet
                    </p>
                    <p class="text-[12px] text-gray-500 mb-8">
                        Start shopping to see your orders here
                    </p>
                    <a href="{{ route('products.index') }}" 
                       class="inline-block bg-black text-white px-10 py-4 text-[12px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                        Shop Now
                    </a>
                </div>
            @else
                <div class="divide-y divide-gray-200 border-t border-gray-200">
                    @foreach($orders as $order)
                        <a href="{{ route('order.detail', $order->id) }}" 
                           class="block py-6 hover:opacity-70 transition-opacity">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                
                                <!-- Order Number & Date -->
                                <div class="col-span-12 md:col-span-3">
                                    <p class="text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-1">
                                        Order Number
                                    </p>
                                    <p class="text-[12px] font-medium text-black tracking-wider">
                                        {{ $order->order_number }}
                                    </p>
                                    <p class="text-[11px] text-gray-500 mt-1">
                                        {{ $order->created_at->format('d M Y') }}
                                    </p>
                                </div>

                                <!-- Product -->
                                <div class="col-span-12 md:col-span-4">
                                    <p class="text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-1">
                                        Product
                                    </p>
                                    <p class="text-[12px] text-black tracking-wider uppercase">
                                        {{ $order->items->first()->product_name ?? 'Product' }}
                                    </p>
                                    <p class="text-[11px] text-gray-500 mt-1">
                                        {{ $order->items->sum('quantity') }} items
                                        @if($order->items->count() > 1)
                                            <span>· {{ $order->items->count() }} products</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Total -->
                                <div class="col-span-6 md:col-span-3 text-left md:text-right">
                                    <p class="text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-1">
                                        Total
                                    </p>
                                    <p class="text-[13px] font-bold text-black">
                                        IDR {{ number_format($order->total, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Status -->
                                <div class="col-span-6 md:col-span-2 flex justify-end items-center gap-3">
                                    <span class="text-[10px] tracking-[0.15em] uppercase font-medium px-3 py-1 border
                                        @if($order->status == 'completed') border-black bg-black text-white
                                        @elseif($order->status == 'pending') border-gray-400 text-gray-700
                                        @elseif($order->status == 'shipped') border-black text-black
                                        @elseif($order->status == 'cancelled') border-red-500 text-red-500
                                        @else border-gray-300 text-gray-500
                                        @endif">
                                        {{ $order->status }}
                                    </span>
                                    <i class="bi bi-arrow-right text-black text-[14px]"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection