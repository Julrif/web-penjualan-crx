<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected $serverKey;
    protected $isProduction;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->isProduction = config('midtrans.is_production');
        
        Log::info('Midtrans Service initialized');
        Log::info('Server Key: ' . substr($this->serverKey, 0, 20) . '...');
    }

    public function createTransaction($order, $user)
    {
        // Reset snap_token
        $order->update(['snap_token' => null]);
        
        $items = [];
        
        foreach ($order->items as $item) {
            $items[] = [
                'id' => (string) $item->product_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => substr($item->product_name . ($item->size ? ' (' . $item->size . ')' : ''), 0, 50)
            ];
        }

        $items[] = [
            'id' => 'SHIPPING',
            'price' => (int) $order->shipping_cost,
            'quantity' => 1,
            'name' => 'Ongkos Kirim ' . $order->shipping_method
        ];

        $items[] = [
            'id' => 'OPERATIONAL',
            'price' => (int) ($order->operational_cost ?? 6000),
            'quantity' => 1,
            'name' => 'Biaya Operasional'
        ];

        $payload = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => [
                'first_name' => substr($user->name, 0, 20),
                'email' => $user->email,
                'phone' => $order->phone,
            ],
            'item_details' => $items,
            'callbacks' => [
                'finish' => route('midtrans.success', ['order_id' => $order->order_number]),
                'error' => route('midtrans.failed', ['order_id' => $order->order_number]),
            ],
        ];

        Log::info('Midtrans Payload: ' . json_encode($payload));

        $client = new Client([
            'verify' => false,
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':'),
                'Content-Type' => 'application/json',
            ]
        ]);

        $baseUrl = $this->isProduction 
            ? 'https://app.midtrans.com' 
            : 'https://app.sandbox.midtrans.com';

        try {
            $response = $client->post($baseUrl . '/snap/v1/transactions', [
                'json' => $payload
            ]);

            $result = json_decode($response->getBody(), true);
            
            Log::info('Midtrans Response: ' . json_encode($result));

            // Simpan snap_token ke order
            $order->update([
                'snap_token' => $result['token'] ?? null
            ]);
            
            if (!isset($result['redirect_url'])) {
                throw new \Exception('Midtrans tidak mengembalikan redirect_url');
            }
            
            return (object) [
                'redirect_url' => $result['redirect_url'],
                'token' => $result['token'] ?? null
            ];
            
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            Log::error('Response Body: ' . ($e->getResponse() ? $e->getResponse()->getBody() : 'No response'));
            throw new \Exception('Midtrans Error: ' . $e->getMessage());
        }
    }
}