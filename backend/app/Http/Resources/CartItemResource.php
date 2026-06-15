<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $variant = $this->productVariant;
        $product = $variant?->product;
        $image   = $variant?->images?->first();

        return [
            'id'                 => $this->id,
            'product_variant_id' => $this->product_variant_id,
            'product_name'       => $product?->name,
            'variant_sku'        => $variant?->sku,
            'variant_attributes' => $variant?->attributeValues?->map(fn ($av) => [
                'attribute' => $av->attribute?->name,
                'value'     => $av->name,
            ]),
            'image_url'          => $image?->image_url,
            'unit_price'         => $variant?->price ? (float) $variant->price : null,
            'compare_at_price'   => $variant?->compare_at_price ? (float) $variant->compare_at_price : null,
            'quantity'           => $this->quantity,
            'subtotal'           => $variant?->price ? (float) ($variant->price * $this->quantity) : null,
            'created_at'         => $this->created_at?->toISOString(),
            'updated_at'         => $this->updated_at?->toISOString(),
        ];
    }
}
