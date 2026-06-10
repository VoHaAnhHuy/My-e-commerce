<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductVariant\StoreProductVariantRequest;
use App\Http\Requests\ProductVariant\UpdateProductVariantRequest;
use App\Services\ProductVariantService;
use Illuminate\Http\JsonResponse;

class ProductVariantController extends Controller
{
    public function __construct(
        protected ProductVariantService $variantService,
    ) {}

    public function index(int $product): JsonResponse
    {
        return response()->json(['data' => $this->variantService->getByProduct($product)]);
    }

    public function show(int $variant): JsonResponse
    {
        $result = $this->variantService->getById($variant);
        if (!$result) {
            return response()->json(['message' => 'Biến thể không tồn tại.'], 404);
        }
        return response()->json(['data' => $result]);
    }

    public function store(StoreProductVariantRequest $request): JsonResponse
    {
        $variant = $this->variantService->create($request->validated());
        return response()->json(['message' => 'Tạo biến thể thành công.', 'data' => $variant], 201);
    }

    public function update(UpdateProductVariantRequest $request, int $variant): JsonResponse
    {
        $result = $this->variantService->update($variant, $request->validated());
        if (!$result) {
            return response()->json(['message' => 'Biến thể không tồn tại.'], 404);
        }
        return response()->json(['message' => 'Cập nhật biến thể thành công.', 'data' => $result]);
    }

    public function destroy(int $variant): JsonResponse
    {
        if (!$this->variantService->delete($variant)) {
            return response()->json(['message' => 'Biến thể không tồn tại.'], 404);
        }
        return response()->json(['message' => 'Xóa biến thể thành công.']);
    }
}
