@extends('layouts.admin')

@section('title', 'Sửa Danh mục')

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf @method('PATCH')
        <div class="admin-card overflow-hidden">
            <div class="p-6 border-b border-[var(--admin-border)] flex items-center justify-between bg-[var(--admin-surface-muted)]">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">edit</span>Sửa Danh Mục #{{ $category->id }}
                </h3>
                <a href="{{ route('admin.categories.index') }}" class="text-xs font-bold text-slate-500 hover:text-primary flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>Quay lại
                </a>
            </div>

            <div class="p-6 space-y-5">
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Tên danh mục <span class="text-primary">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                        class="admin-input w-full">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Slug (URL)</label>
                    <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                        class="admin-input w-full">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Danh mục cha</label>
                    <select name="parent_id" class="admin-input w-full">
                        <option value="">— Là danh mục gốc —</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Icon (Material Symbols)</label>
                    <input type="text" name="image" value="{{ old('image', $category->image_url) }}"
                        class="admin-input w-full"
                        placeholder="VD: menu_book, auto_stories, science...">
                    @if($category->image_url)
                    <p class="text-xs text-slate-400 mt-1">Hiện tại: <span class="font-mono text-primary">{{ $category->image_url }}</span></p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Thứ tự hiển thị</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}"
                            class="admin-input w-full">
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_visible" value="0">
                            <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', $category->is_visible ?? 1) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Hiển thị</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-[var(--admin-surface-muted)] border-t border-[var(--admin-border)] flex items-center justify-between">
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                    onsubmit="return confirm('Xóa danh mục này?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 text-red-600 border border-red-200 rounded-xl text-sm font-bold hover:bg-red-50 transition-all">
                        Xóa danh mục
                    </button>
                </form>
                <button type="submit" class="admin-btn-primary">
                    Cập Nhật
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

