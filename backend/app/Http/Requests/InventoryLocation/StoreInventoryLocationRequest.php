<?php

namespace App\Http\Requests\InventoryLocation;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryLocationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'code'    => 'required|string|max:50|unique:inventory_locations,code',
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Mã kho không được để trống.',
            'code.unique'   => 'Mã kho đã tồn tại.',
            'name.required' => 'Tên kho không được để trống.',
        ];
    }
}
