@extends('layouts.app')

@section('body-class', 'has-hero')

@section("content")

<div class="min-h-screen bg-white">
    <x-navbar />

    <!-- ============================================ -->
    <!-- HERO SECTION (Text Only) -->
    <!-- ============================================ -->
    <div class="relative min-h-[50vh] flex items-end overflow-hidden pt-20 bg-black">
        <div class="w-full max-w-[1600px] mx-auto px-6 lg:px-12 pb-16 lg:pb-24">
            <div class="max-w-4xl">
                <p class="text-[11px] font-medium text-white/70 tracking-[0.3em] uppercase mb-4">
                    About Us
                </p>
                <h1 class="text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-black text-white leading-[0.9] tracking-tight mb-6 uppercase">
                    Our Story
                </h1>
                <p class="text-base sm:text-lg text-white/90 max-w-xl leading-relaxed">
                    More than just a clothing store — we deliver style, quality, and trust.
                </p>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- CONTENT SECTION -->
    <!-- ============================================ -->
    <div class="max-w-[1600px] mx-auto px-6 lg:px-12 py-20 lg:py-32">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24">
            
            <!-- LEFT: DESCRIPTION -->
            <div>
                <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500 mb-4">
                    Who We Are
                </p>
                <h2 class="text-3xl lg:text-5xl font-black text-black tracking-tight uppercase leading-[0.95] mb-8">
                    More Than<br>
                    Just Fashion
                </h2>

                <div class="space-y-6 text-[14px] text-gray-700 leading-relaxed">
                    <p>
                        Our store was founded with one simple goal: to provide high-quality clothing with modern designs that anyone can wear.
                    </p>
                    <p>
                        Every product is selected with the best materials, careful production processes, and models that always follow the latest trends.
                    </p>
                    <p>
                        We believe that dressing well is a form of self-expression and confidence.
                    </p>
                </div>
            </div>

            <!-- RIGHT: WHY CHOOSE US -->
            <div>
                <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500 mb-4">
                    Why Choose Us
                </p>
                <h2 class="text-3xl lg:text-5xl font-black text-black tracking-tight uppercase leading-[0.95] mb-8">
                    Our Values
                </h2>

                <div class="space-y-5">
                    <div class="flex items-start gap-4 pb-5 border-b border-gray-200">
                        <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">01</span>
                        <div>
                            <p class="text-[13px] font-bold tracking-[0.1em] uppercase text-black mb-1">
                                Premium Materials
                            </p>
                            <p class="text-[12px] text-gray-500 leading-relaxed">
                                High-quality fabrics that are comfortable and durable
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 pb-5 border-b border-gray-200">
                        <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">02</span>
                        <div>
                            <p class="text-[13px] font-bold tracking-[0.1em] uppercase text-black mb-1">
                                Always Updated
                            </p>
                            <p class="text-[12px] text-gray-500 leading-relaxed">
                                Designs that always follow the latest fashion trends
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 pb-5 border-b border-gray-200">
                        <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">03</span>
                        <div>
                            <p class="text-[13px] font-bold tracking-[0.1em] uppercase text-black mb-1">
                                Honest Pricing
                            </p>
                            <p class="text-[12px] text-gray-500 leading-relaxed">
                                Fair and affordable prices for everyone
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 pb-5 border-b border-gray-200">
                        <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">04</span>
                        <div>
                            <p class="text-[13px] font-bold tracking-[0.1em] uppercase text-black mb-1">
                                Fast Delivery
                            </p>
                            <p class="text-[12px] text-gray-500 leading-relaxed">
                                Safe and quick shipping to your doorstep
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">05</span>
                        <div>
                            <p class="text-[13px] font-bold tracking-[0.1em] uppercase text-black mb-1">
                                Friendly Support
                            </p>
                            <p class="text-[12px] text-gray-500 leading-relaxed">
                                Responsive and helpful customer service
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- CTA SECTION -->
    <!-- ============================================ -->
    <div class="bg-black text-white py-20 lg:py-28">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12 text-center">
            <p class="text-[11px] tracking-[0.3em] uppercase text-white/50 mb-4">
                Ready to Shop?
            </p>
            <h2 class="text-3xl lg:text-5xl font-black tracking-tight uppercase mb-8 leading-none">
                Explore Our Collection
            </h2>
            <a href="{{ route('products.index') }}" 
               class="inline-block bg-white text-black px-10 py-4 text-[12px] font-medium tracking-[0.3em] uppercase hover:bg-black hover:text-white border border-white transition-all duration-300">
                Shop Now
            </a>
        </div>
    </div>

</div>

@endsection