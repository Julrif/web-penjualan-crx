<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // View
    function index() {
        $products = Product::with(['primaryImage', 'variants'])
            ->withCount('wishlists') // ← TAMBAHKAN INI
            ->get();
        
        $stats = [
            "total" => $products->count(),
            "total_wishlist" => $products->sum('wishlists_count')
        ];
        
        return view("pages.dashboard.product.index", compact("products", "stats"));
    }
    
    function create() {
        return view("pages.dashboard.product.create");
    }
    
    function updateView($id) {
        $product = Product::with(['images', 'variants'])->findOrFail($id);
        return view("pages.dashboard.product.edit", compact("product"));
    }

    // Services
    function store(Request $req)
    {
        // Validasi
        $req->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:20480', // 20MB per file
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|string|max:50',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.price' => 'nullable|numeric|min:0',
        ]);

        // Cek duplicate size
        $sizes = array_column($req->variants, 'size');
        if (count($sizes) !== count(array_unique($sizes))) {
            return redirect()->back()
                ->with('error', 'Duplicate size found! Each size must be unique.')
                ->withInput();
        }

        // Simpan product
        $product = Product::create([
            'name'        => $req->name,
            'price'       => $req->price,
            'description' => $req->description,
        ]);

        // Upload SEMUA gambar
        foreach ($req->file('images') as $index => $file) {
            $fileName = $file->getClientOriginalName();
            $path = $file->store('products', 'public');
            
            File::create([
                'product_id' => $product->id,
                'path' => $path,
                'name' => $fileName,
                'is_primary' => $index === 0,
            ]);
        }

        // Simpan variants
        foreach ($req->variants as $variantData) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size' => strtoupper($variantData['size']),
                'stock' => $variantData['stock'],
                'price' => $variantData['price'] ?? $req->price,
                'sku' => $variantData['sku'] ?? null,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success','Product berhasil ditambahkan!');
    }

    function update(Request $req, $id)
    {
        $product = Product::with(['images', 'variants'])->findOrFail($id);

        $req->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.size' => 'required|string|max:50',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.price' => 'nullable|numeric|min:0',
        ]);

        $sizes = array_column($req->variants, 'size');
        if (count($sizes) !== count(array_unique($sizes))) {
            return redirect()->back()
                ->with('error', 'Duplicate size found! Each size must be unique.')
                ->withInput();
        }

        $product->update([
            'name'        => $req->name,
            'price'       => $req->price,
            'description' => $req->description,
        ]);

        // Jika upload gambar baru
        if ($req->hasFile('images')) {
            // Hapus relasi image_id di products dulu
            $product->update(['image_id' => null]);
            
            foreach ($product->images as $oldImage) {
                if (Storage::disk('public')->exists($oldImage->path)) {
                    Storage::disk('public')->delete($oldImage->path);
                }
                $oldImage->delete();
            }

            // Ambil index primary dari hidden input
            $primaryIndex = $req->new_primary_index ?? 0;

            foreach ($req->file('images') as $index => $file) {
                $fileName = $file->getClientOriginalName();
                $path = $file->store('products', 'public');
                
                File::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'name' => $fileName,
                    'is_primary' => $index == $primaryIndex, // <-- PAKAI INDEX DARI USER
                ]);
            }
        }

        $updatedVariantIds = [];

        foreach ($req->variants as $variantData) {
            $sizeExists = ProductVariant::where('product_id', $product->id)
                ->where('size', strtoupper($variantData['size']))
                ->when(isset($variantData['id']), function($query) use ($variantData) {
                    return $query->where('id', '!=', $variantData['id']);
                })
                ->exists();

            if ($sizeExists) {
                return redirect()->back()
                    ->with('error', 'Size "' . strtoupper($variantData['size']) . '" sudah ada untuk product ini!')
                    ->withInput();
            }

            if (isset($variantData['id']) && !empty($variantData['id'])) {
                $variant = ProductVariant::find($variantData['id']);
                if ($variant && $variant->product_id == $product->id) {
                    $variant->update([
                        'size' => strtoupper($variantData['size']),
                        'stock' => $variantData['stock'],
                        'price' => $variantData['price'] ?? $req->price,
                        'sku' => $variantData['sku'] ?? $variant->sku,
                    ]);
                    $updatedVariantIds[] = $variant->id;
                }
            } else {
                $newVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => strtoupper($variantData['size']),
                    'stock' => $variantData['stock'],
                    'price' => $variantData['price'] ?? $req->price,
                    'sku' => $variantData['sku'] ?? null,
                ]);
                $updatedVariantIds[] = $newVariant->id;
            }
        }

        ProductVariant::where('product_id', $product->id)
            ->whereNotIn('id', $updatedVariantIds)
            ->delete();

        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success','Product berhasil diupdate!');
    }

    function delete($id) 
    {
        $product = Product::with(['images', 'variants'])->findOrFail($id);
        
        // Hapus relasi image_id di products dulu
        $product->update(['image_id' => null]);
        
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }
        
        $product->variants()->delete();
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success','Product berhasil dihapus!');
    }

    // Set primary image
    function setPrimary(Request $req)
    {
        $image = File::find($req->image_id);
        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Image not found']);
        }
        
        // Reset all primary for this product
        File::where('product_id', $image->product_id)->update(['is_primary' => false]);
        
        // Set this image as primary
        $image->update(['is_primary' => true]);
        
        // Get updated product with images
        $product = Product::with('images')->find($image->product_id);
        
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    
}