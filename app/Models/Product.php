<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ["id"];

    function image () {
        return $this->hasOne(File::class, "id", "image_id");
    }

    // Relasi ke variants
    function variants() {
        return $this->hasMany(ProductVariant::class);
    }

    // Helper: total stok semua varian
    function getTotalStockAttribute() {
        return $this->variants->sum('stock');
    }

    // Helper: daftar size yang tersedia
    function getAvailableSizesAttribute() {
        return $this->variants->pluck('size')->toArray();
    }

    // Helper: varian dengan stok > 0
    function getAvailableVariantsAttribute() {
        return $this->variants->where('stock', '>', 0);
    }

    // Relasi ke gambar (SEMUA gambar)
    public function images()
    {
        return $this->hasMany(File::class);
    }

    // Relasi ke gambar utama (hanya 1)
    public function primaryImage()
    {
        return $this->hasOne(File::class)->where('is_primary', true);
    }

    // Relasi ke wishlist
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Helper: hitung total wishlist
    public function getWishlistCountAttribute()
    {
        return $this->wishlists()->count();
    }
}