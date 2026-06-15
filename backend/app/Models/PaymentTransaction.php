<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'order_id',
        'payment_method_id',
        'amount',
        'status',
        'provider_transaction_id',
        'idempotency_key',
        'raw_request',
        'raw_response',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'raw_request' => 'array',
            'raw_response' => 'array',
        ];
    }

    /**
     * The order this transaction belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * The payment method used.
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Refunds for this transaction.
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }
}
