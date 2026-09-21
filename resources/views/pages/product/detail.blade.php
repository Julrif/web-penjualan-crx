@extends('layouts.app')
@section("content")

<div class="min-h-screen bg-white">
    <x-navbar />

    <!-- ============================================ -->
    <!-- BREADCRUMB -->
    <!-- ============================================ -->
    <div class="pt-24 lg:pt-32 border-b border-gray-200">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="flex items-center gap-2 py-4 text-[11px] tracking-wider uppercase text-gray-500">
                <a href="/" class="hover:text-black transition-colors">Home</a>
                <span>/</span>
                <a href="{{ route('products.index') }}" class="hover:text-black transition-colors">Shop</a>
                <span>/</span>
                <span class="text-black">{{ $product->name }}</span>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MAIN CONTENT -->
    <!-- ============================================ -->
    <div class="max-w-[1600px] mx-auto px-6 lg:px-12 py-12 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">

            <!-- ============================================ -->
            <!-- LEFT: PRODUCT IMAGES -->
            <!-- ============================================ -->
            <div class="space-y-4 lg:sticky lg:top-32 lg:self-start">
                @if (session()->has('message'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-[12px] tracking-wider uppercase">
                        {{ session('message') }}
                    </div>
                @endif
                
                @if($product->images->count() > 0)
                    <!-- Main Image -->
                    <div class="relative aspect-square bg-gray-50 overflow-hidden">
                        <img id="mainImage"
                             src="{{ asset('storage/' . $product->images->first()->path) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-transform duration-700">
                        
                        <!-- Image Counter -->
                        <div class="absolute bottom-4 right-4 bg-white text-black text-[11px] px-3 py-1 tracking-wider uppercase border border-black">
                            <span id="imageCounter">1 / {{ $product->images->count() }}</span>
                        </div>

                        <!-- Sold Out Badge -->
                        @php
                            $totalStock = $product->variants->sum('stock');
                        @endphp
                        @if($totalStock <= 0)
                            <div class="absolute top-4 left-4 bg-black text-white text-[11px] font-bold px-4 py-1.5 tracking-wider uppercase">
                                Sold Out
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnail Images -->
                    @if($product->images->count() > 1)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->images as $index => $image)
                                <div class="cursor-pointer aspect-square bg-gray-50 overflow-hidden border-2 {{ $index === 0 ? 'border-black' : 'border-transparent hover:border-gray-300' }} transition-all"
                                     onclick="changeMainImage('{{ asset('storage/' . $image->path) }}', this, {{ $index + 1 }}, {{ $product->images->count() }})">
                                    <img src="{{ asset('storage/' . $image->path) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="aspect-square bg-gray-50 overflow-hidden">
                        <img src="{{ asset('images/default-product.png') }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    </div>
                @endif
            </div>

            <!-- ============================================ -->
            <!-- RIGHT: PRODUCT INFO -->
            <!-- ============================================ -->
            <div class="space-y-8">
                
                <!-- Title -->
                <div class="space-y-3">
                    <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500">
                        {{ $product->category->name ?? 'Collection' }}
                    </p>
                    <h1 class="text-3xl lg:text-5xl font-black text-black tracking-tight uppercase leading-none">
                        {{ $product->name }}
                    </h1>
                </div>

                <!-- Price -->
                <div class="border-t border-gray-200 pt-6">
                    @if($product->discount)
                        <div class="flex items-center gap-4">
                            <span class="text-2xl lg:text-3xl font-bold text-black">
                                IDR {{ number_format($product->price * (1 - $product->discount/100), 0, ',', '.') }},00
                            </span>
                            <span class="text-lg text-gray-400 line-through">
                                IDR {{ number_format($product->price, 0, ',', '.') }},00
                            </span>
                            <span class="bg-black text-white text-[10px] font-bold px-3 py-1 tracking-wider uppercase">
                                -{{ $product->discount }}%
                            </span>
                        </div>
                    @else
                        <span class="text-2xl lg:text-3xl font-bold text-black">
                            IDR {{ number_format($product->price, 0, ',', '.') }},00
                        </span>
                    @endif
                    
                    <p class="text-[11px] text-gray-500 tracking-wider uppercase mt-2">
                        Free Shipping Over Rp 500.000
                    </p>
                </div>

                <!-- Description -->
                <div class="border-t border-gray-200 pt-6">
                    <p class="text-[11px] tracking-[0.2em] uppercase text-gray-500 mb-3">
                        Description
                    </p>
                    <p class="text-[14px] text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $product->description }}
                    </p>
                </div>

                <!-- Size Selector -->
                <div class="border-t border-gray-200 pt-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <p class="text-[11px] tracking-[0.2em] uppercase text-gray-500">
                            Select Size
                        </p>
                        <p class="text-[11px] tracking-wider uppercase text-gray-500">
                            Stock: <span id="stock-display" class="text-black font-bold">0</span>
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap gap-2" id="size-selector">
                        @foreach($product->variants as $variant)
                            <button class="size-btn px-5 py-3 border {{ $loop->first ? 'border-black bg-black text-white' : 'border-gray-300 text-black hover:border-black' }} 
                                {{ $variant->stock <= 0 ? 'opacity-30 cursor-not-allowed line-through' : '' }}" 
                                data-size="{{ $variant->size }}"
                                data-stock="{{ $variant->stock }}"
                                data-variant-id="{{ $variant->id }}"
                                {{ $variant->stock <= 0 ? 'disabled' : '' }}>
                                {{ $variant->size }}
                            </button>
                        @endforeach
                    </div>
                    
                    <p class="text-[11px] text-gray-500 tracking-wider uppercase">
                        Selected: <span id="selected-size-display" class="text-black font-bold">{{ $product->variants->first()->size ?? '-' }}</span>
                        <span id="selected-stock-display" class="ml-2">(Stock: {{ $product->variants->first()->stock ?? 0 }})</span>
                    </p>
                </div>

                <!-- ============================================ -->
                <!-- QUANTITY SELECTOR - TAMBAHKAN INI -->
                <!-- ============================================ -->
                <div class="border-t border-gray-200 pt-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <p class="text-[11px] tracking-[0.2em] uppercase text-gray-500">
                            Quantity
                        </p>
                        <p class="text-[11px] tracking-wider uppercase text-gray-500">
                            Max: <span id="max-quantity-display" class="text-black font-bold">0</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Minus Button -->
                        <button type="button" 
                                onclick="decreaseQuantity()"
                                class="w-12 h-12 border border-gray-300 text-black hover:border-black transition-colors flex items-center justify-center">
                            <i class="bi bi-dash text-[16px]"></i>
                        </button>

                        <!-- Quantity Input -->
                        <input type="number" 
                            id="quantity-input" 
                            name="quantity" 
                            value="1" 
                            min="1" 
                            readonly
                            class="w-20 h-12 border border-gray-300 text-center text-[14px] font-bold text-black focus:outline-none">

                        <!-- Plus Button -->
                        <button type="button" 
                                onclick="increaseQuantity()"
                                class="w-12 h-12 border border-gray-300 text-black hover:border-black transition-colors flex items-center justify-center">
                            <i class="bi bi-plus text-[16px]"></i>
                        </button>

                        <!-- Max Info -->
                        <span id="quantity-info" class="text-[11px] tracking-wider uppercase text-gray-500 ml-4">
                            Max 0 pcs
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t border-gray-200 pt-6 space-y-3">
                    @php
                        $firstVariant = $product->variants->first();
                        $defaultSize = $firstVariant ? $firstVariant->size : 'S';
                    @endphp
                    
                    <!-- Add to Cart -->
                    <form action="{{ route('user.keranjang.create') }}" method="POST" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="size" id="selected-size-input" value="{{ $defaultSize }}">
                        <input type="hidden" name="quantity" id="selected-quantity-input" value="1">
                        <button type="submit" id="add-to-cart-btn" 
                                class="w-full bg-black text-white py-4 text-[12px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                            <span>Add to Cart</span>
                        </button>
                    </form>

                    <!-- WhatsApp Order (Secondary) -->
                    <a id="whatsapp-link"
                       href="https://wa.me/6283811185668?text={{ urlencode('Halo, saya ingin pesan '.$product->name . ' (Size: ' . $defaultSize . ')') }}"
                       target="_blank"
                       class="block w-full text-center border border-black text-black py-4 text-[12px] font-medium tracking-[0.2em] uppercase hover:bg-black hover:text-white transition-all duration-300">
                        Order via WhatsApp
                    </a>

                    <!-- Quick Actions -->
                    <div class="flex justify-center gap-8 pt-4 text-[11px] tracking-wider uppercase text-gray-500">
                        
                        @auth
                            @php
                                $isWishlisted = Auth::user()->hasWishlisted($product->id);
                            @endphp
                            <button onclick="toggleWishlist({{ $product->id }})" 
                                    id="wishlist-btn"
                                    class="flex items-center gap-2 hover:text-black transition-colors">
                                <i class="bi {{ $isWishlisted ? 'bi-heart-fill text-red-500' : 'bi-heart' }} text-[14px]" id="wishlist-icon"></i>
                                <span id="wishlist-text">{{ $isWishlisted ? 'Wishlisted' : 'Wishlist' }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" 
                            class="flex items-center gap-2 hover:text-black transition-colors">
                                <i class="bi bi-heart text-[14px]"></i>
                                <span>Wishlist</span>
                            </a>
                        @endauth

                        <button class="flex items-center gap-2 hover:text-black transition-colors">
                            <i class="bi bi-share text-[14px]"></i>
                            <span>Share</span>
                        </button>
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="grid grid-cols-2 gap-4 text-[11px] tracking-wider uppercase">
                        <div class="border-l-2 border-black pl-4">
                            <p class="font-bold text-black mb-1">Quality Guarantee</p>
                            <p class="text-gray-500">30-Day Return Policy</p>
                        </div>
                        <div class="border-l-2 border-black pl-4">
                            <p class="font-bold text-black mb-1">Fast Delivery</p>
                            <p class="text-gray-500">2-3 Business Days</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // ELEMENTS
    // ============================================
    const sizeBtns = document.querySelectorAll('.size-btn');
    const selectedSizeDisplay = document.getElementById('selected-size-display');
    const selectedSizeInput = document.getElementById('selected-size-input');
    const selectedQuantityInput = document.getElementById('selected-quantity-input');
    const selectedStockDisplay = document.getElementById('selected-stock-display');
    const stockDisplay = document.getElementById('stock-display');
    const maxQuantityDisplay = document.getElementById('max-quantity-display');
    const quantityInput = document.getElementById('quantity-input');
    const quantityInfo = document.getElementById('quantity-info');
    const whatsappLink = document.getElementById('whatsapp-link');
    const productName = '{{ $product->name }}';
    const phoneNumber = '6283811185668';

    let currentStock = 0;

    // ============================================
    // SIZE SELECTION
    // ============================================
    function selectSize(size, button) {
        const stock = parseInt(button.dataset.stock) || 0;
        currentStock = stock;
        
        // Reset all buttons
        sizeBtns.forEach(btn => {
            btn.classList.remove('border-black', 'bg-black', 'text-white');
            btn.classList.add('border-gray-300', 'text-black');
        });

        // Active button
        button.classList.remove('border-gray-300', 'text-black');
        button.classList.add('border-black', 'bg-black', 'text-white');

        // Update displays
        if (selectedSizeDisplay) selectedSizeDisplay.textContent = size;
        if (selectedSizeInput) selectedSizeInput.value = size;
        
        if (selectedStockDisplay) {
            selectedStockDisplay.textContent = `(Stock: ${stock})`;
        }
        
        if (stockDisplay) {
            stockDisplay.textContent = stock;
        }

        // Update max quantity
        if (maxQuantityDisplay) {
            maxQuantityDisplay.textContent = stock;
        }

        if (quantityInfo) {
            quantityInfo.textContent = `Max ${stock} pcs`;
        }

        // Reset quantity ke 1
        if (quantityInput) {
            quantityInput.value = 1;
            if (selectedQuantityInput) selectedQuantityInput.value = 1;
        }

        // Update WhatsApp link
        const message = `Halo, saya ingin pesan ${productName} (Size: ${size})`;
        if (whatsappLink) {
            whatsappLink.href = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
        }

        // Update add to cart button
        updateAddToCartButton(stock);
    }

    // ============================================
    // QUANTITY CONTROL
    // ============================================
    window.increaseQuantity = function() {
        let current = parseInt(quantityInput.value) || 1;
        
        if (current < currentStock) {
            current++;
            quantityInput.value = current;
            if (selectedQuantityInput) selectedQuantityInput.value = current;
        } else {
            // Sudah max, kasih feedback
            quantityInput.classList.add('border-red-500');
            setTimeout(() => {
                quantityInput.classList.remove('border-red-500');
            }, 500);
        }
    };

    window.decreaseQuantity = function() {
        let current = parseInt(quantityInput.value) || 1;
        
        if (current > 1) {
            current--;
            quantityInput.value = current;
            if (selectedQuantityInput) selectedQuantityInput.value = current;
        }
    };

    // ============================================
    // UPDATE ADD TO CART BUTTON
    // ============================================
    function updateAddToCartButton(stock) {
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        if (!addToCartBtn) return;

        if (stock <= 0) {
            addToCartBtn.disabled = true;
            addToCartBtn.classList.add('opacity-30', 'cursor-not-allowed');
            addToCartBtn.querySelector('span').textContent = 'Out of Stock';
        } else {
            addToCartBtn.disabled = false;
            addToCartBtn.classList.remove('opacity-30', 'cursor-not-allowed');
            addToCartBtn.querySelector('span').textContent = 'Add to Cart';
        }
    }

    // ============================================
    // SIZE BUTTON EVENT LISTENER
    // ============================================
    sizeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.disabled) return;
            selectSize(this.dataset.size, this);
        });
    });

    // ============================================
    // SET INITIAL SELECTED SIZE
    // ============================================
    const firstBtn = document.querySelector('.size-btn:not([disabled])');
    if (firstBtn) {
        selectSize(firstBtn.dataset.size, firstBtn);
    } else {
        // Semua size sold out
        updateAddToCartButton(0);
    }
});

// ============================================
// CHANGE MAIN IMAGE
// ============================================
function changeMainImage(src, element, index, total) {
    document.getElementById('mainImage').src = src;
    document.getElementById('imageCounter').textContent = index + ' / ' + total;
    
    document.querySelectorAll('.grid.grid-cols-4 > div').forEach(el => {
        el.classList.remove('border-black');
        el.classList.add('border-transparent');
    });
    
    element.classList.remove('border-transparent');
    element.classList.add('border-black');
}

// ============================================
// TOGGLE WISHLIST
// ============================================
function toggleWishlist(productId) {
    fetch('{{ route("user.wishlist.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const icon = document.getElementById('wishlist-icon');
            const text = document.getElementById('wishlist-text');
            
            if (data.action === 'added') {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill', 'text-red-500');
                text.textContent = 'Wishlisted';
            } else {
                icon.classList.remove('bi-heart-fill', 'text-red-500');
                icon.classList.add('bi-heart');
                text.textContent = 'Wishlist';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal update wishlist');
    });
}
</script>
@endpush

@endsection