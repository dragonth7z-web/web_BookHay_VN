<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_address' => 'required|string|max:500',
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cod,vnpay,momo,bank_transfer',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hàng.',
            'recipient_name.required' => 'Vui lòng nhập tên người nhận.',
            'recipient_phone.required' => 'Vui lòng nhập số điện thoại nhận hàng.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
        ];
    }
}
