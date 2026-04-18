@extends('layouts.admin')

@section('title', 'Quản lý Bộ sưu tập')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Quản lý Bộ Sưu Tập</h1>
            <div class="flex items-center gap-2 text-sm text-slate-500 mt-1">
                <a class="hover:text-primary" href="{{ route('admin.dashboard') }}">Trang chủ</a>
                <span>›</span>
                <span>Collections</span>
            </div>
        </div>
        <a href="{{ route('admin.collections.create') }}"
            class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[20px]">add_circle</span>
            Thêm Bộ sưu tập mới
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2">
        <span class="material-symbols-outlined text-green-500">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    <!-- Table -->
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Bộ sưu tập</th>
                        <th>Huy hiệu</th>
                        <th class="text-center">Thứ tự</th>
                        <th>Trạng thái</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($collections as $bst)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-20 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-slate-100">
                                    <img src="{{ asset('storage/' . $bst->image) }}" class="w-full h-full object-cover" alt="">
                                </div>
                                <div class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $bst->title }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($bst->badge)
                                <span class="bg-primary/10 text-primary text-[10px] px-2 py-0.5 rounded font-black uppercase tracking-wider">
                                    {{ $bst->badge }}
                                </span>
                            @else
                                <span class="text-slate-300 text-xs italic">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium text-slate-600">{{ $bst->sort_order }}</td>
                        <td class="px-6 py-4">
                            @if($bst->is_visible)
                                <span class="inline-flex items-center gap-1 text-green-600 text-xs font-bold">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>Hiện
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-slate-400 text-xs font-bold">
                                    <span class="w-2 h-2 rounded-full bg-slate-300"></span>Ẩn
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.collections.edit', $bst->id) }}"
                                    class="p-2 text-primary hover:bg-red-50 rounded-lg transition-colors" title="Sửa">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('admin.collections.destroy', $bst->id) }}" method="POST"
                                    onsubmit="return confirm('Xác nhận xóa bộ sưu tập này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            Chưa có bộ sưu tập nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

