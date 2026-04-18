@extends('layouts.admin')

@section('title', 'Quản lý Bảng Xếp Hạng Tuần')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Quản lý Bảng Xếp Hạng Tuần</h1>
            <div class="flex items-center gap-2 text-sm text-slate-500 mt-1">
                <a class="hover:text-primary" href="{{ route('admin.dashboard') }}">Trang chủ</a>
                <span>›</span>
                <span>Bảng xếp hạng tuần</span>
            </div>
        </div>
        <a href="{{ route('admin.weekly-rankings.create') }}"
           class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[20px]">add_circle</span>
            Tạo bảng mới
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-green-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tuần</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Số sách</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @php $today = now()->toDateString(); @endphp
                    @forelse($rankings as $ranking)
                        @php
                            $isActive = $ranking->week_start <= $today && $ranking->week_end >= $today;
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-900 dark:text-slate-100">
                                    {{ $ranking->ten_tuan ?? 'Không có tiêu đề' }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $ranking->week_start->format('d/m/Y') }} - {{ $ranking->week_end->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($isActive)
                                    <span class="inline-flex items-center gap-1 text-green-600 text-xs font-bold">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>Đang hiển thị
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-slate-400 text-xs font-bold">
                                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>Chưa đến hạn
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-slate-100 text-slate-600 dark:bg-slate-700/30 dark:text-slate-200 text-xs px-2 py-1 rounded-full font-bold">
                                    {{ $ranking->items_count }} sách
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.weekly-rankings.edit', $ranking->id_weekly_ranking) }}"
                                       class="p-2 text-primary hover:bg-red-50 rounded-lg transition-colors" title="Sửa">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.weekly-rankings.destroy', $ranking->id_weekly_ranking) }}" method="POST"
                                          onsubmit="return confirm('Xóa bảng xếp hạng tuần này?')">
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
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                Chưa có bảng xếp hạng tuần.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rankings->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $rankings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection


