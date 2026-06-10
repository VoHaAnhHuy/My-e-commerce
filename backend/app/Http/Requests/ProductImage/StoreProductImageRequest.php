<?php

namespace App\Http\Requests\ProductImage;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'url'        => 'required|string|max:500',
            'alt'        => 'nullable|string|max:255',
            'text'       => 'nullable|string|max:255',
            'sort_order' => 'sometimes|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Sản phẩm không được để trống.',
            'url.required'        => 'URL hình ảnh không được để trống.',
        ];
    }
}
