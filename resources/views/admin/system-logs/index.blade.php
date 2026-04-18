@extends('layouts.admin')

@section('title', 'Nhật ký hệ thống')
@section('page-title', 'Nhật ký hệ thống')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="admin-card p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-blue-600">description</span>
        </div>
        <div>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Tổng bản ghi</p>
            <p class="text-lg font-black text-gray-800">{{ number_format($totalLogs) }}</p>
        </div>
    </div>
    <div class="admin-card p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-green-600">today</span>
        </div>
        <div>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Hôm nay</p>
            <p class="text-lg font-black text-gray-800">{{ number_format($totalToday) }}</p>
        </div>
    </div>
    <div class="admin-card p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-red-600">error</span>
        </div>
        <div>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Lỗi hệ thống</p>
            <p class="text-lg font-black text-red-600">{{ number_format($totalErrors) }}</p>
        </div>
    </div>
    <div class="admin-card p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
            <span class="material-symbols-outlined text-amber-600">gpp_maybe</span>
        </div>
        <div>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Cảnh báo Bảo mật</p>
            <p class="text-lg font-black text-amber-600">{{ number_format($totalSecurity) }}</p>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="admin-card p-4 mb-6">
    <form method="GET" action="{{ route('admin.system_logs.index') }}" class="flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[200px]">
            <label class="text-[9px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Tìm kiếm</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Mô tả, người dùng, IP..." 
                       class="w-full pl-9 pr-3 py-2 text-xs bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>
        <div>
            <label class="text-[9px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Loại log</label>
            <select name="type" class="text-xs bg-gray-50 border border-gray-100 rounded-xl py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                <option value="">Tất cả</option>
                <option value="auth" {{ request('type') == 'auth' ? 'selected' : '' }}>🔐 Xác thực</option>
                <option value="data" {{ request('type') == 'data' ? 'selected' : '' }}>📊 Dữ liệu</option>
                <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>❌ Lỗi</option>
                <option value="security" {{ request('type') == 'security' ? 'selected' : '' }}>⚠️ Bảo mật</option>
            </select>
        </div>
        <div>
            <label class="text-[9px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Mức độ</label>
            <select name="level" class="text-xs bg-gray-50 border border-gray-100 rounded-xl py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                <option value="">Tất cả</option>
                <option value="info" {{ request('level') == 'info' ? 'selected' : '' }}>Thông tin</option>
                <option value="warning" {{ request('level') == 'warning' ? 'selected' : '' }}>Cảnh báo</option>
                <option value="error" {{ request('level') == 'error' ? 'selected' : '' }}>Lỗi</option>
                <option value="critical" {{ request('level') == 'critical' ? 'selected' : '' }}>Nghiêm trọng</option>
            </select>
        </div>
        <div>
            <label class="text-[9px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Từ ngày</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="text-xs bg-gray-50 border border-gray-100 rounded-xl py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <div>
            <label class="text-[9px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Đến ngày</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="text-xs bg-gray-50 border border-gray-100 rounded-xl py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <button type="submit" class="px-4 py-2 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-700 transition-all flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">filter_list</span> Lọc
        </button>
        <a href="{{ route('admin.system_logs.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-gray-200 transition-all">
            Xóa bộ lọc
        </a>
    </form>
</div>

{{-- Log Table --}}
<div class="admin-card overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">terminal</span>
            <h3 class="font-bold text-gray-800 text-sm" style="font-family: 'Montserrat', sans-serif;">Nhật ký hệ thống</h3>
            <span class="text-[9px] bg-primary/10 text-primary px-2 py-0.5 rounded-full font-bold">BLACK BOX</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-[10px] text-gray-400 font-medium">{{ $logs->total() }} bản ghi</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="admin-table min-w-[900px]">
            <thead>
                <tr>
                    <th class="w-10">#</th>
                    <th>Thời gian</th>
                    <th>Loại</th>
                    <th>Mức độ</th>
                    <th>Hành động</th>
                    <th>Mô tả</th>
                    <th>Người thực hiện</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-xs">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50/80 transition-colors group">
                    <td class="px-4 py-3 text-gray-400 font-mono text-[10px]">{{ $log->id }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-[11px] font-semibold text-gray-700">{{ $log->created_at->format('d/m/Y') }}</div>
                        <div class="text-[9px] text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold 
                            @if($log->type === 'auth') bg-blue-50 text-blue-700 border border-blue-200
                            @elseif($log->type === 'data') bg-emerald-50 text-emerald-700 border border-emerald-200
                            @elseif($log->type === 'error') bg-red-50 text-red-700 border border-red-200
                            @elseif($log->type === 'security') bg-amber-50 text-amber-700 border border-amber-200
                            @else bg-gray-50 text-gray-700 border border-gray-200
                            @endif">
                            <span class="material-symbols-outlined text-[12px]">{{ $log->type_icon }}</span>
                            {{ ucfirst($log->type) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold border {{ $log->level_color }}">
                            @if($log->level === 'critical')
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1 animate-pulse"></span>
                            @endif
                            {{ ucfirst($log->level) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-[10px] bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">{{ $log->action }}</span>
                    </td>
                    <td class="px-4 py-3 max-w-[300px]">
                        <p class="text-[11px] text-gray-700 font-medium truncate" title="{{ $log->description }}">{{ $log->description }}</p>
                        @if($log->object_type)
                            <span class="text-[9px] text-gray-400">{{ $log->object_type }} #{{ $log->object_id }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 rounded-full bg-gradient-to-br from-primary to-red-400 flex items-center justify-center text-white text-[8px] font-bold flex-shrink-0">
                                {{ mb_substr($log->user->name ?? 'H', 0, 1) }}
                            </div>
                            <span class="text-[11px] font-semibold text-gray-700">{{ $log->user->name ?? 'Hệ thống' }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-[10px] text-gray-500">{{ $log->ip_address ?? '—' }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined text-4xl text-gray-300">folder_open</span>
                            <p class="text-sm text-gray-400 font-medium">Chưa có bản ghi log nào.</p>
                            <p class="text-[10px] text-gray-300">Hệ thống sẽ tự động ghi log khi có hoạt động.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($logs->hasPages())
    <div class="p-4 border-t border-gray-100 flex items-center justify-between">
        <div class="text-[10px] text-gray-400 font-medium">
            Hiển thị {{ $logs->firstItem() }}–{{ $logs->lastItem() }} / {{ $logs->total() }} bản ghi
        </div>
        <div>
            {{ $logs->links('pagination::tailwind') }}
        </div>
    </div>
    @endif
</div>

@endsection

