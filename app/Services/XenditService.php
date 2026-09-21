<?php

namespace App\Services;

use GuzzleHttp\Client;

class XenditService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('xendit.secret_key');
        
        $this->client = new Client([
            'base_uri' => 'https://api.xendit.co/',
            'verify' => false, // DISABLE SSL
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':'),
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    public function createInvoice($order, $user)
    {
        try {
            $response = $this->client->post('v2/invoices', [
                'json' => [
                    'external_id' => $order->order_number,
                    'amount' => (int) $order->total,
                    'payer_email' => $user->email,
                    'description' => 'Pembayaran Order #' . $order->order_number,
                    'invoice_duration' => 3600,
                    'currency' => 'IDR',
                    'payment_methods' => ['BCA', 'BNI', 'BRI', 'QRIS', 'OVO', 'DANA', 'SHOPEEPAY'],
                    'success_redirect_url' => route('xendit.success', $order->id),
                    'failure_redirect_url' => route('xendit.failed', $order->id),
                ]
            ]);

            return json_decode($response->getBody(), true);

        } catch (\Exception $e) {
            throw new \Exception('Xendit Error: ' . $e->getMessage());
        }
    }
}