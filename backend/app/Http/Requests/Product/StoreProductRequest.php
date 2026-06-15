<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'base_price'  => 'required|numeric|min:0',
            'status'      => 'sometimes|string|in:active,inactive,draft,archived',
            'category_ids'   => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',
            'variants'                          => 'nullable|array|min:1',
            'variants.*.sku'                    => 'required|string|max:100|distinct',
            'variants.*.barcode'                => 'nullable|string|max:100|distinct',
            'variants.*.price'                  => 'required|numeric|min:0',
            'variants.*.compare_at_price'       => 'nullable|numeric|min:0',
            'variants.*.track_inventory'        => 'sometimes|boolean',
            'variants.*.status'                 => 'sometimes|string|in:active,inactive',
            'variants.*.attribute_value_ids'    => 'nullable|array',
            'variants.*.attribute_value_ids.*'  => 'integer|exists:attribute_values,id',
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
            'status.in'           => 'Trạng thái phải là active, inactive, draft hoặc archived.',
        ];
    }
}
