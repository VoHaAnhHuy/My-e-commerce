<?php

namespace App\Http\Requests\Refund;

use Illuminate\Foundation\Http\FormRequest;

class StoreRefundRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'order_id'               => 'required|integer|exists:orders,id',
            'payment_transaction_id' => 'required|integer|exists:payment_transactions,id',
            'amount'                 => 'required|numeric|min:0',
            'reason'                 => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required'               => 'Đơn hàng không được để trống.',
            'payment_transaction_id.required' => 'Giao dịch thanh toán không được để trống.',
            'amount.required'                 => 'Số tiền hoàn không được để trống.',
            'amount.min'                      => 'Số tiền hoàn không được nhỏ hơn 0.',
        ];
    }
}
