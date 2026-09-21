@extends("layouts.dashboard")
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
                        Management
                    </p>
                    <h1 class="text-3xl lg:text-4xl font-black text-black tracking-tight uppercase leading-none">
                        Products
                    </h1>
                </div>
                
                <button type="button"
                        onclick="openCreateModal()"
                        class="bg-black text-white px-6 py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                    + Add Product
                </button>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SEARCH & STATS -->
        <!-- ============================================ -->
        <div class="px-8 lg:px-12 py-8 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                
                <!-- Search -->
                <div class="flex-1 max-w-md search-input-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" 
                        placeholder="Search products..." 
                        class="border border-gray-300 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors">
                </div>

                <!-- Stats -->
                <div class="flex items-center gap-8 text-[11px] tracking-[0.15em] uppercase">
                    <div>
                        <span class="text-gray-500">Total:</span>
                        <span class="text-black font-bold ml-2">{{ $products->count() }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Stock:</span>
                        <span class="text-black font-bold ml-2">{{ $products->sum(function($p) { return $p->variants->sum('stock'); }) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Wishlist:</span>
                        <span class="text-black font-bold ml-2">❤️ {{ $stats['total_wishlist'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- MESSAGES -->
        <!-- ============================================ -->
        @if (session()->has('success'))
            <div class="mx-8 lg:mx-12 mt-6 border-l-2 border-black bg-gray-50 px-6 py-4 flex items-center justify-between">
                <p class="text-[12px] tracking-wider uppercase text-black">
                    {{ session('success') }}
                </p>
                <button onclick="this.parentElement.remove()" class="text-black hover:opacity-60">
                    <i class="bi bi-x-lg text-[14px]"></i>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mx-8 lg:mx-12 mt-6 border-l-2 border-red-500 bg-red-50 px-6 py-4 flex items-center justify-between">
                <p class="text-[12px] tracking-wider uppercase text-red-600">
                    {{ session('error') }}
                </p>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:opacity-60">
                    <i class="bi bi-x-lg text-[14px]"></i>
                </button>
            </div>
        @endif

        <!-- ============================================ -->
        <!-- TABLE -->
        <!-- ============================================ -->
        <div class="px-8 lg:px-12 py-8">
            <div class="border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        
                        <!-- Table Header -->
                        <thead class="bg-white border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Product</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Price</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Variants</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Stock</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Wishlist</th>
                                <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Updated</th>
                                <th class="px-6 py-4 text-right text-[10px] font-bold tracking-[0.2em] uppercase text-gray-500">Actions</th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody class="divide-y divide-gray-200">
                            @forelse($products as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                
                                <!-- Product -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        @if($item->primaryImage)
                                            <img src="{{ asset('storage/' . $item->primaryImage->path) }}" 
                                                 class="w-12 h-12 object-cover bg-gray-50 border border-gray-200">
                                        @else
                                            <div class="w-12 h-12 bg-gray-50 flex items-center justify-center border border-gray-200">
                                                <i class="bi bi-image text-gray-300 text-[14px]"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-[12px] font-medium text-black tracking-wider uppercase">
                                                {{ $item->name }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Price -->
                                <td class="px-6 py-4">
                                    <span class="text-[12px] font-medium text-black">
                                        IDR {{ number_format($item->price, 0, ',', '.') }}
                                    </span>
                                </td>

                                <!-- Variants -->
                                <td class="px-6 py-4">
                                    @if($item->variants && $item->variants->count() > 0)
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($item->variants as $variant)
                                                <span class="inline-flex items-center gap-1 text-[10px] tracking-wider uppercase border border-gray-300 px-2 py-0.5 text-black">
                                                    {{ $variant->size }}
                                                    <span class="text-gray-400">({{ $variant->stock }})</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-[11px] text-gray-400 tracking-wider uppercase">—</span>
                                    @endif
                                </td>

                                <!-- Total Stock -->
                                <td class="px-6 py-4">
                                    <span class="text-[12px] font-bold text-black">
                                        {{ $item->variants->sum('stock') }}
                                    </span>
                                </td>

                                <!-- Wishlist Count -->
                                <td class="px-6 py-4">
                                    @if($item->wishlists_count > 0)
                                        <span class="inline-flex items-center gap-1 text-[11px] tracking-wider text-black">
                                            <i class="bi bi-heart-fill text-red-500 text-[12px]"></i>
                                            <span class="font-bold">{{ $item->wishlists_count }}</span>
                                        </span>
                                    @else
                                        <span class="text-[11px] text-gray-300">—</span>
                                    @endif
                                </td>

                                <!-- Updated -->
                                <td class="px-6 py-4">
                                    <span class="text-[11px] text-gray-500 tracking-wider">
                                        {{ $item->updated_at->format('d M Y') }}
                                    </span>
                                    <br>
                                    <span class="text-[10px] text-gray-400 tracking-wider">
                                        {{ $item->updated_at->format('H:i') }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-3">
                                        
                                        <!-- View -->
                                        <a href="{{ route('product.detail', $item->id) }}" 
                                           target="_blank"
                                           class="text-gray-400 hover:text-black transition-colors"
                                           title="View">
                                            <i class="bi bi-eye text-[14px]"></i>
                                        </a>

                                        <!-- Edit -->
                                        <button type="button"
                                                class="text-gray-400 hover:text-black transition-colors"
                                                title="Edit"
                                                onclick='openEditModal({{ json_encode($item->load('images')) }})'>
                                            <i class="bi bi-pencil text-[14px]"></i>
                                        </button>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.products.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-gray-400 hover:text-red-500 transition-colors"
                                                    title="Delete">
                                                <i class="bi bi-trash text-[14px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center">
                                    <i class="bi bi-box text-4xl text-gray-300 block mb-4"></i>
                                    <p class="text-[12px] tracking-[0.2em] uppercase text-black mb-2">
                                        No products found
                                    </p>
                                    <p class="text-[11px] text-gray-500">
                                        Get started by adding your first product
                                    </p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- ============================================ -->
    <!-- MODAL CREATE -->
    <!-- ============================================ -->
    <div id="createModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeCreateModal()"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-4xl border border-black max-h-[90vh] overflow-hidden">
                
                <!-- Header -->
                <div class="flex items-center justify-between px-8 py-6 border-b border-gray-200">
                    <div>
                        <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500 mb-1">New</p>
                        <h2 class="text-xl font-black text-black tracking-tight uppercase">Add Product</h2>
                    </div>
                    <button onclick="closeCreateModal()" class="text-black text-xl">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="overflow-y-auto max-h-[calc(90vh-100px)]">
                    @include("pages.dashboard.product.create")
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- EDIT MODAL -->
    <!-- ============================================ -->
    @include("pages.dashboard.product.partials.editModal")

</div>

@push('scripts')
<script>
function confirmDelete(event) {
    if (!confirm('Delete this product? This action cannot be undone.')) {
        event.preventDefault();
        return false;
    }
    return true;
}

const searchInput = document.querySelector('input[placeholder="Search products..."]');
if (searchInput) {
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}
</script>
@endpush

@endsection