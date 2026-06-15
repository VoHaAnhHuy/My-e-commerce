<?php

namespace App\Http\Requests\ProductImage;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductImageRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'url'        => 'sometimes|required|string|max:500',
            'alt'        => 'nullable|string|max:255',
            'text'       => 'nullable|string|max:255',
            'sort_order' => 'sometimes|integer|min:0',
        ];
    }
}
