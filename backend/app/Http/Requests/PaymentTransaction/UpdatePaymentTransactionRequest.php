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
            'transaction_id' => 'nullable|string|max:255',
            'status'         => 'sometimes|required|string|in:pending,success,failed,refunded',
            'paid_at'        => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Trạng thái không được để trống.',
            'status.in'       => 'Trạng thái không hợp lệ.',
            'paid_at.date'    => 'Ngày thanh toán không hợp lệ.',
        ];
    }
}
