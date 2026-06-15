<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryLocation extends Model
{
    protected $fillable = [
        'code',
        'name',
        'address',
    ];

    public function stocks(): HasMany
    {
        return $this->hasMany(InventoryStock::class, 'location_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class, 'location_id');
    }
}
