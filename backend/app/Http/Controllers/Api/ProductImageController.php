<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImage\StoreProductImageRequest;
use App\Http\Requests\ProductImage\UpdateProductImageRequest;
use App\Services\ProductImageService;
use Illuminate\Http\JsonResponse;

class ProductImageController extends Controller
{
    public function __construct(protected ProductImageService $imageService) {}

    public function index(int $product): JsonResponse
    {
        return response()->json(['data' => $this->imageService->getByProduct($product)]);
    }

    public function store(StoreProductImageRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Thêm hình ảnh thành công.',
            'data'    => $this->imageService->create($request->validated()),
        ], 201);
    }

    public function show(int $productImage): JsonResponse
    {
        $result = $this->imageService->getById($productImage);
        if (!$result) return response()->json(['message' => 'Hình ảnh không tồn tại.'], 404);
        return response()->json(['data' => $result]);
    }

    public function update(UpdateProductImageRequest $request, int $productImage): JsonResponse
    {
        $result = $this->imageService->update($productImage, $request->validated());
        if (!$result) return response()->json(['message' => 'Hình ảnh không tồn tại.'], 404);
        return response()->json(['message' => 'Cập nhật hình ảnh thành công.', 'data' => $result]);
    }

    public function destroy(int $productImage): JsonResponse
    {
        $this->imageService->delete($productImage);
        return response()->json(['message' => 'Xóa hình ảnh thành công.']);
    }
}
