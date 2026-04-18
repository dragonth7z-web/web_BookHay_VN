@extends('layouts.admin')

@section('title', 'Thêm Combo Mới')

@section('content')
<form action="{{ route('admin.combo.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2 space-y-6">
            <!-- Thông tin cơ bản -->
            <div class="admin-card overflow-hidden">
                <div class="p-6 border-b border-[var(--admin-border)] bg-[var(--admin-surface-muted)] flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">info</span>
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">Thông Tin Cơ Bản</h3>
                </div>
                
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Tên Combo <span class="text-primary">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="admin-input w-full" placeholder="Ví dụ: Combo Kỹ Năng Sống 2024">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Mô tả ngắn</label>
                        <textarea name="description" class="admin-input w-full h-32" placeholder="VD: Mua kèm giảm sốc - Chỉ hôm nay!">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Sách trong Combo -->
            <div class="admin-card overflow-hidden">
                <div class="p-6 border-b border-[var(--admin-border)] bg-[var(--admin-surface-muted)] flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">library_books</span>
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">Chọn Sách Trong Combo</h3>
                </div>
                
                <div class="max-h-[400px] overflow-y-auto space-y-2 pr-2 custom-scrollbar p-6">
                    @foreach($books as $book)
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-all cursor-pointer group">
                        <input type="checkbox" name="ids_books[]" value="{{ $book->id }}" 
                            class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary transition-all">
                        <div class="w-10 h-14 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-bold text-gray-800 dark:text-gray-200 truncate group-hover:text-primary transition-colors">{{ $book->title }}</div>
                            <div class="text-xs text-gray-500">{{ number_format($book->sale_price, 0, ',', '.') }}đ</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Cột phụ -->
        <div class="space-y-6">
            <!-- Giá & Trạng thái -->
            <div class="admin-card overflow-hidden">
                <div class="p-6 border-b border-[var(--admin-border)] bg-[var(--admin-surface-muted)] flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">sell</span>
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">Giá & Hiển Thị</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Giá Gốc</label>
                            <input type="number" name="original_price" value="{{ old('original_price', 0) }}"
                                class="admin-input w-full">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Giá Combo</label>
                            <input type="number" name="sale_price" value="{{ old('sale_price', 0) }}"
                                class="admin-input w-full">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Thứ tự hiển thị</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                            class="admin-input w-full">
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700/30 rounded-xl border border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Công khai (Hiển thị)</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_visible" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Giao diện (Màu & Icon) -->
            <div class="admin-card overflow-hidden">
                <div class="p-6 border-b border-[var(--admin-border)] bg-[var(--admin-surface-muted)] flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">palette</span>
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">Giao Diện</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Màu nền từ</label>
                            <input type="color" name="bg_from" value="{{ old('bg_from', '#4F46E5') }}"
                                class="w-full h-10 rounded-lg cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Màu nền đến</label>
                            <input type="color" name="bg_to" value="{{ old('bg_to', '#7C3AED') }}"
                                class="w-full h-10 rounded-lg cursor-pointer">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Icon (Google Material Symbols)</label>
                        <input type="text" name="icon" value="{{ old('icon', 'psychology') }}"
                            class="admin-input w-full" placeholder="VD: local_fire_department">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Ảnh Combo (Tùy chọn)</label>
                        <input type="file" name="image" class="w-full text-xs dark:text-gray-400">
                    </div>
                </div>
            </div>

            <button type="submit" class="admin-btn-primary w-full py-4 justify-center uppercase tracking-wide">
                Lưu Combo Deal
            </button>
        </div>
    </div>
</form>
@endsection

