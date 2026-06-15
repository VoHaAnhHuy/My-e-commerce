<?php

namespace App\Http\Requests\ProductVariant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku'   => [
                'sometimes', 'required', 'string', 'max:100',
                Rule::unique('product_variants', 'sku')->ignore($this->route('variant')),
            ],
            'barcode' => [
                'nullable', 'string', 'max:100',
                Rule::unique('product_variants', 'barcode')->ignore($this->route('variant')),
            ],
            'price'            => 'sometimes|required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'track_inventory'  => 'sometimes|boolean',
            'status'           => 'sometimes|string|in:active,inactive',
            'attribute_value_ids'   => 'nullable|array',
            'attribute_value_ids.*' => 'integer|exists:attribute_values,id',
        ];
    }

    public function messages(): array
    {
        return [
            'sku.required'   => 'SKU không được để trống.',
            'sku.unique'     => 'SKU đã tồn tại.',
            'barcode.unique' => 'Barcode đã tồn tại.',
            'price.required' => 'Giá không được để trống.',
            'price.min'      => 'Giá không được nhỏ hơn 0.',
            'status.in'      => 'Trạng thái phải là active hoặc inactive.',
        ];
    }
}
