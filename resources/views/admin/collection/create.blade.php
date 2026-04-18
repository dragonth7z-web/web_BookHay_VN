@extends('layouts.admin')

@section('title', 'Thêm Bộ Sưu Tập')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.collections.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="admin-card overflow-hidden">
            <div class="p-6 border-b border-[var(--admin-border)] flex items-center justify-between bg-[var(--admin-surface-muted)]">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">add_circle</span> Thông Tin Bộ Sưu Tập
                </h3>
                <a href="{{ route('admin.collections.index') }}" class="text-xs font-bold text-slate-500 hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span> Quay lại
                </a>
            </div>
            
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Tiêu đề Bộ sưu tập <span class="text-primary">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="admin-input w-full"
                            placeholder="VD: Flash Sale Giữa Năm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Huy hiệu (Badge)</label>
                        <input type="text" name="badge" value="{{ old('badge') }}"
                            class="admin-input w-full"
                            placeholder="VD: HOT, MỚI, SALE">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Chú thích / Mô tả ngắn</label>
                    <input type="text" name="description" value="{{ old('description') }}"
                        class="admin-input w-full"
                        placeholder="VD: Tổng hợp các đầu sách giảm giá đến 50%">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Đường dẫn (Link)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}"
                            class="admin-input w-full"
                            placeholder="VD: /danh-muc/van-hoc">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Thứ tự hiển thị</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                            class="admin-input w-full">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Hình ảnh Bộ sưu tập <span class="text-primary">*</span></label>
                    <div class="mt-1 border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-2xl p-8 text-center hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <input type="file" name="image" required class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-primary/10 file:text-primary
                            hover:file:bg-primary/20 transition-all cursor-pointer">
                        <p class="mt-2 text-xs text-slate-400">Kích thước khuyên dùng: 400x250px (Tỉ lệ 8:5)</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-4">
                    <input type="hidden" name="is_visible" value="0">
                    <input type="checkbox" name="is_visible" value="1" id="is_visible" checked 
                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary">
                    <label for="is_visible" class="text-sm font-bold text-gray-700 dark:text-gray-300">Công khai (Hiển thị lên trang chủ)</label>
                </div>
            </div>

            <div class="p-6 bg-[var(--admin-surface-muted)] border-t border-[var(--admin-border)] text-right">
                <button type="submit" class="admin-btn-primary">
                    Lưu Bộ Sưu Tập
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

