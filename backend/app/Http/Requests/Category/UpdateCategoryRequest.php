<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'sometimes|required|string|max:255',
            'slug'      => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->route('category')),
            ],
            'parent_id'  => 'nullable|integer|exists:categories,id',
            'sort_order' => 'sometimes|integer|min:0',
            'is_active'  => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Tên danh mục không được để trống.',
            'name.max'         => 'Tên danh mục không được vượt quá 255 ký tự.',
            'slug.required'    => 'Slug không được để trống.',
            'slug.max'         => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique'      => 'Slug đã tồn tại.',
            'parent_id.exists' => 'Danh mục cha không tồn tại.',
        ];
    }
}
