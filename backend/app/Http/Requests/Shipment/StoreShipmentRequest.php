<?php

namespace App\Http\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'order_id'      => 'required|integer|exists:orders,id',
            'carrier'       => 'nullable|string|max:255',
            'tracking_code' => 'nullable|string|max:255',
            'shipping_fee'  => 'sometimes|numeric|min:0',
            'status'        => 'sometimes|string|in:pending,picked_up,in_transit,delivered,failed',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Đơn hàng không được để trống.',
            'order_id.exists'   => 'Đơn hàng không tồn tại.',
        ];
    }
}
