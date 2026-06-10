<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethod\StorePaymentMethodRequest;
use App\Http\Requests\PaymentMethod\UpdatePaymentMethodRequest;
use App\Services\PaymentMethodService;
use Illuminate\Http\JsonResponse;

class PaymentMethodController extends Controller
{
    public function __construct(
        protected PaymentMethodService $paymentMethodService,
    ) {}

    /**
     * GET /api/payment-methods — Chỉ trả về active (public).
     */
    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->paymentMethodService->getActive()]);
    }

    /**
     * GET /api/admin/payment-methods — Tất cả (admin).
     */
    public function adminIndex(): JsonResponse
    {
        return response()->json(['data' => $this->paymentMethodService->getAll()]);
    }

    public function show(int $paymentMethod): JsonResponse
    {
        $result = $this->paymentMethodService->getById($paymentMethod);

        if (!$result) {
            return response()->json(['message' => 'Phương thức thanh toán không tồn tại.'], 404);
        }

        return response()->json(['data' => $result]);
    }

    public function store(StorePaymentMethodRequest $request): JsonResponse
    {
        $method = $this->paymentMethodService->create($request->validated());

        return response()->json([
            'message' => 'Tạo phương thức thanh toán thành công.',
            'data'    => $method,
        ], 201);
    }

    public function update(UpdatePaymentMethodRequest $request, int $paymentMethod): JsonResponse
    {
        $result = $this->paymentMethodService->update($paymentMethod, $request->validated());

        if (!$result) {
            return response()->json(['message' => 'Phương thức thanh toán không tồn tại.'], 404);
        }

        return response()->json([
            'message' => 'Cập nhật phương thức thanh toán thành công.',
            'data'    => $result,
        ]);
    }

    public function destroy(int $paymentMethod): JsonResponse
    {
        if (!$this->paymentMethodService->delete($paymentMethod)) {
            return response()->json(['message' => 'Phương thức thanh toán không tồn tại.'], 404);
        }

        return response()->json(['message' => 'Xóa phương thức thanh toán thành công.']);
    }
}
