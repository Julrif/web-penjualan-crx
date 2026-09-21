@extends('layouts.app')
@section('content')

<div class="min-h-screen bg-white">
    <x-navbar />

    <!-- HEADER -->
    <div class="pt-24 lg:pt-32 border-b border-gray-200">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="flex items-center justify-between py-6">
                <div>
                    <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500 mb-2">
                        My Account
                    </p>
                    <h1 class="text-3xl lg:text-5xl font-black text-black tracking-tight uppercase leading-none">
                        My Wishlist
                    </h1>
                </div>
                <a href="{{ route('user.profile') }}" 
                   class="text-[11px] tracking-[0.2em] uppercase text-black border-b border-black pb-1 hover:opacity-60 transition-opacity">
                    Back to Profile
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-6 lg:px-12 py-12">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-[12px] tracking-wider uppercase mb-8">
                {{ session('success') }}
            </div>
        @endif

        @if($wishlists->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-20">
                <i class="bi bi-heart text-5xl text-gray-300 block mb-6"></i>
                <p class="text-[13px] tracking-[0.2em] uppercase text-black mb-3">
                    Your wishlist is empty
                </p>
                <p class="text-[12px] text-gray-500 mb-8">
                    Start adding your favorite products
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-black text-white px-10 py-4 text-[12px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                    Shop Now
                </a>
            </div>
        @else
            <!-- Wishlist Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-12">
                @foreach($wishlists as $wishlist)
                    @php
                        $product = $wishlist->product;
                        $totalStock = $product->variants->sum('stock');
                        $isSoldOut = $totalStock <= 0;
                        
                        $imagePath = null;
                        if($product->primaryImage) {
                            $imagePath = asset('storage/' . $product->primaryImage->path);
                        } elseif($product->images && $product->images->first()) {
                            $imagePath = asset('storage/' . $product->images->first()->path);
                        } else {
                            $imagePath = asset('images/default-product.png');
                        }
                    @endphp

                    <div class="group">
                        <!-- Product Image -->
                        <div class="relative aspect-square bg-gray-50 overflow-hidden mb-3">
                            <a href="{{ route('product.detail', $product->id) }}">
                                <img src="{{ $imagePath }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </a>
                            
                            <!-- Sold Out Badge -->
                            @if($isSoldOut)
                                <div class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-bold px-3 py-1 tracking-wider uppercase">
                                    Sold Out
                                </div>
                            @endif

                            <!-- Remove Button -->
                            <form action="{{ route('user.wishlist.delete', $wishlist->id) }}" method="POST" 
                                  class="absolute top-3 right-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Hapus dari wishlist?')"
                                        class="bg-white border border-black w-8 h-8 flex items-center justify-center hover:bg-black hover:text-white transition-all">
                                    <i class="bi bi-x text-[14px]"></i>
                                </button>
                            </form>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="space-y-2">
                            <a href="{{ route('product.detail', $product->id) }}">
                                <h3 class="text-[12px] font-medium text-black tracking-wider uppercase line-clamp-1 group-hover:opacity-60 transition-opacity">
                                    {{ $product->name }}
                                </h3>
                            </a>
                            <p class="text-[12px] text-gray-500 tracking-wider">
                                IDR {{ number_format($product->price, 0, ',', '.') }},00
                            </p>

                            <!-- Stock Info -->
                            <p class="text-[10px] tracking-wider uppercase {{ $isSoldOut ? 'text-red-500' : 'text-gray-400' }}">
                                @if($isSoldOut)
                                    Out of Stock
                                @else
                                    Stock: {{ $totalStock }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection