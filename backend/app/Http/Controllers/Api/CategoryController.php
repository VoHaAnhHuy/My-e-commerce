<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
    ) {}

    /**
     * GET /api/categories
     */
    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->categoryService->getAll()]);
    }

    /**
     * GET /api/categories/{category}
     */
    public function show(int $category): JsonResponse
    {
        $result = $this->categoryService->getById($category);

        if (!$result) {
            return response()->json(['message' => 'Danh mục không tồn tại.'], 404);
        }

        return response()->json(['data' => $result]);
    }

    /**
     * POST /api/admin/categories
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->create($request->validated());

        return response()->json([
            'message' => 'Tạo danh mục thành công.',
            'data'    => $category,
        ], 201);
    }

    /**
     * PUT /api/admin/categories/{category}
     */
    public function update(UpdateCategoryRequest $request, int $category): JsonResponse
    {
        $result = $this->categoryService->update($category, $request->validated());

        if (!$result) {
            return response()->json(['message' => 'Danh mục không tồn tại.'], 404);
        }

        return response()->json([
            'message' => 'Cập nhật danh mục thành công.',
            'data'    => $result,
        ]);
    }

    /**
     * DELETE /api/admin/categories/{category}
     */
    public function destroy(int $category): JsonResponse
    {
        if (!$this->categoryService->delete($category)) {
            return response()->json(['message' => 'Danh mục không tồn tại.'], 404);
        }

        return response()->json(['message' => 'Xóa danh mục thành công.']);
    }
}
