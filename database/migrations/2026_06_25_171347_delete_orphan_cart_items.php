<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Cart;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus cart items yang product-nya sudah tidak ada
        Cart::whereDoesntHave('product')->delete();
    }

    public function down(): void
    {
        // Tidak bisa rollback karena ini data
    }
};