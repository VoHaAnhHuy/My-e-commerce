<?php

namespace App\Http\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'carrier'       => 'nullable|string|max:255',
            'tracking_code' => 'nullable|string|max:255',
            'shipping_fee'  => 'sometimes|numeric|min:0',
            'status'        => 'sometimes|string|in:pending,picked_up,in_transit,delivered,failed',
        ];
    }
}
