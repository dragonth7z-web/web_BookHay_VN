@extends('layouts.admin')

@section('title', 'Sửa Banner')

@section('content')
<form action="{{ route('admin.banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2 space-y-6">
            <div class="admin-card overflow-hidden">
                <div class="p-6 border-b border-[var(--admin-border)] flex items-center gap-2 bg-[var(--admin-surface-muted)]">
                    <span class="material-symbols-outlined text-primary text-[20px]">info</span>
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">Thông Tin Banner</h3>
                </div>

                <div class="p-6 space-y-5">
                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                    </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Tiêu Đề</label>
                        <input type="text" name="title" value="{{ old('title', $banner->title) }}"
                            class="admin-input w-full" placeholder="Ví dụ: Tháng Mới - Ưu Đãi Mới">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Mô Tả (Hiển thị dưới tiêu đề)</label>
                        <textarea name="description" rows="2" class="admin-input w-full" placeholder="VD: Khám phá hàng ngàn tựa sách mới nhất...">{{ old('description', $banner->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Text Nhãn (Badge)</label>
                            <input type="text" name="badge_text" value="{{ old('badge_text', $banner->badge_text) }}"
                                class="admin-input w-full" placeholder="VD: Flash Sale">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Text Nút Bấm</label>
                            <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}"
                                class="admin-input w-full" placeholder="VD: Mua Ngay">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Hình Ảnh Banner</label>
                        @if($banner->image)
                        <div class="mb-3 relative group">
                            <img src="{{ filter_var($banner->image, FILTER_VALIDATE_URL) ? $banner->image : asset('storage/' . $banner->image) }}"
                                 alt="Ảnh hiện tại" class="w-full h-32 object-cover rounded-xl border border-[var(--admin-border)]">
                            <div class="absolute inset-0 bg-black/40 rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-xs font-bold">Ảnh hiện tại</span>
                            </div>
                        </div>
                        @endif
                        <div class="space-y-3">
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-[var(--admin-border)] rounded-xl cursor-pointer bg-[var(--admin-surface-muted)] hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-all">
                                <div class="flex flex-col items-center justify-center py-2">
                                    <span class="material-symbols-outlined text-slate-400 text-2xl mb-1">cloud_upload</span>
                                    <p class="text-xs text-slate-500"><span class="font-bold text-primary">Thay ảnh mới</span> (bỏ trống để giữ ảnh cũ)</p>
                                </div>
                                <input type="file" name="image" class="hidden" accept="image/*">
                            </label>
                            <div class="flex items-center gap-2">
                                <div class="h-px flex-1 bg-[var(--admin-border)]"></div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase">hoặc nhập URL mới</span>
                                <div class="h-px flex-1 bg-[var(--admin-border)]"></div>
                            </div>
                            <input type="text" name="image_url" value=""
                                class="admin-input w-full" placeholder="https://example.com/new-banner.jpg">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Đường Link Đích</label>
                        <input type="text" name="url" value="{{ old('url', $banner->url) }}"
                            class="admin-input w-full" placeholder="/search hoặc https://...">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="admin-card overflow-hidden">
                <div class="p-6 border-b border-[var(--admin-border)] flex items-center gap-2 bg-[var(--admin-surface-muted)]">
                    <span class="material-symbols-outlined text-primary text-[20px]">settings</span>
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">Hiển Thị</h3>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Vị Trí <span class="text-primary">*</span></label>
                        <select name="position" required class="admin-input w-full">
                            <option value="home_main" {{ old('position', $banner->position) == 'home_main' ? 'selected' : '' }}>Trang Chủ - Hero Slider</option>
                            <option value="home_mini" {{ old('position', $banner->position) == 'home_mini' ? 'selected' : '' }}>Trang Chủ - Banner Nhỏ</option>
                            <option value="home_gift" {{ old('position', $banner->position) == 'home_gift' ? 'selected' : '' }}>Trang Chủ - Card Quà Tặng</option>
                            <option value="Slider" {{ old('position', $banner->position) == 'Slider' ? 'selected' : '' }}>Slider Cũ</option>
                            <option value="Sidebar" {{ old('position', $banner->position) == 'Sidebar' ? 'selected' : '' }}>Sidebar Cũ</option>
                            <option value="Footer" {{ old('position', $banner->position) == 'Footer' ? 'selected' : '' }}>Footer Cũ</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Thứ tự hiển thị</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}"
                            class="admin-input w-full">
                    </div>

                    <div class="flex items-center justify-between p-3 bg-[var(--admin-surface-muted)] rounded-xl border border-[var(--admin-border)]">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Công khai (Hiển thị)</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_visible" value="0">
                            <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', $banner->is_visible) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>

                <div class="p-6 bg-[var(--admin-surface-muted)] border-t border-[var(--admin-border)] text-right">
                    <button type="submit" class="admin-btn-primary w-full">
                        Cập Nhật Banner
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
