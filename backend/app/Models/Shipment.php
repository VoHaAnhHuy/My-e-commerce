<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'carrier',
        'tracking_code',
        'shipping_fee',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'shipping_fee' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItems(): BelongsToMany
    {
        return $this->belongsToMany(OrderItem::class, 'shipment_items')
            ->withPivot('quantity');
    }
}
