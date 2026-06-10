<?php

namespace App\Services;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\CouponRepositoryInterface;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepo,
        protected CouponRepositoryInterface $couponRepo,
        protected ProductVariantRepositoryInterface $variantRepo,
        protected CouponService $couponService,
    ) {}

    public function getByUser(int $userId): Collection
    {
        return $this->orderRepo->getByUser($userId);
    }

    public function getAll(): Collection
    {
        return $this->orderRepo->getAll();
    }

    public function getById(int $id): ?Model
    {
        return $this->orderRepo->getById($id)?->load('items', 'coupon', 'transactions.paymentMethod');
    }

    /**
     * Tạo đơn hàng mới.
     * - Tính subtotal từ variant prices
     * - Snapshot product info vào order_items
     * - Apply coupon nếu có
     * - Tăng used_count coupon
     */
    public function create(int $userId, array $data): Model
    {
        return DB::transaction(function () use ($userId, $data) {
            $subtotal = 0;
            $orderItems = [];

            // Chuẩn bị order items + tính subtotal
            foreach ($data['items'] as $item) {
                $variant = $this->variantRepo->getById($item['variant_id']);
                $lineTotal = $variant->price * $item['qty'];
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'product_id'           => $variant->product_id,
                    'variant_id'           => $variant->id,
                    'product_name_snapshot' => $variant->product->name,
                    'variant_sku_snapshot'  => $variant->sku,
                    'attributes_snapshot'   => $variant->attributeValues->map(fn ($av) => [
                        'attribute' => $av->attribute->name,
                        'value'     => $av->value,
                    ])->toArray() ?: null,
                    'unit_price'           => $variant->price,
                    'qty'                  => $item['qty'],
                    'line_total'           => $lineTotal,
                ];
            }

            // Apply coupon
            $discountAmount = 0;
            $couponId = null;

            if (!empty($data['coupon_code'])) {
                $couponResult = $this->couponService->applyCoupon($data['coupon_code'], $subtotal);

                if ($couponResult['valid']) {
                    $discountAmount = $couponResult['discount'];
                    $couponId = $couponResult['coupon']->id;
                    $this->couponRepo->incrementUsedCount($couponId);
                }
            }

            $total = $subtotal - $discountAmount + ($data['shipping_fee'] ?? 0);

            // Tạo order
            $order = $this->orderRepo->create([
                'user_id'                   => $userId,
                'coupon_id'                 => $couponId,
                'subtotal'                  => $subtotal,
                'discount'                  => $discountAmount,
                'shipping'                  => $data['shipping_fee'] ?? 0,
                'total'                     => max($total, 0),
                'status'                    => 'pending',
                'payment_status'            => 'unpaid',
                'shipping_address_snapshot' => $data['shipping_address'],
            ]);

            // Tạo order items
            $order->items()->createMany($orderItems);

            return $order->load('items', 'coupon');
        });
    }

    public function updateStatus(int $id, string $status): bool
    {
        return $this->orderRepo->updateStatus($id, $status);
    }

    /**
     * Hủy đơn hàng (chỉ khi status = pending).
     */
    public function cancel(int $id, int $userId): array
    {
        $order = $this->orderRepo->getById($id);

        if (!$order || $order->user_id !== $userId) {
            return ['success' => false, 'message' => 'Đơn hàng không tồn tại.'];
        }

        if ($order->status !== 'pending') {
            return ['success' => false, 'message' => 'Chỉ có thể hủy đơn hàng đang chờ xử lý.'];
        }

        $this->orderRepo->updateStatus($id, 'cancelled');

        return ['success' => true, 'message' => 'Hủy đơn hàng thành công.'];
    }
}
