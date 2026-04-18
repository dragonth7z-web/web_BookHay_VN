@extends('layouts.admin')

@section('title', 'Cấu hình hệ thống')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Cấu hình hệ thống</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý các tham số hiển thị và thông tin cơ bản của website.</p>
        </div>
        <a href="{{ route('admin.settings.create') }}" class="admin-btn-primary">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Thêm cấu hình mới
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 animate-fade-in-down">
            <span class="material-symbols-outlined">check_circle</span>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Từ khóa (Key)</th>
                        <th>Giá trị</th>
                        <th>Mô tả</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($settings as $setting)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm text-[#C92127] bg-red-50 px-2 py-1 rounded">{{ $setting->key }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs truncate font-medium text-gray-800" title="{{ $setting->value }}">
                                    {{ $setting->value }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $setting->description ?? 'Không có mô tả' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.settings.edit', $setting->key) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Chỉnh sửa">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.settings.destroy', $setting->key) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa cấu hình này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                                Chưa có cấu hình nào được thiết lập.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

