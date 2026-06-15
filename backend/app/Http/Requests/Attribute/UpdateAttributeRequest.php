<?php

namespace App\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'code' => [
                'sometimes', 'required', 'string', 'max:100',
                Rule::unique('attributes', 'code')->ignore($this->route('attribute')),
            ],
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
