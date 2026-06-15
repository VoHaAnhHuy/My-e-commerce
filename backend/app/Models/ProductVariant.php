<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'price',
        'compare_at_price',
        'track_inventory',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'track_inventory' => 'boolean',
        ];
    }

    /**
     * The product this variant belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Attribute values for this variant (e.g. Color: Red, Size: XL).
     */
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'variant_attribute_values', 'variant_id', 'attribute_value_id');
    }

    /**
     * Images for this variant.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'variant_id');
    }

    /**
     * Inventory stocks across locations.
     */
    public function inventoryStocks(): HasMany
    {
        return $this->hasMany(InventoryStock::class, 'variant_id');
    }

    /**
     * Reviews for this variant.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'variant_id');
    }
}
