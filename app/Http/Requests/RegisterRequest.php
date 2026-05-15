<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    const TEMP_EMAIL_DOMAINS = [
        '10minutemail.com', 'guerrillamail.com', 'mailinator.com',
        'tempmail.com', 'throwaway.email', 'yopmail.com',
        'sharklasers.com', 'guerrillamailblock.com', 'grr.la',
        'spam4.me', 'trashmail.com', 'dispostable.com',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ho_ten'        => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\p{L}\s]+$/u'],
            'email'         => ['required', 'email:rfc', 'unique:users,email', 'max:255'],
            'password'      => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()   // Có cả chữ hoa và chữ thường
                    ->numbers()     // Có ít nhất 1 chữ số
                    ->symbols(),    // Có ít nhất 1 ký tự đặc biệt
            ],
            'so_dien_thoai' => ['nullable', 'regex:/^(03|05|07|08|09)\d{8}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'ho_ten.required'       => 'Vui lòng nhập họ và tên.',
            'ho_ten.min'            => 'Họ và tên phải có ít nhất 3 ký tự.',
            'ho_ten.regex'          => 'Họ và tên không được chứa ký tự đặc biệt hoặc chữ số.',
            'email.required'        => 'Vui lòng nhập địa chỉ email.',
            'email.email'           => 'Địa chỉ email không hợp lệ.',
            'email.unique'          => 'Email này đã được sử dụng.',
            'password.required'     => 'Vui lòng nhập mật khẩu.',
            'password.min'          => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed'    => 'Mật khẩu xác nhận không khớp.',
            'password.mixed_case'   => 'Mật khẩu phải có cả chữ hoa và chữ thường.',
            'password.numbers'      => 'Mật khẩu phải chứa ít nhất 1 chữ số.',
            'password.symbols'      => 'Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt (!@#$...).',
            'so_dien_thoai.regex'   => 'Số điện thoại không hợp lệ. Phải bắt đầu bằng 03, 05, 07, 08 hoặc 09 và có đúng 10 chữ số.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ho_ten'        => trim($this->ho_ten ?? ''),
            'email'         => strtolower(trim($this->email ?? '')),
            'so_dien_thoai' => trim($this->so_dien_thoai ?? '') ?: null,
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $email = strtolower(trim($this->email ?? ''));
            $parts = explode('@', $email);

            if (count($parts) === 2) {
                $domain = $parts[1];
                if (in_array($domain, self::TEMP_EMAIL_DOMAINS)) {
                    $validator->errors()->add('email', 'Không chấp nhận email tạm thời.');
                }
            }
        });
    }
}
