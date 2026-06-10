<?php

namespace App\Http\Requests\Refund;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRefundRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:pending,approved,rejected,completed',
            'reason' => 'nullable|string|max:2000',
        ];
    }
}
