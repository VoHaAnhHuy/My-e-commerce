<?php

namespace App\Services;

use App\Repositories\Interfaces\CouponRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CouponService
{
    public function __construct(
        protected CouponRepositoryInterface $couponRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->couponRepo->getAll();
    }

    public function getById(int $id): ?Model
    {
        return $this->couponRepo->getById($id);
    }

    public function create(array $data): Model
    {
        return $this->couponRepo->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        return $this->couponRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->couponRepo->delete($id);
    }

    /**
     * Apply coupon — kiểm tra validity và tính discount.
     *
     * @return array{valid: bool, message: string, discount?: float, coupon?: Model}
     */
    public function applyCoupon(string $code, float $orderAmount): array
    {
        $coupon = $this->couponRepo->findByCode($code);

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Mã giảm giá không tồn tại.'];
        }

        if (!$coupon->isValid()) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn hoặc không còn hiệu lực.'];
        }

        if ($coupon->min_order_amount > 0 && $orderAmount < $coupon->min_order_amount) {
            return [
                'valid' => false,
                'message' => "Đơn hàng phải tối thiểu " . number_format($coupon->min_order_amount) . "đ.",
            ];
        }

        // Tính discount
        $discount = $coupon->type === 'percentage'
            ? $orderAmount * ($coupon->value / 100)
            : (float) $coupon->value;

        // Giới hạn max_discount (chỉ cho percentage)
        if ($coupon->type === 'percentage' && $coupon->max_discount !== null) {
            $discount = min($discount, (float) $coupon->max_discount);
        }

        return [
            'valid'    => true,
            'message'  => 'Áp dụng mã giảm giá thành công.',
            'discount' => round($discount, 2),
            'coupon'   => $coupon,
        ];
    }
}
