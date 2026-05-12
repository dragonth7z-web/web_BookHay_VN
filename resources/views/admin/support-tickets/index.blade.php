@extends('layouts.admin')

@section('title', 'Yêu Cầu Hỗ Trợ')
@section('page-title', 'Quản lý Yêu cầu Hỗ trợ')

@section('content')
<div class="max-w-[1230px] mx-auto space-y-6">

    {{-- ── Page Header ── --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Quản lý yêu cầu hỗ trợ</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Theo dõi và xử lý các phản hồi từ độc giả và đối tác học thuật.
            </p>
        </div>
        <div class="flex items-center gap-3">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2.5 rounded-xl flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-green-500 text-[18px]">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif
            <a href="#" class="inline-flex items-center gap-2 bg-primary text-white font-bold px-5 py-2.5 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.3)] text-sm">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                + Tạo yêu cầu mới
            </a>
        </div>
    </div>

    {{-- ── Stat Cards — counts come from SupportTicketService → Repository ── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- Tổng yêu cầu --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-xl">confirmation_number</span>
                </div>
                <span class="text-xs font-bold text-green-600 flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                    +12.5%
                </span>
            </div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Tổng yêu cầu</p>
            <p class="text-4xl font-black text-slate-900 dark:text-white mb-4">
                {{ number_format($counts['total']) }}
            </p>
            <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-primary rounded-full" style="width: {{ min(100, $counts['done_rate']) }}%"></div>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">{{ $counts['done_rate'] }}% mục tiêu tháng hoàn thành</p>
        </div>

        {{-- Đang xử lý --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-500 text-xl">pending_actions</span>
                </div>
                <span class="text-xs font-bold text-red-500 flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[14px]">trending_down</span>
                    -4.2%
                </span>
            </div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Đang xử lý</p>
            <p class="text-4xl font-black text-slate-900 dark:text-white mb-4">
                {{ number_format($counts['in_progress']) }}
            </p>
            <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 rounded-full" style="width: {{ min(100, $counts['progress_rate']) }}%"></div>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">Trung bình xử lý 2.4 giờ/ticket</p>
        </div>

        {{-- Đã hoàn tất --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-500 text-xl">task_alt</span>
                </div>
                <span class="text-xs font-bold text-green-600 flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                    +8.1%
                </span>
            </div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Đã hoàn tất</p>
            <p class="text-4xl font-black text-slate-900 dark:text-white mb-4">
                {{ number_format($counts['done']) }}
            </p>
            <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 rounded-full" style="width: {{ min(100, $counts['done_rate']) }}%"></div>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">{{ $counts['done_rate'] }}% tỷ lệ hài lòng khách hàng</p>
        </div>
    </div>

    {{-- ── Ticket List Card ── --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="font-bold text-slate-800 dark:text-slate-200 text-base">Danh sách yêu cầu</h2>
            <div class="flex items-center gap-3">
                {{-- Filter toggle --}}
                <button onclick="document.getElementById('filter-bar').classList.toggle('hidden')"
                    class="p-2 text-slate-400 hover:text-primary rounded-lg hover:bg-slate-50 transition-all"
                    title="Lọc">
                    <span class="material-symbols-outlined text-[20px]">filter_list</span>
                </button>
                {{-- Export --}}
                <button class="p-2 text-slate-400 hover:text-primary rounded-lg hover:bg-slate-50 transition-all"
                    title="Xuất dữ liệu">
                    <span class="material-symbols-outlined text-[20px]">download</span>
                </button>
            </div>
        </div>

        {{-- Filter Bar (collapsible) --}}
        <div id="filter-bar" class="{{ !empty(array_filter($filters)) ? '' : 'hidden' }} border-b border-slate-100 dark:border-slate-800 px-6 py-4 bg-slate-50/50 dark:bg-slate-800/30">
            <form method="GET" action="{{ route('admin.support-tickets.index') }}"
                class="flex flex-wrap gap-3 items-end">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                        class="admin-input pl-9 py-2 w-56 text-sm"
                        placeholder="Tìm mã, tiêu đề, email...">
                </div>
                <select name="status" class="admin-input py-2 pr-8 text-sm min-w-[140px]" onchange="this.form.submit()">
                    <option value="">Tất cả trạng thái</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" {{ ($filters['status'] ?? '') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
                <select name="priority" class="admin-input py-2 pr-8 text-sm min-w-[130px]" onchange="this.form.submit()">
                    <option value="">Tất cả mức độ</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->value }}" {{ ($filters['priority'] ?? '') === $priority->value ? 'selected' : '' }}>
                            {{ $priority->label() }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="admin-btn-primary px-4 py-2 text-sm">Tìm</button>
                @if(!empty(array_filter($filters)))
                    <a href="{{ route('admin.support-tickets.index') }}" class="admin-btn-secondary px-4 py-2 text-sm flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">close</span> Xóa lọc
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[700px]">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-3 text-[11px] font-black text-slate-400 uppercase tracking-widest w-32">Mã yêu cầu</th>
                        <th class="px-4 py-3 text-[11px] font-black text-slate-400 uppercase tracking-widest w-32">Ngày gửi</th>
                        <th class="px-4 py-3 text-[11px] font-black text-slate-400 uppercase tracking-widest">Chủ đề</th>
                        <th class="px-4 py-3 text-[11px] font-black text-slate-400 uppercase tracking-widest w-32 text-center">Trạng thái</th>
                        <th class="px-4 py-3 text-[11px] font-black text-slate-400 uppercase tracking-widest w-24 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        {{-- status_label, status_badge_class, requester_name come from Model Accessors --}}
                        <tr class="border-b border-slate-50 dark:border-slate-800/50 hover:bg-slate-50/60 dark:hover:bg-slate-800/20 transition-colors group">

                            {{-- Mã yêu cầu --}}
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-slate-700 dark:text-slate-300 text-sm">
                                    {{ $ticket->ticket_number }}
                                </span>
                            </td>

                            {{-- Ngày gửi --}}
                            <td class="px-4 py-4">
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    {{ $ticket->created_at->format('d/m/Y') }}
                                </p>
                                <p class="text-[11px] text-slate-400">{{ $ticket->created_at->format('H:i') }}</p>
                            </td>

                            {{-- Chủ đề + mô tả --}}
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.support-tickets.show', $ticket->id) }}"
                                    class="font-semibold text-slate-800 dark:text-slate-200 text-sm hover:text-primary transition-colors block leading-snug">
                                    {{ $ticket->subject }}
                                </a>
                                <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">
                                    {{ Str::limit($ticket->description, 60) }}
                                </p>
                            </td>

                            {{-- Trạng thái --}}
                            <td class="px-4 py-4 text-center">
                                {{-- status_label and status_badge_class are Model Accessors --}}
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide border {{ $ticket->status_badge_class }}">
                                    {{ $ticket->status_label }}
                                </span>
                            </td>

                            {{-- Thao tác --}}
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.support-tickets.show', $ticket->id) }}"
                                        class="p-1.5 text-primary hover:bg-red-50 rounded-lg transition-colors"
                                        title="Xem chi tiết">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open"
                                            class="p-1.5 text-slate-400 hover:bg-slate-100 rounded-lg transition-colors"
                                            title="Thêm">
                                            <span class="material-symbols-outlined text-[20px]">more_vert</span>
                                        </button>
                                        <div x-show="open" @click.outside="open = false"
                                            class="absolute right-0 top-full mt-1 w-40 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-lg z-20 py-1 text-sm">
                                            <a href="{{ route('admin.support-tickets.show', $ticket->id) }}"
                                                class="flex items-center gap-2 px-4 py-2 text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                                                <span class="material-symbols-outlined text-[16px]">edit</span>
                                                Chỉnh sửa
                                            </a>
                                            <form action="{{ route('admin.support-tickets.destroy', $ticket->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Xác nhận xóa?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="w-full flex items-center gap-2 px-4 py-2 text-red-500 hover:bg-red-50 transition-colors">
                                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                                    Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-400 text-3xl">support_agent</span>
                                    </div>
                                    <p class="text-slate-500 font-medium text-sm">Chưa có yêu cầu hỗ trợ nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($tickets->total() > 0)
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-sm text-slate-500">
                    Hiển thị {{ $tickets->firstItem() }} - {{ $tickets->lastItem() }} của {{ number_format($tickets->total()) }} yêu cầu
                </p>
                <div class="flex items-center gap-1">
                    {{-- Trang trước --}}
                    @if($tickets->onFirstPage())
                        <span class="px-3 py-1.5 text-sm text-slate-300 border border-slate-100 rounded-lg cursor-not-allowed">Trang trước</span>
                    @else
                        <a href="{{ $tickets->previousPageUrl() }}"
                            class="px-3 py-1.5 text-sm text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition-all">
                            Trang trước
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($tickets->getUrlRange(max(1, $tickets->currentPage() - 1), min($tickets->lastPage(), $tickets->currentPage() + 1)) as $page => $url)
                        @if($page == $tickets->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center text-sm font-bold bg-primary text-white rounded-lg">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="w-8 h-8 flex items-center justify-center text-sm text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Trang sau --}}
                    @if($tickets->hasMorePages())
                        <a href="{{ $tickets->nextPageUrl() }}"
                            class="px-3 py-1.5 text-sm text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition-all">
                            Trang sau
                        </a>
                    @else
                        <span class="px-3 py-1.5 text-sm text-slate-300 border border-slate-100 rounded-lg cursor-not-allowed">Trang sau</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
