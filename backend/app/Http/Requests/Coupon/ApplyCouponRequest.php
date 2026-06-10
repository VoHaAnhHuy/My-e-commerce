<?php

namespace App\Http\Requests\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|exists:coupons,code',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Mã giảm giá không được để trống.',
            'code.exists'   => 'Mã giảm giá không tồn tại.',
        ];
    }
}
