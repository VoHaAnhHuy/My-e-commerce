<?php

namespace App\Http\Requests\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'             => 'required|string|max:50|unique:coupons,code',
            'type'             => 'required|string|in:fixed,percentage',
            'value'            => 'required|numeric|min:0',
            'max_discount'     => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
            'usage_per_user'   => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'starts_at'        => 'nullable|date',
            'expires_at'       => 'nullable|date|after_or_equal:starts_at',
            'is_active'        => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'             => 'Mã giảm giá không được để trống.',
            'code.unique'               => 'Mã giảm giá đã tồn tại.',
            'code.max'                  => 'Mã giảm giá không được vượt quá 50 ký tự.',
            'type.required'             => 'Loại giảm giá không được để trống.',
            'type.in'                   => 'Loại giảm giá phải là "fixed" hoặc "percentage".',
            'value.required'            => 'Giá trị giảm giá không được để trống.',
            'value.min'                 => 'Giá trị giảm giá không được nhỏ hơn 0.',
            'usage_per_user.integer'    => 'Giới hạn sử dụng mỗi user phải là số nguyên.',
            'usage_per_user.min'        => 'Giới hạn sử dụng mỗi user phải ít nhất là 1.',
            'expires_at.after_or_equal' => 'Ngày hết hạn phải sau hoặc bằng ngày bắt đầu.',
            'is_active.boolean'         => 'Trạng thái phải là true hoặc false.',
        ];
    }
}
