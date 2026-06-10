<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coupon\ApplyCouponRequest;
use App\Http\Requests\Coupon\StoreCouponRequest;
use App\Http\Requests\Coupon\UpdateCouponRequest;
use App\Services\CouponService;
use Illuminate\Http\JsonResponse;

class CouponController extends Controller
{
    public function __construct(
        protected CouponService $couponService,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->couponService->getAll()]);
    }

    public function show(int $coupon): JsonResponse
    {
        $result = $this->couponService->getById($coupon);

        if (!$result) {
            return response()->json(['message' => 'Mã giảm giá không tồn tại.'], 404);
        }

        return response()->json(['data' => $result]);
    }

    public function store(StoreCouponRequest $request): JsonResponse
    {
        $coupon = $this->couponService->create($request->validated());

        return response()->json([
            'message' => 'Tạo mã giảm giá thành công.',
            'data'    => $coupon,
        ], 201);
    }

    public function update(UpdateCouponRequest $request, int $coupon): JsonResponse
    {
        $result = $this->couponService->update($coupon, $request->validated());

        if (!$result) {
            return response()->json(['message' => 'Mã giảm giá không tồn tại.'], 404);
        }

        return response()->json([
            'message' => 'Cập nhật mã giảm giá thành công.',
            'data'    => $result,
        ]);
    }

    public function destroy(int $coupon): JsonResponse
    {
        if (!$this->couponService->delete($coupon)) {
            return response()->json(['message' => 'Mã giảm giá không tồn tại.'], 404);
        }

        return response()->json(['message' => 'Xóa mã giảm giá thành công.']);
    }

    /**
     * POST /api/coupons/apply
     */
    public function apply(ApplyCouponRequest $request): JsonResponse
    {
        $result = $this->couponService->applyCoupon(
            $request->validated()['code'],
            $request->input('order_amount', 0)
        );

        $status = $result['valid'] ? 200 : 422;

        return response()->json($result, $status);
    }
}
