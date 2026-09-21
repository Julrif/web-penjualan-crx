<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = ["id"];

    function product() {
        return $this->hasOne(Product::class, "id", "product_id");
    }

    // TAMBAHKAN RELASI USER
    function user() {
        return $this->belongsTo(User::class);
    }
}