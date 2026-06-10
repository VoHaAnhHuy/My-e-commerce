<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'slug'      => 'required|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|integer|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Tên danh mục không được để trống.',
            'name.max'        => 'Tên danh mục không được vượt quá 255 ký tự.',
            'slug.required'   => 'Slug không được để trống.',
            'slug.max'        => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique'     => 'Slug đã tồn tại.',
            'parent_id.exists' => 'Danh mục cha không tồn tại.',
        ];
    }
}
