@foreach($products as $item)
<a href="{{ route('product.detail', $item->id) }}" class="group block">
    
    <!-- Product Image -->
    <div class="relative aspect-square bg-gray-50 overflow-hidden mb-3">
        @php
            $imagePath = null;
            if($item->primaryImage) {
                $imagePath = asset('storage/' . $item->primaryImage->path);
            } elseif($item->images && $item->images->first()) {
                $imagePath = asset('storage/' . $item->images->first()->path);
            } else {
                $imagePath = asset('images/default-product.png');
            }
            
            // Cek stok
            $totalStock = $item->variants->sum('stock');
            $isSoldOut = $totalStock <= 0;
        @endphp
        
        <img src="{{ $imagePath }}"
             alt="{{ $item->name }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        
        <!-- Sold Out Badge -->
        @if($isSoldOut)
            <div class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-bold px-3 py-1 tracking-wider uppercase">
                Sold Out
            </div>
        @endif
    </div>
    
    <!-- Product Info -->
    <div class="space-y-1">
        <h3 class="text-[12px] font-medium text-black tracking-wider uppercase line-clamp-1 group-hover:opacity-60 transition-opacity">
            {{ $item->name }}
        </h3>
        <p class="text-[12px] text-gray-500 tracking-wider">
            IDR {{ number_format($item->price, 0, ',', '.') }},00
        </p>
    </div>
    
</a>
@endforeach