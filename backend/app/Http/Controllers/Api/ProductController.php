<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->productService->getAll()]);
    }

    public function show(int $product): JsonResponse
    {
        $result = $this->productService->getById($product);
        if (!$result) {
            return response()->json(['message' => 'Sản phẩm không tồn tại.'], 404);
        }
        return response()->json(['data' => $result]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());
        return response()->json(['message' => 'Tạo sản phẩm thành công.', 'data' => $product], 201);
    }

    public function update(UpdateProductRequest $request, int $product): JsonResponse
    {
        $result = $this->productService->update($product, $request->validated());
        if (!$result) {
            return response()->json(['message' => 'Sản phẩm không tồn tại.'], 404);
        }
        return response()->json(['message' => 'Cập nhật sản phẩm thành công.', 'data' => $result]);
    }

    public function destroy(int $product): JsonResponse
    {
        if (!$this->productService->delete($product)) {
            return response()->json(['message' => 'Sản phẩm không tồn tại.'], 404);
        }
        return response()->json(['message' => 'Xóa sản phẩm thành công.']);
    }
}
