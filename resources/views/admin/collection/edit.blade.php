@extends('layouts.admin')

@section('title', 'Sửa Bộ Sưu Tập')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.collections.update', $collection->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="admin-card overflow-hidden">
            <div class="p-6 border-b border-[var(--admin-border)] flex items-center justify-between bg-[var(--admin-surface-muted)]">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">edit</span> Sửa Bộ Sưu Tập #{{ $collection->id }}
                </h3>
                <a href="{{ route('admin.collections.index') }}" class="text-xs font-bold text-slate-500 hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span> Quay lại
                </a>
            </div>
            
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Tiêu đề Bộ sưu tập <span class="text-primary">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $collection->title) }}" required
                            class="admin-input w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Huy hiệu (Badge)</label>
                        <input type="text" name="badge" value="{{ old('badge', $collection->badge) }}"
                            class="admin-input w-full">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Chú thích / Mô tả ngắn</label>
                    <input type="text" name="description" value="{{ old('description', $collection->description) }}"
                        class="admin-input w-full">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Đường dẫn (Link)</label>
                        <input type="text" name="slug" value="{{ old('slug', $collection->slug) }}"
                            class="admin-input w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Thứ tự hiển thị</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $collection->sort_order) }}"
                            class="admin-input w-full">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Hình ảnh hiện tại</label>
                    <div class="mt-2 mb-4 w-40 h-24 rounded-xl overflow-hidden border border-gray-100">
                        <img src="{{ asset('storage/' . $collection->image) }}" class="w-full h-full object-cover">
                    </div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Thay đổi hình ảnh</label>
                    <div class="mt-1 border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-2xl p-6 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <input type="file" name="image" class="block w-full text-sm text-slate-500 cursor-pointer">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-4">
                    <input type="hidden" name="is_visible" value="0">
                    <input type="checkbox" name="is_visible" value="1" id="is_visible" {{ $collection->is_visible ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary">
                    <label for="is_visible" class="text-sm font-bold text-gray-700 dark:text-gray-300">Công khai (Hiển thị lên trang chủ)</label>
                </div>
            </div>

            <div class="p-6 bg-[var(--admin-surface-muted)] border-t border-[var(--admin-border)] text-right">
                <button type="submit" class="admin-btn-primary">
                    Cập Nhật Bộ Sưu Tập
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

