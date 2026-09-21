@extends("layouts.app")
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
                    <h1 class="text-3xl lg:text-5xl font-black text-black tracking-tight uppercase leading-none">
                        Shopping Bag
                    </h1>
                    <p class="text-[11px] tracking-[0.2em] uppercase text-gray-500 mt-2">
                        {{ $cartItems->count() }} {{ $cartItems->count() == 1 ? 'Item' : 'Items' }}
                    </p>
                </div>
                <a href="{{ route('products.index') }}" 
                   class="text-[11px] tracking-[0.2em] uppercase text-black border-b border-black pb-1 hover:opacity-60 transition-opacity">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-6 lg:px-12 py-12">
        
        @if($cartItems->isEmpty())
            <!-- ============================================ -->
            <!-- EMPTY CART -->
            <!-- ============================================ -->
            <div class="text-center py-20">
                <i class="bi bi-bag text-5xl text-gray-300 block mb-6"></i>
                <h3 class="text-[14px] font-medium tracking-[0.2em] uppercase text-black mb-3">
                    Your cart is empty
                </h3>
                <p class="text-[12px] text-gray-500 mb-8">
                    Start shopping to find amazing products
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-black text-white px-10 py-4 text-[12px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                    Shop Now
                </a>
            </div>
        @else

            @if (session()->has('message'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-[12px] tracking-wider uppercase mb-6">
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-[12px] tracking-wider uppercase mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- ============================================ -->
                <!-- LEFT: CART ITEMS -->
                <!-- ============================================ -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Header Row -->
                    <div class="grid grid-cols-12 gap-4 pb-4 border-b border-gray-200 text-[11px] tracking-[0.2em] uppercase text-gray-500">
                        <div class="col-span-7">Product</div>
                        <div class="col-span-5 text-right">Total</div>
                    </div>

                    <!-- Cart Items -->
                    <div class="divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                            <div class="py-6 cart-item">
                                <div class="grid grid-cols-12 gap-4 items-start">
                                    
                                    <!-- Product Image & Info -->
                                    <div class="col-span-7">
                                        <div class="flex gap-4">
                                            <!-- Image -->
                                            <div class="relative flex-shrink-0">
                                                @php
                                                    $productImage = null;
                                                    if($item->product && $item->product->primaryImage) {
                                                        $productImage = asset('storage/' . $item->product->primaryImage->path);
                                                    } elseif($item->product && $item->product->images && $item->product->images->first()) {
                                                        $productImage = asset('storage/' . $item->product->images->first()->path);
                                                    }
                                                @endphp

                                                @if($productImage)
                                                    <a href="{{ route('product.detail', $item->product->id) }}">
                                                        <img src="{{ $productImage }}" 
                                                             alt="{{ $item->product->name }}"
                                                             class="w-24 h-24 object-cover bg-gray-50">
                                                    </a>
                                                @else
                                                    <div class="w-24 h-24 bg-gray-50 flex items-center justify-center">
                                                        <i class="bi bi-image text-gray-300 text-2xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Info -->
                                            <div class="flex-1 min-w-0">
                                                @if($item->product)
                                                    <a href="{{ route('product.detail', $item->product->id) }}" 
                                                       class="block text-[12px] font-medium text-black tracking-wider uppercase hover:opacity-60 transition-opacity mb-1">
                                                        {{ $item->product->name }}
                                                    </a>
                                                    <p class="text-[11px] text-gray-500 tracking-wider uppercase mb-3">
                                                        Size: <span class="text-black">{{ $item->size ?? '-' }}</span>
                                                        <span class="mx-1">·</span>
                                                        Qty: <span class="text-black">{{ $item->quantity }}</span>
                                                    </p>
                                                    <p class="text-[12px] text-black">
                                                        IDR {{ number_format($item->product->price, 0, ',', '.') }},00
                                                    </p>
                                                    
                                                    <!-- Delete Button -->
                                                    <form action="{{ route('user.keranjang.delete', $item->id) }}" method="POST" class="mt-3">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button onclick="return confirm('Remove this item from cart?')"
                                                                class="text-[11px] text-gray-500 hover:text-black tracking-wider uppercase underline transition-colors">
                                                            Remove
                                                        </button>
                                                    </form>
                                                @else
                                                    <p class="text-[12px] text-red-500 tracking-wider uppercase">
                                                        Product not found
                                                    </p>
                                                    <form action="{{ route('user.keranjang.delete', $item->id) }}" method="POST" class="mt-3">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="text-[11px] text-gray-500 hover:text-black tracking-wider uppercase underline">
                                                            Remove
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="col-span-5 text-right">
                                        @if($item->product)
                                            <p class="text-[14px] font-medium text-black">
                                                IDR {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }},00
                                            </p>
                                            @if($item->quantity > 1)
                                                <p class="text-[11px] text-gray-500 tracking-wider uppercase mt-1">
                                                    {{ $item->quantity }} × IDR {{ number_format($item->product->price, 0, ',', '.') }}
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- RIGHT: ORDER SUMMARY -->
                <!-- ============================================ -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 p-8 sticky top-32">
                        <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black mb-6 pb-4 border-b border-gray-200">
                            Order Summary
                        </h3>
                        
                        <div class="space-y-4 text-[12px]">
                            <!-- Subtotal -->
                            <div class="flex justify-between items-center tracking-wider uppercase">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="text-black font-medium">IDR {{ number_format($subtotal, 0, ',', '.') }},00</span>
                            </div>
                            
                            <!-- Shipping -->
                            <div class="flex justify-between items-center tracking-wider uppercase">
                                <span class="text-gray-500">Shipping</span>
                                <span class="text-black font-medium">
                                    @if($subtotal > 500000)
                                        FREE
                                    @else
                                        IDR {{ number_format($shippingCost, 0, ',', '.') }},00
                                    @endif
                                </span>
                            </div>

                            <!-- Operational Cost -->
                            <div class="flex justify-between items-center tracking-wider uppercase">
                                <span class="text-gray-500">Handling</span>
                                <span class="text-black font-medium">IDR {{ number_format($operationalCost, 0, ',', '.') }},00</span>
                            </div>
                            
                            <!-- Divider -->
                            <div class="border-t border-gray-300 my-4"></div>
                            
                            <!-- Total -->
                            <div class="flex justify-between items-center">
                                <span class="text-[13px] font-bold tracking-[0.2em] uppercase text-black">Total</span>
                                <span class="text-[16px] font-bold text-black">
                                    IDR {{ number_format($total, 0, ',', '.') }},00
                                </span>
                            </div>
                        </div>
                        
                        <!-- Checkout Button -->
                        <a href="{{ route('checkout.index') }}" 
                           class="block w-full mt-8 bg-black text-white text-center py-4 text-[12px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                            Checkout
                        </a>

                        <!-- Info -->
                        <p class="text-[10px] text-gray-500 tracking-wider uppercase text-center mt-4">
                            Free shipping over Rp 500.000
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push("styles")
<style>
    .cart-item {
        transition: opacity 0.2s ease;
    }
    
    .cart-item:hover {
        opacity: 0.85;
    }
</style>
@endpush

@endsection