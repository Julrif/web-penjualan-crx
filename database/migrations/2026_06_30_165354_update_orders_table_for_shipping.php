<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambah kolom paid_at jika belum ada
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }
            
            // Tambah kolom verified_at jika belum ada
            if (!Schema::hasColumn('orders', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('paid_at');
            }
            
            // Tambah expired_at setelah verified_at
            if (!Schema::hasColumn('orders', 'expired_at')) {
                $table->timestamp('expired_at')->nullable()->after('verified_at');
            }
            
            // Tambah courier jika belum ada
            if (!Schema::hasColumn('orders', 'courier')) {
                $table->string('courier')->nullable()->after('shipping_method');
            }
            
            // Tambah tracking_number jika belum ada
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'paid_at',
                'verified_at',
                'expired_at',
                'courier',
                'tracking_number'
            ]);
        });
    }
};