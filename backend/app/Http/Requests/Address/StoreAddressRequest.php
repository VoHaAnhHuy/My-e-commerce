<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_name' => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'province'      => 'required|string|max:100',
            'district'      => 'required|string|max:100',
            'ward'          => 'nullable|string|max:100',
            'address_text'  => 'required|string|max:255',
            'is_default'    => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_name.required' => 'Tên người nhận không được để trống.',
            'receiver_name.max'      => 'Tên người nhận không được vượt quá 255 ký tự.',
            'phone.required'         => 'Số điện thoại không được để trống.',
            'phone.max'              => 'Số điện thoại không được vượt quá 20 ký tự.',
            'province.required'      => 'Tỉnh/Thành phố không được để trống.',
            'district.required'      => 'Quận/Huyện không được để trống.',
            'ward.max'               => 'Phường/Xã không được vượt quá 100 ký tự.',
            'address_text.required'  => 'Địa chỉ không được để trống.',
            'address_text.max'       => 'Địa chỉ không được vượt quá 255 ký tự.',
            'is_default.boolean'     => 'Giá trị mặc định phải là true hoặc false.',
        ];
    }
}
