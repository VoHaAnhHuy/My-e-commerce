<?php

namespace App\Http\Requests\InventoryLocation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInventoryLocationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'code' => [
                'sometimes', 'required', 'string', 'max:50',
                Rule::unique('inventory_locations', 'code')->ignore($this->route('inventory_location')),
            ],
            'name'    => 'sometimes|required|string|max:255',
            'address' => 'nullable|string|max:500',
        ];
    }
}
