@extends('layouts.app')

@section('body-class', 'has-hero')

@section("content")

<div class="relative min-h-screen overflow-hidden">
    <x-navbar />

    <!-- ============================================ -->
    <!-- HERO SECTION - FULLSCREEN IMAGE -->
    <!-- ============================================ -->
    <div class="relative min-h-screen flex items-end overflow-hidden">
        
        <!-- Background Image -->
        <div class="absolute inset-0 -z-10">
            <div 
                class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                style="background-image: url('{{ asset('images/crx.JPEG') }}');"
            ></div>
            <!-- Overlay gelap minimalis -->
            <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <!-- Content - Bottom Left -->
        <div class="w-full max-w-[1600px] mx-auto px-6 lg:px-12 pb-16 lg:pb-24">
            <div class="max-w-4xl">
                
                <!-- Small Label -->
                <p class="text-[11px] font-medium text-white/70 tracking-[0.3em] uppercase mb-4">
                    
                </p>

                <!-- Main Title - SNSBWORLD STYLE -->
                <h1 class="text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-black text-white leading-[0.9] tracking-tight mb-6 uppercase">
                    Signature Collection
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg text-white/90 mb-8 max-w-xl leading-relaxed">
                    
                </p>

                <!-- CTA Button - Simple Black/White -->
                <a href="/products" class="inline-block bg-white text-black px-10 py-4 text-[13px] font-semibold tracking-[0.15em] uppercase hover:bg-black hover:text-white border border-white transition-all duration-300">
                    Shop Now
                </a>
            </div>
        </div>

        <!-- Bottom Info Bar -->
        <div class="absolute bottom-0 left-0 right-0 border-t border-white/20">
            <div class="max-w-[1600px] mx-auto px-6 lg:px-12 py-4">
                <div class="flex justify-between items-center text-[11px] text-white/70 tracking-wider uppercase">
                    <span>Free Shipping Over Rp 500.000</span>
                    <span class="hidden md:block">{{ env("APP_NAME") }} © 2026</span>
                    <span>IDR</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- FEATURED PRODUCTS SECTION -->
    <!-- ============================================ -->
    <div class="bg-white py-20 lg:py-32">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            
            <!-- Section Header -->
            <div class="flex justify-between items-end mb-12">
                <div>
                    <p class="text-[11px] font-medium text-gray-500 tracking-[0.3em] uppercase mb-2">
                        Featured
                    </p>
                    <h2 class="text-3xl lg:text-5xl font-black text-black tracking-tight uppercase">
                        New Arrival
                    </h2>
                </div>
                <a href="/products" class="hidden md:block text-[12px] font-medium text-black tracking-wider uppercase border-b border-black pb-1 hover:opacity-60 transition-opacity">
                    View All
                </a>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-12">
                @php
                    $featuredProducts = \App\Models\Product::with(['primaryImage', 'variants'])->latest()->take(4)->get();
                @endphp
                
                @forelse($featuredProducts as $product)
                    <a href="{{ route('product.detail', $product->id) }}" class="group block">
                        <!-- Product Image -->
                        <div class="relative aspect-square bg-gray-50 overflow-hidden mb-4">
                            @php
                                $imagePath = null;
                                if($product->primaryImage) {
                                    $imagePath = asset('storage/' . $product->primaryImage->path);
                                } elseif($product->images && $product->images->first()) {
                                    $imagePath = asset('storage/' . $product->images->first()->path);
                                } else {
                                    $imagePath = asset('images/default-product.png');
                                }
                            @endphp
                            <img src="{{ $imagePath }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        </div>
                        
                        <!-- Product Info -->
                        <div class="space-y-1">
                            <h3 class="text-[12px] font-medium text-black tracking-wider uppercase line-clamp-1">
                                {{ $product->name }}
                            </h3>
                            <p class="text-[12px] text-gray-500">
                                IDR {{ number_format($product->price, 0, ',', '.') }},00
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-sm">Belum ada produk</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- ABOUT / BRAND SECTION -->
    <!-- ============================================ -->
    <div class="bg-black text-white py-20 lg:py-32">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                <!-- Left: Text -->
                <div>
                    <p class="text-[11px] font-medium text-white/50 tracking-[0.3em] uppercase mb-4">
                        About Us
                    </p>
                    <h2 class="text-4xl lg:text-6xl font-black tracking-tight uppercase mb-6 leading-[0.95]">
                        More Than<br>Just Fashion
                    </h2>
                    <p class="text-base text-white/70 leading-relaxed mb-8 max-w-lg">
                        {{ env("APP_NAME") }} adalah brand fashion lokal yang menghadirkan koleksi eksklusif dengan kualitas premium. Setiap produk dirancang dengan detail dan passion untuk menampilkan gaya terbaik Anda.
                    </p>
                    <a href="/about" class="inline-block border border-white text-white px-8 py-3 text-[12px] font-medium tracking-[0.15em] uppercase hover:bg-white hover:text-black transition-all duration-300">
                        Learn More
                    </a>
                </div>

                <!-- Right: Stats -->
                <div class="grid grid-cols-2 gap-8">
                    <div class="border-t border-white/20 pt-6">
                        <div class="text-4xl lg:text-5xl font-black mb-2">0</div>
                        <div class="text-[11px] text-white/50 tracking-wider uppercase">Happy Customers</div>
                    </div>
                    <div class="border-t border-white/20 pt-6">
                        <div class="text-4xl lg:text-5xl font-black mb-2">2</div>
                        <div class="text-[11px] text-white/50 tracking-wider uppercase">Premium Products</div>
                    </div>
                    <div class="border-t border-white/20 pt-6">
                        <div class="text-4xl lg:text-5xl font-black mb-2">24/7</div>
                        <div class="text-[11px] text-white/50 tracking-wider uppercase">Customer Support</div>
                    </div>
                    <div class="border-t border-white/20 pt-6">
                        <div class="text-4xl lg:text-5xl font-black mb-2">100%</div>
                        <div class="text-[11px] text-white/50 tracking-wider uppercase">Quality Guarantee</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection