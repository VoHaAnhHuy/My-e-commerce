<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentTransaction\StorePaymentTransactionRequest;
use App\Http\Requests\PaymentTransaction\UpdatePaymentTransactionRequest;
use App\Services\PaymentTransactionService;
use Illuminate\Http\JsonResponse;

class PaymentTransactionController extends Controller
{
    public function __construct(
        protected PaymentTransactionService $transactionService,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->transactionService->getAll()]);
    }

    /**
     * GET /api/admin/orders/{order}/transactions
     */
    public function byOrder(int $order): JsonResponse
    {
        return response()->json([
            'data' => $this->transactionService->getByOrder($order),
        ]);
    }

    public function show(int $transaction): JsonResponse
    {
        $result = $this->transactionService->getById($transaction);

        if (!$result) {
            return response()->json(['message' => 'Giao dịch không tồn tại.'], 404);
        }

        return response()->json(['data' => $result]);
    }

    public function store(StorePaymentTransactionRequest $request): JsonResponse
    {
        $transaction = $this->transactionService->create($request->validated());

        return response()->json([
            'message' => 'Tạo giao dịch thành công.',
            'data'    => $transaction,
        ], 201);
    }

    public function update(UpdatePaymentTransactionRequest $request, int $transaction): JsonResponse
    {
        $result = $this->transactionService->update($transaction, $request->validated());

        if (!$result) {
            return response()->json(['message' => 'Giao dịch không tồn tại.'], 404);
        }

        return response()->json([
            'message' => 'Cập nhật giao dịch thành công.',
            'data'    => $result,
        ]);
    }

    public function destroy(int $transaction): JsonResponse
    {
        if (!$this->transactionService->delete($transaction)) {
            return response()->json(['message' => 'Giao dịch không tồn tại.'], 404);
        }

        return response()->json(['message' => 'Xóa giao dịch thành công.']);
    }
}
