<div id="editModal" class="fixed inset-0 z-[9999] hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50" onclick="closeEditModal()"></div>

    <!-- Modal Container -->
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <!-- Modal Content -->
        <div class="relative bg-white w-full max-w-4xl border border-black overflow-hidden"
             onclick="event.stopPropagation()">
            
            <!-- Modal Header -->
            <div class="sticky top-0 z-10 bg-white px-8 py-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500 mb-1">Edit</p>
                        <h2 class="text-2xl font-black text-black tracking-tight uppercase">Edit Product</h2>
                    </div>
                    <button onclick="closeEditModal()" 
                            class="text-black text-xl hover:opacity-60 transition-opacity">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body with Form -->
            <div class="overflow-y-auto max-h-[calc(100vh-200px)]">
                <form id="editForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-10">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit_id" name="id">

                    <!-- ============================================ -->
                    <!-- SECTION 1: BASIC INFO -->
                    <!-- ============================================ -->
                    <div>
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                            <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center">1</span>
                            <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                                Basic Information
                            </h3>
                        </div>

                        <div class="space-y-6 max-w-2xl">
                            <!-- Product Name -->
                            <div>
                                <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                    Product Name *
                                </label>
                                <input id="edit_name" 
                                       name="name" 
                                       class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                                       required>
                            </div>

                            <!-- Price -->
                            <div>
                                <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                    Price (IDR) *
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-[12px] tracking-wider uppercase">Rp</span>
                                    <input id="edit_price" 
                                           name="price" 
                                           type="number" 
                                           step="0.01"
                                           min="0"
                                           class="w-full border border-gray-300 pl-14 pr-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                                           required>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                    Description
                                </label>
                                <textarea id="edit_description" 
                                          name="description" 
                                          rows="4"
                                          class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors resize-none"
                                          placeholder="Describe the product..."></textarea>
                                <div class="flex justify-end mt-2">
                                    <span id="charCount" class="text-[10px] tracking-wider uppercase text-gray-400">0/500</span>
                                </div>
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
                                <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                                    Product Variants
                                </h3>
                            </div>
                            <button type="button" 
                                    onclick="addEditVariant()"
                                    class="text-[11px] tracking-[0.2em] uppercase text-black border-b border-black pb-0.5 hover:opacity-60 transition-opacity">
                                + Add Size
                            </button>
                        </div>
                        
                        <p class="text-[11px] text-gray-500 tracking-wider uppercase mb-4">
                            Manage product sizes and stock quantities
                        </p>
                        
                        <div id="edit-variants-container" class="space-y-3">
                            <!-- Variants will be populated by JavaScript -->
                        </div>
                        
                        <div id="edit-variant-error" class="text-red-500 text-[11px] tracking-wider uppercase mt-3 hidden">
                            Please add at least one variant
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- SECTION 3: CURRENT IMAGES -->
                    <!-- ============================================ -->
                    <div id="currentImageContainer">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                            <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center">3</span>
                            <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                                Current Images
                            </h3>
                        </div>

                        <p class="text-[11px] text-gray-500 tracking-wider uppercase mb-4">
                            Click any image to set as primary (cover)
                        </p>
                        
                        <div class="grid grid-cols-4 gap-3" id="currentImagesPreview">
                            <!-- Images will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- SECTION 4: UPDATE IMAGES -->
                    <!-- ============================================ -->
                    <div>
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                            <span class="w-6 h-6 bg-white text-black border border-black text-[11px] font-bold flex items-center justify-center">4</span>
                            <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                                Update Images <span class="text-gray-400 font-normal">(Optional)</span>
                            </h3>
                        </div>
                        
                        <!-- Upload Area -->
                        <div class="border border-dashed border-gray-300 p-8 text-center hover:border-black transition-colors">
                            <i class="bi bi-cloud-arrow-up text-3xl text-gray-400 mb-4 block"></i>
                            <p class="text-[12px] tracking-wider uppercase text-black mb-2">
                                Upload New Images
                            </p>
                            <p class="text-[11px] text-gray-500 tracking-wider uppercase mb-6">
                                JPG, PNG, WEBP — Max 5MB each
                            </p>
                            
                            <div class="relative inline-block">
                                <input type="file" 
                                       name="images[]" 
                                       accept="image/*"
                                       multiple
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                       onchange="previewNewImages(event)">
                                <label class="inline-block bg-black text-white px-8 py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all cursor-pointer">
                                    Choose Files
                                </label>
                            </div>
                        </div>

                        <!-- New Images Preview -->
                        <div id="newImagePreviewContainer" class="hidden mt-6">
                            <p class="text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-3">
                                New Images (Click to set primary)
                            </p>
                            <div id="newImagesPreviewGrid" class="grid grid-cols-4 gap-3">
                            </div>
                            <button type="button" 
                                    onclick="removeNewImages()"
                                    class="text-[11px] tracking-wider uppercase text-red-500 underline mt-4 hover:opacity-60">
                                Remove All New Images
                            </button>
                            <input type="hidden" id="newPrimaryIndex" name="new_primary_index" value="0">
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- ACTIONS -->
                    <!-- ============================================ -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        <button type="button" 
                                onclick="closeEditModal()" 
                                class="border border-gray-300 text-black px-8 py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:border-black transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-black text-white px-10 py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>

            <!-- Loading Overlay -->
            <div id="editModalLoading" class="absolute inset-0 bg-white/80 hidden items-center justify-center">
                <div class="text-center">
                    <div class="w-10 h-10 border-2 border-black border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                    <p class="text-[11px] tracking-wider uppercase text-black">Updating...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #editModal:not(.hidden) .relative.bg-white {
        animation: modalSlideIn 0.3s ease-out forwards;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f5f5f5;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #000;
    }
</style>

<script>
// ============================================
// VARIABLES
// ============================================
let editVariantIndex = 0;

// ============================================
// VARIANT FUNCTIONS
// ============================================
function addEditVariant(size = '', stock = '', price = '', variantId = '') {
    const container = document.getElementById('edit-variants-container');
    if (!container) return;
    
    const newVariant = document.createElement('div');
    newVariant.className = 'variant-item grid grid-cols-1 md:grid-cols-4 gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200';
    
    let idInput = '';
    if (variantId) {
        idInput = `<input type="hidden" name="variants[${editVariantIndex}][id]" value="${variantId}">`;
    }
    
    newVariant.innerHTML = `
        ${idInput}
        <div>
            <label class="text-xs text-gray-600 mb-1 block">Size</label>
            <input type="text" 
                   name="variants[${editVariantIndex}][size]" 
                   class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm"
                   placeholder="e.g., S, M, L, 28, 30"
                   value="${size}"
                   required>
        </div>
        <div>
            <label class="text-xs text-gray-600 mb-1 block">Stock</label>
            <input type="number" 
                   name="variants[${editVariantIndex}][stock]" 
                   class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm"
                   placeholder="0"
                   min="0"
                   value="${stock}"
                   required>
        </div>
        <div>
            <label class="text-xs text-gray-600 mb-1 block">Price (Optional)</label>
            <input type="number" 
                   name="variants[${editVariantIndex}][price]" 
                   class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm"
                   placeholder="Leave empty for default price"
                   step="0.01"
                   min="0"
                   value="${price}">
        </div>
        <div class="flex items-end justify-end">
            <button type="button" 
                    onclick="removeEditVariant(this)"
                    class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition-colors">
                <i class="bi bi-trash3 text-lg"></i>
            </button>
        </div>
    `;
    container.appendChild(newVariant);
    editVariantIndex++;
}

function removeEditVariant(button) {
    const container = document.getElementById('edit-variants-container');
    if (!container) return;
    
    if (container.children.length <= 1) {
        const errorDiv = document.getElementById('edit-variant-error');
        if (errorDiv) {
            errorDiv.classList.remove('hidden');
            errorDiv.textContent = 'Minimal harus ada 1 varian!';
        }
        return;
    }
    button.closest('.variant-item').remove();
    const errorDiv = document.getElementById('edit-variant-error');
    if (errorDiv) {
        errorDiv.classList.add('hidden');
    }
}

// ============================================
// IMAGE FUNCTIONS
// ============================================
function previewNewImages(event) {
    const input = event.target;
    const previewContainer = document.getElementById('newImagePreviewContainer');
    const previewGrid = document.getElementById('newImagesPreviewGrid');
    const primaryIndexInput = document.getElementById('newPrimaryIndex');
    
    if (previewGrid) {
        previewGrid.innerHTML = '';
    }
    
    if (input.files && input.files.length > 0) {
        if (previewContainer) previewContainer.classList.remove('hidden');
        
        // Set primary index default ke 0
        let primaryIndex = 0;
        if (primaryIndexInput) {
            primaryIndex = parseInt(primaryIndexInput.value) || 0;
        }
        
        Array.from(input.files).forEach(function(file, index) {
            const reader = new FileReader();
            const div = document.createElement('div');
            div.className = 'relative group cursor-pointer';
            div.dataset.index = index;
            
            reader.onload = function(e) {
                div.innerHTML = `
                    <div class="w-full h-32 bg-gray-100 rounded-xl overflow-hidden border-2 ${index === primaryIndex ? 'border-indigo-500' : 'border-gray-300'}">
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                        ${index + 1}
                    </div>
                    <div class="absolute bottom-2 left-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded-full ${index === primaryIndex ? '' : 'hidden'} primary-badge-new">
                        Primary
                    </div>
                    <div class="absolute inset-0 bg-black/0 hover:bg-black/30 transition-all flex items-center justify-center opacity-0 hover:opacity-100 rounded-xl">
                        <span class="text-white text-xs bg-black/60 px-3 py-1 rounded-lg">Set Primary</span>
                    </div>
                `;
                previewGrid.appendChild(div);
                
                // Add click event to set primary
                div.addEventListener('click', function() {
                    setNewPrimary(index, previewGrid);
                });
            };
            
            reader.readAsDataURL(file);
        });
    } else {
        if (previewContainer) previewContainer.classList.add('hidden');
    }
}

// Fungsi untuk set primary di new images
function setNewPrimary(index, grid) {
    // Update hidden input
    document.getElementById('newPrimaryIndex').value = index;
    
    // Update UI
    const items = grid.querySelectorAll('.relative');
    items.forEach(function(item, i) {
        const border = item.querySelector('.rounded-xl');
        const badge = item.querySelector('.primary-badge-new');
        
        if (i === index) {
            border.classList.remove('border-gray-300');
            border.classList.add('border-indigo-500');
            if (badge) badge.classList.remove('hidden');
        } else {
            border.classList.remove('border-indigo-500');
            border.classList.add('border-gray-300');
            if (badge) badge.classList.add('hidden');
        }
    });
}

function removeNewImages() {
    const fileInput = document.querySelector('input[name="images[]"]');
    const previewContainer = document.getElementById('newImagePreviewContainer');
    const previewGrid = document.getElementById('newImagesPreviewGrid');
    const primaryIndexInput = document.getElementById('newPrimaryIndex');
    
    if (fileInput) fileInput.value = '';
    if (previewGrid) previewGrid.innerHTML = '';
    if (previewContainer) previewContainer.classList.add('hidden');
    if (primaryIndexInput) primaryIndexInput.value = 0;
}

// ============================================
// SET PRIMARY IMAGE (AJAX)
// ============================================
function setPrimaryImage(imageId, productId) {
    fetch('/dashboard/product/set-primary', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            image_id: imageId,
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh modal to show updated primary
            const product = data.product;
            const currentImagesPreview = document.getElementById('currentImagesPreview');
            
            if (currentImagesPreview) {
                currentImagesPreview.innerHTML = '';
                
                if (product.images && product.images.length > 0) {
                    product.images.forEach(function(image, index) {
                        const div = document.createElement('div');
                        div.className = 'current-image-card relative group rounded-xl overflow-hidden border-2 ' + 
                            (image.is_primary ? 'border-indigo-500 shadow-lg shadow-indigo-200' : 'border-gray-300');
                        div.innerHTML = `
                            <img src="/storage/${image.path}" 
                                 alt="Product Image ${index + 1}"
                                 class="w-full h-24 object-cover">
                            ${image.is_primary ? '<span class="absolute top-1 left-1 bg-indigo-600 text-white text-xs px-2 py-0.5 rounded-full">Primary</span>' : ''}
                            <div class="overlay absolute inset-0 bg-black/40 flex items-center justify-center">
                                <span class="text-white text-xs bg-black/60 px-3 py-1 rounded-lg">${image.is_primary ? '✓ Primary' : 'Click to set primary'}</span>
                            </div>
                        `;
                        
                        // Click to set as primary
                        if (!image.is_primary) {
                            div.addEventListener('click', function() {
                                setPrimaryImage(image.id, product.id);
                            });
                        }
                        
                        currentImagesPreview.appendChild(div);
                    });
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengubah primary image!');
    });
}

// ============================================
// MAIN FUNCTIONS
// ============================================
function openEditModal(product) {
    console.log('openEditModal dipanggil untuk product:', product);
    
    const modal = document.getElementById('editModal');
    if (!modal) {
        console.error('Modal tidak ditemukan!');
        return;
    }
    
    const form = document.getElementById('editForm');
    if (!form) {
        console.error('Form tidak ditemukan!');
        return;
    }
    
    // Set form action
    form.action = `/dashboard/product/update/${product.id}`;
    
    // Isi field dasar
    const nameInput = document.getElementById('edit_name');
    const priceInput = document.getElementById('edit_price');
    const descInput = document.getElementById('edit_description');
    const idInput = document.getElementById('edit_id');
    
    if (nameInput) nameInput.value = product.name || '';
    if (priceInput) priceInput.value = product.price || '';
    if (descInput) descInput.value = product.description || '';
    if (idInput) idInput.value = product.id;
    
    // Update character count
    const charCount = document.getElementById('charCount');
    if (charCount) {
        charCount.textContent = `${product.description?.length || 0}/500`;
    }
    
    // Set current images preview with primary selection
    const currentContainer = document.getElementById('currentImageContainer');
    const currentImagesPreview = document.getElementById('currentImagesPreview');
    
    console.log('Product images:', product.images);
    
    if (currentImagesPreview) {
        currentImagesPreview.innerHTML = '';
        
        if (product.images && product.images.length > 0) {
            product.images.forEach(function(image, index) {
                const div = document.createElement('div');
                div.className = 'current-image-card relative group rounded-xl overflow-hidden border-2 ' + 
                    (image.is_primary ? 'border-indigo-500 shadow-lg shadow-indigo-200' : 'border-gray-300 cursor-pointer hover:border-indigo-400');
                div.innerHTML = `
                    <img src="/storage/${image.path}" 
                         alt="Product Image ${index + 1}"
                         class="w-full h-24 object-cover">
                    ${image.is_primary ? '<span class="absolute top-1 left-1 bg-indigo-600 text-white text-xs px-2 py-0.5 rounded-full">Primary</span>' : ''}
                    <div class="overlay absolute inset-0 bg-black/40 flex items-center justify-center">
                        <span class="text-white text-xs bg-black/60 px-3 py-1 rounded-lg">${image.is_primary ? '✓ Primary' : 'Click to set primary'}</span>
                    </div>
                `;
                
                // Click to set as primary (only if not already primary)
                if (!image.is_primary) {
                    div.addEventListener('click', function() {
                        setPrimaryImage(image.id, product.id);
                    });
                }
                
                currentImagesPreview.appendChild(div);
            });
            if (currentContainer) currentContainer.classList.remove('hidden');
        } else {
            currentImagesPreview.innerHTML = `
                <div class="col-span-full text-center py-8 text-gray-400">
                    <i class="bi bi-image text-4xl block mb-2"></i>
                    No images available
                </div>
            `;
            if (currentContainer) currentContainer.classList.remove('hidden');
        }
    }
    
    // Populate variants
    const container = document.getElementById('edit-variants-container');
    if (container) {
        container.innerHTML = '';
        editVariantIndex = 0;
        
        if (product.variants && product.variants.length > 0) {
            product.variants.forEach(variant => {
                addEditVariant(
                    variant.size || '', 
                    variant.stock || 0, 
                    variant.price || '', 
                    variant.id
                );
            });
        } else {
            addEditVariant();
        }
    }
    
    // Hide new images preview
    const newPreview = document.getElementById('newImagePreviewContainer');
    if (newPreview) newPreview.classList.add('hidden');
    
    // Clear file input
    const fileInput = document.querySelector('input[name="images[]"]');
    if (fileInput) fileInput.value = '';
    
    // Tampilkan modal
    modal.classList.remove('hidden');
    console.log('Modal berhasil dibuka!');

    // Reset new primary index
    const newPrimaryIndex = document.getElementById('newPrimaryIndex');
    if (newPrimaryIndex) newPrimaryIndex.value = 0;

    // Reset new images preview
    const newPreviewGrid = document.getElementById('newImagesPreviewGrid');
    if (newPreviewGrid) newPreviewGrid.innerHTML = '';
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    if (!modal) return;
    
    const content = modal.querySelector('.relative.bg-white');
    
    if (content) {
        content.style.animation = 'modalSlideIn 0.3s ease-out reverse forwards';
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.opacity = '0';
        if (content) {
            content.style.animation = '';
        }
        const loading = document.getElementById('editModalLoading');
        if (loading) loading.classList.add('hidden');
    }, 200);
}

// ============================================
// EVENT LISTENERS
// ============================================
// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('editModal');
    if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
        closeEditModal();
    }
});

// Character Counter
document.addEventListener('DOMContentLoaded', function() {
    const descriptionTextarea = document.getElementById('edit_description');
    const charCount = document.getElementById('charCount');
    
    if (descriptionTextarea && charCount) {
        descriptionTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length}/500`;
            
            if (length > 500) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        });
    }
    
    // Form validation for edit
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const container = document.getElementById('edit-variants-container');
            const errorDiv = document.getElementById('edit-variant-error');
            
            if (!container || container.children.length === 0) {
                e.preventDefault();
                if (errorDiv) {
                    errorDiv.classList.remove('hidden');
                    errorDiv.textContent = 'Please add at least one variant';
                }
                return false;
            }
            
            // Check if any size is empty
            const sizeInputs = container.querySelectorAll('input[name*="[size]"]');
            let hasEmpty = false;
            sizeInputs.forEach(input => {
                if (!input.value.trim()) {
                    hasEmpty = true;
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            });
            
            if (hasEmpty) {
                e.preventDefault();
                if (errorDiv) {
                    errorDiv.classList.remove('hidden');
                    errorDiv.textContent = 'Please fill in all size fields';
                }
                return false;
            }
            
            if (errorDiv) errorDiv.classList.add('hidden');
        });
    }
});
</script>