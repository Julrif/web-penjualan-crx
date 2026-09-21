<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan kolom untuk checkout
            $table->string('shipping_method')->nullable()->after('status');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_method');
            $table->string('payment_method')->nullable()->after('shipping_cost');
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->text('address')->nullable()->after('payment_status');
            $table->string('phone')->nullable()->after('address');
            $table->text('notes')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_method',
                'shipping_cost',
                'payment_method',
                'payment_status',
                'address',
                'phone',
                'notes'
            ]);
        });
    }
};