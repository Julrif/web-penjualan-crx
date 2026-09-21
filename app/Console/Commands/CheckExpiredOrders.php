<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CheckExpiredOrders extends Command
{
    protected $signature = 'orders:check-expired';
    protected $description = 'Check and cancel expired orders';

    public function handle()
    {
        $expiredOrders = Order::where('payment_status', 'pending')
            ->where('expired_at', '<', now())
            ->where('status', '!=', 'cancelled')
            ->with('items')
            ->get();

        foreach ($expiredOrders as $order) {
            // 👇 Kembalikan stok
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->increment('stock', $item->quantity);
                    }
                }
            }
            
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'expired'
            ]);
        }

        $this->info('Expired orders cancelled: ' . $expiredOrders->count());
    }
}