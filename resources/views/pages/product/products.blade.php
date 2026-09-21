@extends('layouts.app')
@section("content")

<div class="min-h-screen bg-white">

    <x-navbar />

    <!-- ============================================ -->
    <!-- HEADER SECTION -->
    <!-- ============================================ -->
    <div class="pt-32 lg:pt-40 pb-12 border-b border-gray-200">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <div>
                    <p class="text-[11px] font-medium text-gray-500 tracking-[0.3em] uppercase mb-3">
                        Collection
                    </p>
                    <h1 class="text-4xl lg:text-6xl font-black text-black tracking-tight uppercase leading-none">
                        All Products
                    </h1>
                </div>
                <div class="text-[12px] text-gray-500 tracking-wider uppercase">
                    {{ $data->total() }} Items
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- FILTER BAR -->
    <!-- ============================================ -->
    <div class="border-b border-gray-200">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="flex items-center justify-between py-4">
                <div class="flex items-center gap-8 text-[12px] tracking-wider uppercase text-black">
                    <button class="flex items-center gap-2 hover:opacity-60 transition-opacity">
                        Availability
                        <i class="bi bi-chevron-down text-[10px]"></i>
                    </button>
                    <button class="flex items-center gap-2 hover:opacity-60 transition-opacity">
                        Price
                        <i class="bi bi-chevron-down text-[10px]"></i>
                    </button>
                </div>
                <div class="flex items-center gap-6 text-[12px] tracking-wider uppercase text-black">
                    <button class="flex items-center gap-2 hover:opacity-60 transition-opacity">
                        Sort
                        <i class="bi bi-chevron-down text-[10px]"></i>
                    </button>
                    <div class="hidden md:flex items-center gap-2">
                        <i class="bi bi-grid-3x3-gap-fill text-[14px]"></i>
                        <i class="bi bi-list text-[14px] opacity-40"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- PRODUCTS GRID -->
    <!-- ============================================ -->
    <div class="max-w-[1600px] mx-auto px-6 lg:px-12 py-12 lg:py-16">
        <div id="product-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-4 gap-y-12">
            @include('pages.product.partials.product-items', ['products' => $data])
        </div>

        <!-- ============================================ -->
        <!-- LOAD MORE / PAGINATION -->
        <!-- ============================================ -->
        @if($data->hasMorePages())
            <div class="mt-20 text-center" id="load-more-container">
                <button id="load-more-btn" 
                        data-next-page="{{ $data->nextPageUrl() }}"
                        data-current-page="{{ $data->currentPage() }}"
                        data-last-page="{{ $data->lastPage() }}"
                        class="inline-block border border-black px-12 py-4 text-[12px] font-medium tracking-[0.15em] uppercase text-black hover:bg-black hover:text-white transition-all duration-300">
                    <span id="load-more-text">Load More</span>
                    <div id="loading-spinner" class="hidden inline-block ml-2">
                        <div class="animate-spin rounded-full h-3 w-3 border-b border-current"></div>
                    </div>
                </button>
                
                <!-- Hidden data for JS -->
                <div class="hidden">
                    <span id="shown-count">{{ $data->count() }}</span>
                    <span id="total-count">{{ $data->total() }}</span>
                    <span id="current-page">{{ $data->currentPage() }}</span>
                    <span id="total-pages">{{ $data->lastPage() }}</span>
                    <div id="progress-bar"></div>
                    <div id="end-message"></div>
                </div>
            </div>
        @endif

        @if(!$data->hasMorePages() && $data->count() > 0)
            <div class="mt-16 text-center">
                <p class="text-[12px] text-gray-500 tracking-wider uppercase">
                    — All {{ $data->total() }} products displayed —
                </p>
            </div>
        @endif

        @if($data->count() == 0)
            <div class="text-center py-20">
                <p class="text-[14px] text-gray-500 tracking-wider uppercase mb-2">
                    No products found
                </p>
                <p class="text-[12px] text-gray-400">
                    Come back later for new arrivals
                </p>
            </div>
        @endif
    </div>

    <!-- ============================================ -->
    <!-- FEATURES BAR -->
    <!-- ============================================ -->
    <div class="border-t border-gray-200 bg-white">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-200">
                <div class="py-8 md:pr-8">
                    <p class="text-[12px] font-medium tracking-wider uppercase text-black mb-1">
                        Quality Guarantee
                    </p>
                    <p class="text-[11px] text-gray-500 tracking-wider uppercase">
                        30-Day Return Policy
                    </p>
                </div>
                <div class="py-8 md:px-8">
                    <p class="text-[12px] font-medium tracking-wider uppercase text-black mb-1">
                        Fast Delivery
                    </p>
                    <p class="text-[11px] text-gray-500 tracking-wider uppercase">
                        Free Shipping Over Rp 500.000
                    </p>
                </div>
                <div class="py-8 md:pl-8">
                    <p class="text-[12px] font-medium tracking-wider uppercase text-black mb-1">
                        24/7 Support
                    </p>
                    <p class="text-[11px] text-gray-500 tracking-wider uppercase">
                        Dedicated Customer Service
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Script --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreBtn = document.getElementById('load-more-btn');
            const productContainer = document.getElementById('product-container');
            const loadingSpinner = document.getElementById('loading-spinner');
            const loadMoreText = document.getElementById('load-more-text');
            const shownCount = document.getElementById('shown-count');
            const currentPage = document.getElementById('current-page');
            const loadMoreContainer = document.getElementById('load-more-container');
            const totalCount = document.getElementById('total-count');
            
            if (!loadMoreBtn) return;
            
            let isLoading = false;
            
            loadMoreBtn.addEventListener('click', async function() {
                if (isLoading) return;
                
                const nextPageUrl = this.dataset.nextPage;
                if (!nextPageUrl) return;
                
                // Show loading state
                isLoading = true;
                loadingSpinner.classList.remove('hidden');
                loadMoreText.textContent = 'Loading...';
                loadMoreBtn.disabled = true;
                loadMoreBtn.style.opacity = '0.6';
                
                try {
                    const response = await fetch(nextPageUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const html = await response.text();
                    
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newProducts = doc.getElementById('product-container').innerHTML;
                    const newLoadMoreBtn = doc.getElementById('load-more-btn');
                    
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = newProducts;
                    const productElements = tempDiv.children;
                    
                    Array.from(productElements).forEach((element) => {
                        productContainer.appendChild(element.cloneNode(true));
                    });
                    
                    if (newLoadMoreBtn) {
                        loadMoreBtn.dataset.nextPage = newLoadMoreBtn.dataset.nextPage;
                        loadMoreBtn.dataset.currentPage = newLoadMoreBtn.dataset.currentPage;
                        currentPage.textContent = parseInt(newLoadMoreBtn.dataset.currentPage) + 1;
                    } else {
                        loadMoreContainer.classList.add('hidden');
                        // Tampilkan pesan end
                        const endMsg = document.createElement('div');
                        endMsg.className = 'mt-16 text-center';
                        endMsg.innerHTML = '<p class="text-[12px] text-gray-500 tracking-wider uppercase">— All products displayed —</p>';
                        loadMoreContainer.parentNode.appendChild(endMsg);
                    }
                    
                    const currentShown = parseInt(shownCount.textContent);
                    const newProductsCount = productElements.length;
                    shownCount.textContent = currentShown + newProductsCount;
                    
                } catch (error) {
                    console.error('Error loading more products:', error);
                    loadMoreText.textContent = 'Error — Try Again';
                    setTimeout(() => {
                        loadMoreText.textContent = 'Load More';
                    }, 3000);
                } finally {
                    isLoading = false;
                    loadingSpinner.classList.add('hidden');
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.style.opacity = '1';
                    if (loadMoreText.textContent === 'Loading...') {
                        loadMoreText.textContent = 'Load More';
                    }
                }
            });
        });
    </script>
@endpush

@endsection