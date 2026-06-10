<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductVariantRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductVariantService
{
    public function __construct(
        protected ProductVariantRepositoryInterface $variantRepo,
    ) {}

    public function getByProduct(int $productId): Collection
    {
        return $this->variantRepo->getByProduct($productId);
    }

    public function getById(int $id): ?Model
    {
        return $this->variantRepo->getById($id)?->load('attributeValues.attribute');
    }

    public function create(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $variant = $this->variantRepo->create([
                'product_id'       => $data['product_id'],
                'sku'              => $data['sku'],
                'barcode'          => $data['barcode'] ?? null,
                'price'            => $data['price'],
                'compare_at_price' => $data['compare_at_price'] ?? null,
                'track_inventory'  => $data['track_inventory'] ?? true,
                'status'           => $data['status'] ?? 'active',
            ]);

            if (!empty($data['attribute_value_ids'])) {
                $variant->attributeValues()->sync($data['attribute_value_ids']);
            }

            return $variant->load('attributeValues.attribute');
        });
    }

    public function update(int $id, array $data): ?Model
    {
        return DB::transaction(function () use ($id, $data) {
            $variant = $this->variantRepo->update($id, collect($data)->only([
                'sku', 'barcode', 'price', 'compare_at_price', 'track_inventory', 'status',
            ])->toArray());

            if (!$variant) {
                return null;
            }

            if (array_key_exists('attribute_value_ids', $data)) {
                $variant->attributeValues()->sync($data['attribute_value_ids'] ?? []);
            }

            return $variant->load('attributeValues.attribute');
        });
    }

    public function delete(int $id): bool
    {
        return $this->variantRepo->delete($id);
    }
}
