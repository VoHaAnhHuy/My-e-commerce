<?php

namespace App\Http\Requests\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'sometimes', 'required', 'string', 'max:50',
                Rule::unique('payment_methods', 'code')->ignore($this->route('payment_method')),
            ],
            'name'      => 'sometimes|required|string|max:100',
            'provider'  => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Mã phương thức thanh toán không được để trống.',
            'code.unique'   => 'Mã phương thức thanh toán đã tồn tại.',
            'code.max'      => 'Mã không được vượt quá 50 ký tự.',
            'name.required' => 'Tên phương thức thanh toán không được để trống.',
            'name.max'      => 'Tên không được vượt quá 100 ký tự.',
        ];
    }
}
