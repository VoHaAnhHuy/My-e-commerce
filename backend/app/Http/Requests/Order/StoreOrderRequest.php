<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coupon_code'    => 'nullable|string|exists:coupons,code',
            'shipping_address' => 'required|array',
            'shipping_address.receiver_name' => 'required|string|max:255',
            'shipping_address.phone'         => 'required|string|max:20',
            'shipping_address.province'      => 'required|string|max:100',
            'shipping_address.district'      => 'required|string|max:100',
            'shipping_address.ward'          => 'nullable|string|max:100',
            'shipping_address.address_text'  => 'required|string|max:255',
            'items'             => 'required|array|min:1',
            'items.*.variant_id'  => 'required|integer|exists:product_variants,id',
            'items.*.qty'         => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'coupon_code.exists'         => 'Mã giảm giá không tồn tại.',
            'shipping_address.required'  => 'Địa chỉ giao hàng không được để trống.',
            'shipping_address.receiver_name.required' => 'Tên người nhận không được để trống.',
            'shipping_address.phone.required'    => 'Số điện thoại không được để trống.',
            'shipping_address.province.required' => 'Tỉnh/Thành phố không được để trống.',
            'shipping_address.district.required' => 'Quận/Huyện không được để trống.',
            'shipping_address.address_text.required' => 'Địa chỉ không được để trống.',
            'items.required'             => 'Đơn hàng phải có ít nhất 1 sản phẩm.',
            'items.min'                  => 'Đơn hàng phải có ít nhất 1 sản phẩm.',
            'items.*.variant_id.required' => 'Biến thể sản phẩm không được để trống.',
            'items.*.variant_id.exists'   => 'Biến thể sản phẩm không tồn tại.',
            'items.*.qty.required'  => 'Số lượng không được để trống.',
            'items.*.qty.min'       => 'Số lượng phải ít nhất là 1.',
        ];
    }
}
