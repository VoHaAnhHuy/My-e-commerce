<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'order_id'   => 'nullable|integer|exists:orders,id',
            'rating'     => 'required|integer|min:1|max:5',
            'title'      => 'nullable|string|max:255',
            'comment'    => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Sản phẩm không được để trống.',
            'product_id.exists'   => 'Sản phẩm không tồn tại.',
            'rating.required'     => 'Đánh giá không được để trống.',
            'rating.min'          => 'Đánh giá tối thiểu là 1 sao.',
            'rating.max'          => 'Đánh giá tối đa là 5 sao.',
        ];
    }
}
