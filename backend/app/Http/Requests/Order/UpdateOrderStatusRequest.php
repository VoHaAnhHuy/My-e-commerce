<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:pending,confirmed,processing,shipping,delivered,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Trạng thái đơn hàng không được để trống.',
            'status.in'       => 'Trạng thái đơn hàng không hợp lệ.',
        ];
    }
}
