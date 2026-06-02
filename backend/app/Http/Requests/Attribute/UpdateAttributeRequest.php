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
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('attributes', 'name')->ignore($this->route('attribute')),
            ],
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
