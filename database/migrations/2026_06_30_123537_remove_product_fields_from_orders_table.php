<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus kolom yang tidak diperlukan lagi (sudah pakai order_items)
            $table->dropColumn([
                'product_name',
                'size',
                'quantity'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('product_name');
            $table->string('size')->nullable();
            $table->integer('quantity')->default(1);
        });
    }
};