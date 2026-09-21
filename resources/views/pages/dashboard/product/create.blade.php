<!-- ============================================ -->
<!-- CREATE PRODUCT FORM - SNSBWORLD STYLE -->
<!-- ============================================ -->
<div class="p-8 lg:p-12 max-h-[70vh] overflow-y-auto">

    <form action="{{ route('admin.products.store') }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-10">
        @csrf

        <!-- ============================================ -->
        <!-- SECTION 1: BASIC INFO -->
        <!-- ============================================ -->
        <div>
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center">1</span>
                <h2 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                    Basic Information
                </h2>
            </div>

            <div class="space-y-6 max-w-2xl">
                
                <!-- Product Name -->
                <div>
                    <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                        Product Name *
                    </label>
                    <input id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           placeholder="e.g. Track Jacket - Spectra"
                           required
                           class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors">
                    @error('name')
                        <p class="text-red-500 text-[11px] tracking-wider uppercase mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                        Price (IDR) *
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-[12px] tracking-wider uppercase">Rp</span>
                        <input id="price" 
                               name="price" 
                               value="{{ old('price') }}"
                               type="number" 
                               step="0.01"
                               min="0"
                               placeholder="0"
                               required
                               class="w-full border border-gray-300 pl-14 pr-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors">
                    </div>
                    @error('price')
                        <p class="text-red-500 text-[11px] tracking-wider uppercase mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              placeholder="Describe the product..."
                              class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors resize-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-[11px] tracking-wider uppercase mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECTION 2: VARIANTS -->
        <!-- ============================================ -->
        <div>
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center">2</span>
                    <h2 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                        Product Variants
                    </h2>
                </div>
                <button type="button" 
                        onclick="addVariant()"
                        class="text-[11px] tracking-[0.2em] uppercase text-black border-b border-black pb-0.5 hover:opacity-60 transition-opacity">
                    + Add Size
                </button>
            </div>

            <p class="text-[11px] text-gray-500 tracking-wider uppercase mb-4">
                Add multiple sizes with stock quantities
            </p>
            
            <div id="variants-container" class="space-y-3">
                <!-- Variant 1 -->
                <div class="variant-item grid grid-cols-12 gap-3 p-4 border border-gray-200">
                    <div class="col-span-4">
                        <label class="block text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-1">Size</label>
                        <input type="text" 
                               name="variants[0][size]" 
                               class="w-full border border-gray-300 px-3 py-2 text-[12px] text-black focus:outline-none focus:border-black"
                               placeholder="S, M, L"
                               required>
                    </div>
                    <div class="col-span-4">
                        <label class="block text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-1">Stock</label>
                        <input type="number" 
                               name="variants[0][stock]" 
                               class="w-full border border-gray-300 px-3 py-2 text-[12px] text-black focus:outline-none focus:border-black"
                               placeholder="0"
                               min="0"
                               required>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-1">Price (Opt)</label>
                        <input type="number" 
                               name="variants[0][price]" 
                               class="w-full border border-gray-300 px-3 py-2 text-[12px] text-black focus:outline-none focus:border-black"
                               placeholder="0"
                               step="0.01"
                               min="0">
                    </div>
                    <div class="col-span-1 flex items-end justify-end">
                        <button type="button" 
                                onclick="removeVariant(this)"
                                class="text-gray-400 hover:text-red-500 p-2 transition-colors">
                            <i class="bi bi-x text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div id="variant-error" class="text-red-500 text-[11px] tracking-wider uppercase mt-3 hidden">
                Please add at least one variant
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECTION 3: IMAGES -->
        <!-- ============================================ -->
        <div>
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center">3</span>
                <h2 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                    Product Images *
                </h2>
            </div>

            <p class="text-[11px] text-gray-500 tracking-wider uppercase mb-4">
                First image will be the primary/cover image
            </p>
            
            <!-- Upload Area -->
            <div class="border border-dashed border-gray-300 p-10 text-center hover:border-black transition-colors">
                <div class="max-w-md mx-auto">
                    <i class="bi bi-cloud-arrow-up text-3xl text-gray-400 mb-4 block"></i>
                    <p class="text-[12px] tracking-wider uppercase text-black mb-2">
                        Upload Product Images
                    </p>
                    <p class="text-[11px] text-gray-500 tracking-wider uppercase mb-6">
                        JPG, PNG, WEBP — Max 5MB each
                    </p>
                    
                    <div class="relative inline-block">
                        <input id="images" 
                               name="images[]" 
                               type="file" 
                               accept="image/*"
                               multiple
                               required
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                               onchange="previewImages(event)">
                        <label for="images" 
                               class="inline-block bg-black text-white px-8 py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all cursor-pointer">
                            Choose Files
                        </label>
                    </div>
                    
                    <p id="fileCount" class="text-[11px] text-gray-500 tracking-wider uppercase mt-4 hidden">
                        <span id="fileCountText">0</span> files selected
                    </p>
                </div>
            </div>
            
            <!-- Image Previews -->
            <div id="imagePreviews" class="hidden mt-6">
                <p class="text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-3">
                    Previews (Click to set primary)
                </p>
                <div id="previewContainer" class="grid grid-cols-4 gap-3">
                </div>
            </div>
            
            @error('images')
                <p class="text-red-500 text-[11px] tracking-wider uppercase mt-2">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="text-red-500 text-[11px] tracking-wider uppercase mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- ============================================ -->
        <!-- ACTIONS -->
        <!-- ============================================ -->
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
            <button type="reset" 
                    class="border border-gray-300 text-black px-8 py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:border-black transition-all">
                Reset
            </button>
            <button type="submit" 
                    class="bg-black text-white px-10 py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all">
                Save Product
            </button>
        </div>
    </form>
</div>

<script>
// ============================================
// VARIANTS
// ============================================
let variantIndex = 1;

function addVariant() {
    const container = document.getElementById('variants-container');
    const newVariant = document.createElement('div');
    newVariant.className = 'variant-item grid grid-cols-12 gap-3 p-4 border border-gray-200';
    newVariant.innerHTML = `
        <div class="col-span-4">
            <label class="block text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-1">Size</label>
            <input type="text" 
                   name="variants[${variantIndex}][size]" 
                   class="w-full border border-gray-300 px-3 py-2 text-[12px] text-black focus:outline-none focus:border-black"
                   placeholder="S, M, L"
                   required>
        </div>
        <div class="col-span-4">
            <label class="block text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-1">Stock</label>
            <input type="number" 
                   name="variants[${variantIndex}][stock]" 
                   class="w-full border border-gray-300 px-3 py-2 text-[12px] text-black focus:outline-none focus:border-black"
                   placeholder="0"
                   min="0"
                   required>
        </div>
        <div class="col-span-3">
            <label class="block text-[10px] tracking-[0.15em] uppercase text-gray-500 mb-1">Price (Opt)</label>
            <input type="number" 
                   name="variants[${variantIndex}][price]" 
                   class="w-full border border-gray-300 px-3 py-2 text-[12px] text-black focus:outline-none focus:border-black"
                   placeholder="0"
                   step="0.01"
                   min="0">
        </div>
        <div class="col-span-1 flex items-end justify-end">
            <button type="button" 
                    onclick="removeVariant(this)"
                    class="text-gray-400 hover:text-red-500 p-2 transition-colors">
                <i class="bi bi-x text-lg"></i>
            </button>
        </div>
    `;
    container.appendChild(newVariant);
    variantIndex++;
}

function removeVariant(button) {
    const container = document.getElementById('variants-container');
    if (container.children.length <= 1) {
        document.getElementById('variant-error').classList.remove('hidden');
        return;
    }
    button.closest('.variant-item').remove();
    document.getElementById('variant-error').classList.add('hidden');
}

// ============================================
// IMAGE PREVIEW
// ============================================
function previewImages(event) {
    const input = event.target;
    const previewContainer = document.getElementById('previewContainer');
    const fileCount = document.getElementById('fileCount');
    const fileCountText = document.getElementById('fileCountText');
    const previewsDiv = document.getElementById('imagePreviews');
    
    if (previewContainer) previewContainer.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        if (fileCountText) fileCountText.textContent = input.files.length;
        if (fileCount) fileCount.classList.remove('hidden');
        if (previewsDiv) previewsDiv.classList.remove('hidden');
        
        Array.from(input.files).forEach(function(file, index) {
            const reader = new FileReader();
            const div = document.createElement('div');
            div.className = 'relative cursor-pointer group';
            
            reader.onload = function(e) {
                div.innerHTML = `
                    <div class="aspect-square bg-gray-50 border-2 ${index === 0 ? 'border-black' : 'border-gray-200'} overflow-hidden">
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute top-2 left-2 bg-black text-white text-[10px] tracking-wider uppercase px-2 py-0.5 ${index === 0 ? '' : 'hidden'} primary-badge">
                        Primary
                    </div>
                `;
                previewContainer.appendChild(div);
                
                div.addEventListener('click', function() {
                    previewContainer.querySelectorAll('.primary-badge').forEach(b => b.classList.add('hidden'));
                    previewContainer.querySelectorAll('.border-2').forEach(b => {
                        b.classList.remove('border-black');
                        b.classList.add('border-gray-200');
                    });
                    
                    div.querySelector('.primary-badge').classList.remove('hidden');
                    div.querySelector('.border-2').classList.remove('border-gray-200');
                    div.querySelector('.border-2').classList.add('border-black');
                });
            };
            
            reader.readAsDataURL(file);
        });
    } else {
        if (fileCount) fileCount.classList.add('hidden');
        if (previewsDiv) previewsDiv.classList.add('hidden');
    }
}

// ============================================
// FORM VALIDATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const container = document.getElementById('variants-container');
            if (container.children.length === 0) {
                e.preventDefault();
                document.getElementById('variant-error').classList.remove('hidden');
                return false;
            }
            
            const imageInput = document.getElementById('images');
            if (imageInput.files.length === 0) {
                e.preventDefault();
                alert('Please upload at least 1 product image!');
                return false;
            }
        });
    }
    
    const resetButton = document.querySelector('button[type="reset"]');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            setTimeout(() => {
                document.getElementById('fileCount').classList.add('hidden');
                document.getElementById('imagePreviews').classList.add('hidden');
                document.getElementById('previewContainer').innerHTML = '';
            }, 10);
        });
    }
});
</script>