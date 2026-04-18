@extends('layouts.admin')

@section('title', 'Chỉnh sửa cấu hình')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <div class="mb-8 flex items-center gap-3">
        <a href="{{ route('admin.settings.index') }}" class="p-2 text-gray-400 hover:text-primary bg-white rounded-xl shadow-sm border border-gray-100 transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chỉnh sửa cấu hình</h1>
            <p class="text-sm text-gray-500 mt-1">Thay đổi giá trị cho khóa: <span class="font-mono text-primary font-bold">{{ $setting->key }}</span></p>
        </div>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="p-8">
            <form action="{{ route('admin.settings.update', $setting->key) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Giá trị (Value)</label>
                    <textarea name="value" rows="4" class="admin-input w-full" placeholder="Nhập giá trị cấu hình...">{{ old('value', $setting->value) }}</textarea>
                    @error('value')
                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Mô tả (Description)</label>
                    <input type="text" name="description" value="{{ old('description', $setting->description) }}" 
                           class="admin-input w-full"
                           placeholder="Mô tả ngắn gọn về cấu hình này...">
                    @error('description')
                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="admin-btn-primary flex-1 py-4 justify-center">
                        Cập nhật cấu hình
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

