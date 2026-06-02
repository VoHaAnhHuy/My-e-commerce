<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Xác định ai được phép gửi request này
     * true = tất cả (không cần đăng nhập)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules validate cho đăng ký
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    /**
     * Custom thông báo lỗi
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống.',
            'name.unique' => 'Tên đã được sử dụng.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
        ];
    }
}
