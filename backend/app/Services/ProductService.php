<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->productRepo->getActive();
    }

    public function getById(int $id): ?Model
    {
        return $this->productRepo->getById($id)?->load('categories', 'variants.attributeValues.attribute', 'images');
    }

    public function findBySlug(string $slug): ?Model
    {
        return $this->productRepo->findBySlug($slug);
    }

    /**
     * Tạo product kèm variants + sync categories.
     */
    public function create(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $product = $this->productRepo->create([
                'name'        => $data['name'],
                'slug'        => $data['slug'],
                'description' => $data['description'] ?? null,
                'base_price'  => $data['base_price'],
                'status'      => $data['status'] ?? 'active',
            ]);

            // Sync categories
            if (!empty($data['category_ids'])) {
                $product->categories()->sync($data['category_ids']);
            }

            // Create variants
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    $variant = $product->variants()->create([
                        'sku'              => $variantData['sku'],
                        'barcode'          => $variantData['barcode'] ?? null,
                        'price'            => $variantData['price'],
                        'compare_at_price' => $variantData['compare_at_price'] ?? null,
                        'track_inventory'  => $variantData['track_inventory'] ?? true,
                        'status'           => $variantData['status'] ?? 'active',
                    ]);

                    if (!empty($variantData['attribute_value_ids'])) {
                        $variant->attributeValues()->sync($variantData['attribute_value_ids']);
                    }
                }
            }

            return $product->load('categories', 'variants.attributeValues');
        });
    }

    /**
     * Cập nhật product + sync categories.
     */
    public function update(int $id, array $data): ?Model
    {
        return DB::transaction(function () use ($id, $data) {
            $product = $this->productRepo->update($id, collect($data)->only([
                'name', 'slug', 'description', 'base_price', 'status',
            ])->toArray());

            if (!$product) {
                return null;
            }

            if (array_key_exists('category_ids', $data)) {
                $product->categories()->sync($data['category_ids'] ?? []);
            }

            return $product->load('categories', 'variants.attributeValues');
        });
    }

    public function delete(int $id): bool
    {
        return $this->productRepo->delete($id);
    }
}
