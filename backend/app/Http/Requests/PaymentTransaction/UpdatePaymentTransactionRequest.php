<?php

namespace App\Http\Requests\PaymentTransaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider_transaction_id' => 'nullable|string|max:255',
            'status'                  => 'sometimes|required|string|in:pending,success,failed,refunded',
            'raw_request'             => 'nullable|array',
            'raw_response'            => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Trạng thái không được để trống.',
            'status.in'       => 'Trạng thái không hợp lệ.',
        ];
    }
}
