<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            // Tambah kolom product_id (relasi ke products)
            $table->foreignId('product_id')->nullable()->after('id')->constrained('products')->onDelete('cascade');
            
            // Tambah kolom is_primary (untuk gambar utama)
            $table->boolean('is_primary')->default(false)->after('path');
        });
    }

    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'is_primary']);
        });
    }
};