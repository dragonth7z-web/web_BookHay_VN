<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('order')->paginate(10);
        return view('admin.faq.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'order' => 'nullable|integer',
            'is_visible' => 'nullable|boolean',
        ]);

        $faq = Faq::create($validated);

        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Thêm FAQ mới: ' . Str::limit($faq->question, 50),
            level: 'info',
            objectType: 'Faq',
            objectId: $faq->id
        );

        return redirect()->route('admin.faq.index')
            ->with('success', 'Thêm FAQ thành công!');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faq.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'order' => 'nullable|integer',
            'is_visible' => 'nullable|boolean',
        ]);

        $faq->update($validated);

        SystemLog::ghi(
            type: 'data',
            action: 'update',
            description: 'Cập nhật FAQ: ' . Str::limit($faq->question, 50),
            level: 'info',
            objectType: 'Faq',
            objectId: $faq->id
        );

        return redirect()->route('admin.faq.index')
            ->with('success', 'Cập nhật FAQ thành công!');
    }

    public function destroy(Faq $faq)
    {
        $id = $faq->id;
        $question = Str::limit($faq->question, 50);
        $faq->delete();

        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: 'Xóa FAQ: ' . $question,
            level: 'warning',
            objectType: 'Faq',
            objectId: $id
        );

        return redirect()->route('admin.faq.index')
            ->with('success', 'Xóa FAQ thành công!');
    }
}
