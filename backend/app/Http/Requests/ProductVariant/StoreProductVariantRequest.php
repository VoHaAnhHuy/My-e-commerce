<?php

namespace App\Http\Requests\ProductVariant;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'         => 'required|integer|exists:products,id',
            'sku'                => 'required|string|max:100|unique:product_variants,sku',
            'barcode'            => 'nullable|string|max:100|unique:product_variants,barcode',
            'price'              => 'required|numeric|min:0',
            'compare_at_price'   => 'nullable|numeric|min:0',
            'track_inventory'    => 'sometimes|boolean',
            'status'             => 'sometimes|string|in:active,inactive',
            'attribute_value_ids'   => 'nullable|array',
            'attribute_value_ids.*' => 'integer|exists:attribute_values,id',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Sản phẩm không được để trống.',
            'product_id.exists'   => 'Sản phẩm không tồn tại.',
            'sku.required'        => 'SKU không được để trống.',
            'sku.unique'          => 'SKU đã tồn tại.',
            'barcode.unique'      => 'Barcode đã tồn tại.',
            'price.required'      => 'Giá không được để trống.',
            'price.min'           => 'Giá không được nhỏ hơn 0.',
            'status.in'           => 'Trạng thái phải là active hoặc inactive.',
        ];
    }
}
