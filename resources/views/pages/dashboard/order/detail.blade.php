@extends('layouts.dashboard')
@section('content')

<div class="min-h-screen bg-white">
    <x-sidebar />

    <main class="ml-64">

        <!-- ============================================ -->
        <!-- HEADER -->
        <!-- ============================================ -->
        <div class="border-b border-gray-200">
            <div class="px-8 lg:px-12 py-6 flex items-center justify-between">
                <div>
                    <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500 mb-2">
                        Order Detail
                    </p>
                    <h1 class="text-3xl lg:text-4xl font-black text-black tracking-tight uppercase leading-none">
                        {{ $order->order_number }}
                    </h1>
                </div>
                <a href="{{ route('admin.orders.index') }}" 
                   class="text-[11px] tracking-[0.2em] uppercase text-black border-b border-black pb-1 hover:opacity-60 transition-opacity">
                    ← Back
                </a>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- ALERTS -->
        <!-- ============================================ -->
        @if(session('success'))
            <div class="mx-8 lg:mx-12 mt-6 border-l-2 border-black bg-gray-50 px-6 py-4">
                <p class="text-[12px] tracking-wider uppercase text-black">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-8 lg:mx-12 mt-6 border-l-2 border-red-500 bg-red-50 px-6 py-4">
                <p class="text-[12px] tracking-wider uppercase text-red-600">{{ session('error') }}</p>
            </div>
        @endif

        <div class="px-8 lg:px-12 py-8 grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- ============================================ -->
            <!-- LEFT: MAIN CONTENT -->
            <!-- ============================================ -->
            <div class="lg:col-span-2 space-y-12">

                <!-- INFO ORDER -->
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
                            <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 mb-1">Customer</p>
                            <p class="text-[12px] font-medium text-black tracking-wider uppercase">{{ $order->user->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 mb-1">Date</p>
                            <p class="text-[12px] font-medium text-black">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 mb-1">Total</p>
                            <p class="text-[13px] font-bold text-black">IDR {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

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
                        
                        <div class="flex items-center gap-6 pt-2 text-[11px] tracking-wider uppercase text-gray-500">
                            <span>Courier: {{ $order->shipping_method }}</span>
                            <span>Cost: IDR {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            <span>Payment: {{ $order->payment_method }}</span>
                        </div>

                        @if($order->tracking_number)
                            <div class="mt-4 border-l-2 border-black pl-4 py-2">
                                <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 mb-1">Tracking Number</p>
                                <p class="text-[13px] font-bold text-black tracking-wider">{{ $order->tracking_number }}</p>
                                <p class="text-[11px] text-gray-500 tracking-wider uppercase">Courier: {{ $order->courier }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- PRODUCTS -->
                <div>
                    <div class="pb-4 mb-6 border-b border-gray-200">
                        <h2 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                            Products
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
                                    <p class="text-[10px] text-gray-500 tracking-wider">
                                        @ IDR {{ number_format($item->price, 0, ',', '.') }}
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

                <!-- PAYMENT STATUS -->
                <div class="border border-gray-200 p-6">
                    <div class="pb-4 mb-4 border-b border-gray-200">
                        <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase text-black">
                            Payment Status
                        </h3>
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <span class="text-[11px] tracking-wider uppercase text-gray-500">Status</span>
                        <span class="text-[10px] tracking-[0.15em] uppercase font-medium px-3 py-1 border
                            @if($order->payment_status == 'verified') border-black bg-black text-white
                            @elseif($order->payment_status == 'paid') border-gray-400 text-gray-700
                            @elseif($order->payment_status == 'rejected') border-red-500 text-red-500
                            @else border-gray-300 text-gray-500
                            @endif">
                            {{ $order->payment_status ?? 'pending' }}
                        </span>
                    </div>

                    @if($order->paid_at)
                        <p class="text-[10px] text-gray-500 tracking-wider uppercase mb-1">
                            Paid: {{ $order->paid_at->format('d M Y, H:i') }}
                        </p>
                    @endif
                    @if($order->verified_at)
                        <p class="text-[10px] text-gray-500 tracking-wider uppercase">
                            Verified: {{ $order->verified_at->format('d M Y, H:i') }}
                        </p>
                    @endif

                    <!-- Verify Buttons -->
                    @if($order->payment_status == 'paid')
                        <div class="mt-6 space-y-2">
                            <form action="{{ route('admin.orders.verify.payment', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        class="w-full bg-black text-white py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all">
                                    Verify Payment
                                </button>
                            </form>
                            <form action="{{ route('admin.orders.reject.payment', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        class="w-full border border-gray-300 text-black py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:border-black transition-all"
                                        onclick="return confirm('Reject this payment?')">
                                    Reject Payment
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($order->payment_status == 'pending' && $order->status != 'cancelled')
                        <div class="mt-6">
                            <form action="{{ route('admin.orders.verify.payment', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        class="w-full bg-black text-white py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all"
                                        onclick="return confirm('Verify this payment manually?')">
                                    Verify Manually
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- INPUT RESI -->
                @if($order->payment_status == 'verified' && $order->status != 'shipped' && $order->status != 'completed')
                <div class="border border-gray-200 p-6">
                    <div class="pb-4 mb-4 border-b border-gray-200">
                        <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase text-black">
                            Input Tracking
                        </h3>
                    </div>

                    <form action="{{ route('admin.orders.add.resi', $order->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-2">Courier</label>
                            <select name="courier" 
                                    class="w-full border border-gray-300 px-4 py-3 text-[12px] text-black focus:outline-none focus:border-black">
                                <option value="JNE">JNE</option>
                                <option value="JNT">J&T</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-2">Tracking Number</label>
                            <input type="text" 
                                   name="tracking_number" 
                                   class="w-full border border-gray-300 px-4 py-3 text-[12px] text-black focus:outline-none focus:border-black"
                                   placeholder="Enter tracking number..."
                                   required>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-black text-white py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all">
                            Send & Update Status
                        </button>
                    </form>
                </div>
                @endif

                <!-- TRACKING INFO -->
                @if($order->tracking_number)
                <div class="border border-black p-6">
                    <div class="pb-4 mb-4 border-b border-gray-200">
                        <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase text-black">
                            Tracking Info
                        </h3>
                    </div>

                    <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 mb-1">Tracking Number</p>
                    <p class="text-[14px] font-bold text-black tracking-wider mb-3">{{ $order->tracking_number }}</p>
                    
                    <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 mb-1">Courier</p>
                    <p class="text-[12px] text-black tracking-wider uppercase mb-4">{{ $order->courier }}</p>

                    <a href="https://www.jne.co.id/id/tracking" 
                       target="_blank"
                       class="block w-full border border-black text-black text-center py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-black hover:text-white transition-all">
                        Track Package
                    </a>
                </div>
                @endif

                <!-- UPDATE STATUS -->
                <div class="border border-gray-200 p-6">
                    <div class="pb-4 mb-4 border-b border-gray-200">
                        <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase text-black">
                            Update Status
                        </h3>
                    </div>

                    <form action="{{ route('admin.orders.update.status', $order->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <select name="status" 
                                class="w-full border border-gray-300 px-4 py-3 text-[12px] text-black focus:outline-none focus:border-black">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        
                        <button type="submit" 
                                class="w-full bg-black text-white py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection