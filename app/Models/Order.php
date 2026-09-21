<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total',
        'status',
        'shipping_method',
        'shipping_cost',
        'operational_cost', // <-- TAMBAHKAN INI
        'courier',
        'tracking_number',
        'payment_method',
        'payment_status',
        'paid_at',
        'verified_at',
        'expired_at',
        'address',
        'city',          
        'province',      
        'postal_code',   
        'phone',
        'snap_token',
        'notes'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}