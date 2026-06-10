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
        return $this->orderRepo->getById($id)?->load('items', 'paymentMethod', 'coupon', 'transactions');
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
                $variant = $this->variantRepo->getById($item['product_variant_id']);
                $lineTotal = $variant->price * $item['quantity'];
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'product_variant_id' => $variant->id,
                    'product_name'       => $variant->product->name,
                    'variant_info'       => $variant->attributeValues->pluck('value')->implode(' / ') ?: null,
                    'sku'                => $variant->sku,
                    'price'              => $variant->price,
                    'quantity'           => $item['quantity'],
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

            $totalAmount = $subtotal - $discountAmount + ($data['shipping_fee'] ?? 0);

            // Tạo order
            $order = $this->orderRepo->create([
                'user_id'            => $userId,
                'coupon_id'          => $couponId,
                'payment_method_id'  => $data['payment_method_id'],
                'shipping_name'      => $data['shipping_name'],
                'shipping_phone'     => $data['shipping_phone'],
                'shipping_address'   => $data['shipping_address'],
                'shipping_ward'      => $data['shipping_ward'] ?? null,
                'shipping_district'  => $data['shipping_district'],
                'shipping_city'      => $data['shipping_city'],
                'subtotal'           => $subtotal,
                'discount_amount'    => $discountAmount,
                'shipping_fee'       => $data['shipping_fee'] ?? 0,
                'total_amount'       => max($totalAmount, 0),
                'note'               => $data['note'] ?? null,
                'status'             => 'pending',
            ]);

            // Tạo order items
            $order->items()->createMany($orderItems);

            return $order->load('items', 'paymentMethod', 'coupon');
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
