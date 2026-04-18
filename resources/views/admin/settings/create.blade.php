@extends('layouts.admin')

@section('title', 'Thêm cấu hình mới')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <div class="mb-8 flex items-center gap-3">
        <a href="{{ route('admin.settings.index') }}" class="p-2 text-gray-400 hover:text-primary bg-white rounded-xl shadow-sm border border-gray-100 transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Thêm cấu hình mới</h1>
            <p class="text-sm text-gray-500 mt-1">Định nghĩa một tham số mới cho hệ thống nội dung trang chủ.</p>
        </div>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="p-8">
            <form action="{{ route('admin.settings.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Từ khóa (Key)</label>
                    <input type="text" name="key" value="{{ old('key') }}" 
                           class="admin-input w-full font-mono"
                           placeholder="Ví dụ: home_featured_limit...">
                    <p class="text-[11px] text-gray-400 mt-1 font-medium italic">* Phải bắt đầu bằng chữ cái, không chứa khoảng trắng (sử dụng dấu gạch dưới _).</p>
                    @error('key')
                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Giá trị (Value)</label>
                    <textarea name="value" rows="4" class="admin-input w-full" placeholder="Nhập giá trị cấu hình...">{{ old('value') }}</textarea>
                    @error('value')
                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Mô tả (Description)</label>
                    <input type="text" name="description" value="{{ old('description') }}" 
                           class="admin-input w-full"
                           placeholder="Mô tả tham số này dùng để trang chủ làm gì...">
                    @error('description')
                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="admin-btn-primary flex-1 py-4 justify-center">
                        Tạo cấu hình
                    </button>
                    <a href="{{ route('admin.settings.index') }}" class="px-8 py-4 bg-gray-50 text-gray-500 rounded-2xl font-bold hover:bg-gray-100 transition-all">
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

