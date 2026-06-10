<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|required|string|max:255',
            'slug'        => [
                'sometimes', 'required', 'string', 'max:255',
                Rule::unique('products', 'slug')->ignore($this->route('product')),
            ],
            'description' => 'nullable|string',
            'base_price'  => 'sometimes|required|numeric|min:0',
            'status'      => 'sometimes|string|in:active,inactive,draft',
            'category_ids'   => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Tên sản phẩm không được để trống.',
            'slug.required'       => 'Slug không được để trống.',
            'slug.unique'         => 'Slug đã tồn tại.',
            'base_price.required' => 'Giá cơ bản không được để trống.',
            'base_price.min'      => 'Giá cơ bản không được nhỏ hơn 0.',
            'status.in'           => 'Trạng thái phải là active, inactive hoặc draft.',
        ];
    }
}
