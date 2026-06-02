<?php

namespace App\Http\Requests\AttributeValue;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attribute_id' => 'sometimes|required|integer|exists:attributes,id',
            'value'        => 'sometimes|required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'attribute_id.required' => 'Thuộc tính không được để trống.',
            'attribute_id.exists'   => 'Thuộc tính không tồn tại.',
            'value.required'        => 'Giá trị thuộc tính không được để trống.',
            'value.max'             => 'Giá trị thuộc tính không được vượt quá 100 ký tự.',
        ];
    }
}
