<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'button_text' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|url|max:500',
            'url' => 'nullable|string|max:255',
            'position' => 'required|in:home_main,home_mini,home_gift,Slider,Sidebar,Footer',
            'sort_order' => 'nullable|integer',
            'is_visible' => 'nullable|boolean',
        ];
    }
}
