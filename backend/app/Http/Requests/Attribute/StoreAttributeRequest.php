<?php

namespace App\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:attributes,code',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên thuộc tính không được để trống.',
            'code.required' => 'Mã thuộc tính không được để trống.',
            'code.unique'   => 'Mã thuộc tính đã tồn tại.',
        ];
    }
}
