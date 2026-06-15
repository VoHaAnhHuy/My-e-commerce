<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $items = CartItemResource::collection($this->whenLoaded('items'));

        // Tính tổng giá từ items
        $totalPrice = $this->items->sum(function ($item) {
            $price = $item->productVariant?->price ?? 0;
            return (float) $price * $item->quantity;
        });

        return [
            'id'          => $this->id,
            'status'      => $this->status,
            'items'       => $items,
            'total_items' => $this->items->sum('quantity'),
            'total_price' => round($totalPrice, 2),
            'expires_at'  => $this->expires_at?->toISOString(),
            'created_at'  => $this->created_at?->toISOString(),
            'updated_at'  => $this->updated_at?->toISOString(),
        ];
    }
}
