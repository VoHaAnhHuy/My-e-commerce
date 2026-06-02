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
            'label'          => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address_line'   => 'required|string|max:255',
            'ward'           => 'required|string|max:100',
            'district'       => 'required|string|max:100',
            'city'           => 'required|string|max:100',
            'is_default'     => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_name.required' => 'Tên người nhận không được để trống.',
            'recipient_name.max'      => 'Tên người nhận không được vượt quá 255 ký tự.',
            'phone.required'          => 'Số điện thoại không được để trống.',
            'phone.max'               => 'Số điện thoại không được vượt quá 20 ký tự.',
            'address_line.required'   => 'Địa chỉ không được để trống.',
            'address_line.max'        => 'Địa chỉ không được vượt quá 255 ký tự.',
            'ward.required'           => 'Phường/Xã không được để trống.',
            'district.required'       => 'Quận/Huyện không được để trống.',
            'city.required'           => 'Tỉnh/Thành phố không được để trống.',
            'is_default.boolean'      => 'Giá trị mặc định phải là true hoặc false.',
        ];
    }
}
