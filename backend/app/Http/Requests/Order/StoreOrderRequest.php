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
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'coupon_code'       => 'nullable|string|exists:coupons,code',
            'shipping_name'     => 'required|string|max:255',
            'shipping_phone'    => 'required|string|max:20',
            'shipping_address'  => 'required|string|max:255',
            'shipping_ward'     => 'required|string|max:100',
            'shipping_district' => 'required|string|max:100',
            'shipping_city'     => 'required|string|max:100',
            'note'              => 'nullable|string|max:500',
            'items'             => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|integer|exists:product_variants,id',
            'items.*.quantity'           => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method_id.required' => 'Phương thức thanh toán không được để trống.',
            'payment_method_id.exists'   => 'Phương thức thanh toán không tồn tại.',
            'coupon_code.exists'         => 'Mã giảm giá không tồn tại.',
            'shipping_name.required'     => 'Tên người nhận không được để trống.',
            'shipping_phone.required'    => 'Số điện thoại không được để trống.',
            'shipping_address.required'  => 'Địa chỉ giao hàng không được để trống.',
            'shipping_ward.required'     => 'Phường/Xã không được để trống.',
            'shipping_district.required' => 'Quận/Huyện không được để trống.',
            'shipping_city.required'     => 'Tỉnh/Thành phố không được để trống.',
            'items.required'             => 'Đơn hàng phải có ít nhất 1 sản phẩm.',
            'items.min'                  => 'Đơn hàng phải có ít nhất 1 sản phẩm.',
            'items.*.product_variant_id.required' => 'Biến thể sản phẩm không được để trống.',
            'items.*.product_variant_id.exists'   => 'Biến thể sản phẩm không tồn tại.',
            'items.*.quantity.required'  => 'Số lượng không được để trống.',
            'items.*.quantity.min'       => 'Số lượng phải ít nhất là 1.',
        ];
    }
}
