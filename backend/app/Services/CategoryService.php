<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->categoryRepo->getRootCategories();
    }

    public function getById(int $id): ?Model
    {
        return $this->categoryRepo->getById($id)?->load('children', 'products');
    }

    public function findBySlug(string $slug): ?Model
    {
        return $this->categoryRepo->findBySlug($slug);
    }

    public function create(array $data): Model
    {
        // Kiểm tra vòng lặp nếu có parent_id
        if (!empty($data['parent_id'])) {
            $this->assertNotCircular($data['parent_id']);
        }

        return $this->categoryRepo->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        // Kiểm tra vòng lặp: không cho đặt parent_id = chính nó hoặc con cháu
        if (array_key_exists('parent_id', $data) && $data['parent_id'] !== null) {
            $this->assertNotCircular($data['parent_id'], $id);
        }

        return $this->categoryRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->categoryRepo->delete($id);
    }

    /**
     * FR-CAT-001: Chống tạo vòng lặp cây phân cấp.
     *
     * Khi tạo: chỉ cần kiểm tra parent_id tồn tại (validation đã lo).
     * Khi cập nhật: đảm bảo parent_id mới không phải chính nó hoặc con cháu.
     *
     * @param int      $parentId   ID danh mục cha muốn gán
     * @param int|null $categoryId ID danh mục đang cập nhật (null nếu tạo mới)
     */
    protected function assertNotCircular(int $parentId, ?int $categoryId = null): void
    {
        // Không cho đặt parent = chính nó
        if ($categoryId !== null && $parentId === $categoryId) {
            throw ValidationException::withMessages([
                'parent_id' => ['Danh mục không thể là cha của chính nó.'],
            ]);
        }

        // Không cho đặt parent = con cháu (tạo vòng lặp)
        if ($categoryId !== null) {
            $descendantIds = $this->getAllDescendantIds($categoryId);

            if (in_array($parentId, $descendantIds, true)) {
                throw ValidationException::withMessages([
                    'parent_id' => ['Không thể đặt danh mục con/cháu làm cha (vòng lặp cây).'],
                ]);
            }
        }
    }

    /**
     * Lấy tất cả ID con cháu (đệ quy) của một danh mục.
     */
    protected function getAllDescendantIds(int $categoryId): array
    {
        $ids = [];
        $children = Category::where('parent_id', $categoryId)->pluck('id');

        foreach ($children as $childId) {
            $ids[] = $childId;
            $ids = array_merge($ids, $this->getAllDescendantIds($childId));
        }

        return $ids;
    }
}
