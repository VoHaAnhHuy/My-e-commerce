<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_variant_id' => 'required|integer|exists:product_variants,id',
            'quantity'           => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'product_variant_id.required' => 'Biến thể sản phẩm không được để trống.',
            'product_variant_id.exists'   => 'Biến thể sản phẩm không tồn tại.',
            'quantity.required'           => 'Số lượng không được để trống.',
            'quantity.integer'            => 'Số lượng phải là số nguyên.',
            'quantity.min'                => 'Số lượng phải ít nhất là 1.',
        ];
    }
}
