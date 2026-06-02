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
            'name' => 'required|string|max:100|unique:attributes,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên thuộc tính không được để trống.',
            'name.max'      => 'Tên thuộc tính không được vượt quá 100 ký tự.',
            'name.unique'   => 'Tên thuộc tính đã tồn tại.',
        ];
    }
}
