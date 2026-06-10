<?php

namespace App\Http\Requests\PaymentTransaction;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id'                => 'required|integer|exists:orders,id',
            'payment_method_id'       => 'required|integer|exists:payment_methods,id',
            'amount'                  => 'required|numeric|min:0',
            'status'                  => 'required|string|in:pending,success,failed,refunded',
            'provider_transaction_id' => 'nullable|string|max:255',
            'idempotency_key'         => 'nullable|string|max:255|unique:payment_transactions,idempotency_key',
            'raw_request'             => 'nullable|array',
            'raw_response'            => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required'          => 'Đơn hàng không được để trống.',
            'order_id.exists'            => 'Đơn hàng không tồn tại.',
            'payment_method_id.required' => 'Phương thức thanh toán không được để trống.',
            'payment_method_id.exists'   => 'Phương thức thanh toán không tồn tại.',
            'amount.required'            => 'Số tiền không được để trống.',
            'amount.min'                 => 'Số tiền không được nhỏ hơn 0.',
            'status.required'            => 'Trạng thái không được để trống.',
            'status.in'                  => 'Trạng thái không hợp lệ.',
            'idempotency_key.unique'     => 'Idempotency key đã tồn tại.',
        ];
    }
}
