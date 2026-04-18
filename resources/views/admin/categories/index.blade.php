@extends('layouts.admin')

@section('title', 'Quản lý Danh mục')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Quản lý Danh mục</h1>
            <div class="flex items-center gap-2 text-sm text-slate-500 mt-1">
                <a class="hover:text-primary" href="{{ route('admin.dashboard') }}">Trang chủ</a>
                <span>›</span><span>Danh mục sách</span>
            </div>
        </div>
        <a href="{{ route('admin.categories.create') }}"
            class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[20px]">add_circle</span>Thêm danh mục mới
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2">
        <span class="material-symbols-outlined text-green-500">check_circle</span>{{ session('success') }}
    </div>
    @endif

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Phân cấp</th>
                        <th class="text-center">Thứ tự</th>
                        <th>Trạng thái</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($categories as $cat)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-6 py-4 text-sm text-slate-500 font-medium">#{{ $cat->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-slate-400 text-[20px]">{{ $cat->icon ?: 'folder_open' }}</span>
                                <span class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $cat->name }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-500">Gốc</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">{{ $cat->children->count() }} danh mục con</td>
                        <td class="px-6 py-4 text-center text-sm font-medium text-slate-600">{{ $cat->sort_order ?? 0 }}</td>
                        <td class="px-6 py-4">
                            @if($cat->is_visible ?? true)
                                <span class="inline-flex items-center gap-1 text-green-600 text-xs font-bold"><span class="w-2 h-2 rounded-full bg-green-500"></span>Hiển thị</span>
                            @else
                                <span class="inline-flex items-center gap-1 text-slate-400 text-xs font-bold"><span class="w-2 h-2 rounded-full bg-slate-300"></span>Ẩn</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.categories.edit', $cat->id) }}" class="p-2 text-primary hover:bg-red-50 rounded-lg transition-colors" title="Sửa">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Xóa danh mục này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @foreach($cat->children as $child)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group bg-slate-50/30">
                        <td class="px-6 py-3 text-sm text-slate-400">#{{ $child->id }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2 pl-8">
                                <span class="text-slate-300 text-[14px]">↳</span>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $child->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-xs text-slate-400 italic">Con của: {{ $cat->name }}</td>
                        <td class="px-6 py-3 text-center text-sm text-slate-500">{{ $child->sort_order ?? 0 }}</td>
                        <td class="px-6 py-3">
                            @if($child->is_visible ?? true)
                                <span class="inline-flex items-center gap-1 text-green-600 text-xs font-bold"><span class="w-2 h-2 rounded-full bg-green-500"></span>Hiển thị</span>
                            @else
                                <span class="inline-flex items-center gap-1 text-slate-400 text-xs font-bold"><span class="w-2 h-2 rounded-full bg-slate-300"></span>Ẩn</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.categories.edit', $child->id) }}" class="p-2 text-primary hover:bg-red-50 rounded-lg transition-colors" title="Sửa">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Xóa danh mục con này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            Chưa có danh mục nào. <a href="{{ route('admin.categories.create') }}" class="text-primary font-bold hover:underline">Thêm ngay</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
