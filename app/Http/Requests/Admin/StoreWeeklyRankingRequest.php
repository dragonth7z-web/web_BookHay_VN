<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreWeeklyRankingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_ranking' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'items' => 'required|array',
            'items.*.book_id' => 'nullable|exists:books,id',
        ];
    }

    public function selectedBooks(): \Illuminate\Support\Collection
    {
        return collect($this->input('items', []))
            ->map(fn($x) => $x['book_id'] ?? null)
            ->filter()
            ->values();
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $selected = $this->selectedBooks();

            if ($selected->isEmpty()) {
                $v->errors()->add('items', 'Vui lòng chọn ít nhất 1 sách cho bảng xếp hạng.');
            } elseif ($selected->unique()->count() !== $selected->count()) {
                $v->errors()->add('items', 'Mỗi sách chỉ được chọn 1 lần trong cùng một bảng xếp hạng.');
            }
        });
    }
}
