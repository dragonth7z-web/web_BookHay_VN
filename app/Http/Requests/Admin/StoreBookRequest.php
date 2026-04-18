<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bookId = $this->route('book')?->id;

        return [
            'sku' => "required|string|max:30|unique:books,sku,{$bookId}",
            'title' => 'required|string|max:255',
            'slug' => "required|string|max:255|unique:books,slug,{$bookId}",
            'cost_price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'cover_type' => 'nullable|in:hardcover,paperback',
            'status' => 'nullable|in:in_stock,out_of_stock,discontinued',
            'author_ids' => 'nullable|array',
            'author_ids.*' => 'exists:authors,id',
            'cover_image' => 'nullable|image|max:2048',
        ];
    }
}
