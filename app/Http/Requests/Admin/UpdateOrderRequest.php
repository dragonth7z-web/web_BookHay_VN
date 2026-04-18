<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,confirmed,shipping,delivered,completed,cancelled,returned',
            'payment_status' => 'required|in:unpaid,paid,refunded',
            'cancel_reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
