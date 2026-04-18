<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeaturedWorksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'page_ids' => 'required|array',
            'page_ids.*' => 'integer|exists:books,id',
            'featured_ids' => 'nullable|array',
            'featured_ids.*' => 'integer|exists:books,id',
        ];
    }
}
