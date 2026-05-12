@extends('layouts.admin')

@section('title', 'Nhà Xuất Bản')
@section('page-title', 'Quản lý Nhà Xuất Bản')

@section('content')
<div class="max-w-[1230px] mx-auto space-y-6">

    {{-- ── Page Header ── --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Nhà Xuất Bản</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Quản lý thông tin và danh sách các đối tác xuất bản sách.
            </p>
        </div>
        <div class="flex items-center gap-3">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2.5 rounded-xl flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-green-500 text-[18px]">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif
            <a href="{{ route('admin.publishers.create') }}"
                class="inline-flex items-center gap-2 bg-primary text-white font-bold px-5 py-2.5 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.3)] text-sm">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                Thêm NXB Mới
            </a>
        </div>
    </div>

    {{-- ── Stats Cards — data from PublisherRepository::getStats() ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

        {{-- Tổng số NXB --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-xl">apartment</span>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">+5%</span>
            </div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Tổng số NXB</p>
            <p class="text-4xl font-black text-slate-900 dark:text-white">{{ number_format($stats['total']) }}</p>
        </div>

        {{-- Tổng đầu sách --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-500 text-xl">library_books</span>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">+12%</span>
            </div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Tổng đầu sách</p>
            <p class="text-4xl font-black text-slate-900 dark:text-white">{{ number_format($stats['total_books']) }}</p>
        </div>

        {{-- NXB mới tháng này --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-amber-500 text-xl">fiber_new</span>
                </div>
                <span class="text-xs font-medium text-slate-400 px-2 py-1">Ổn định</span>
            </div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">NXB mới (tháng này)</p>
            <p class="text-4xl font-black text-slate-900 dark:text-white">
                {{ str_pad($stats['new_this_month'], 2, '0', STR_PAD_LEFT) }}
            </p>
        </div>
    </div>

    {{-- ── Publisher Table ── --}}
    <div class="admin-card overflow-hidden">

        {{-- Table Header --}}
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
            style="background: linear-gradient(135deg, rgba(201,33,39,0.03) 0%, rgba(255,255,255,0.5) 100%);">
            <div class="relative w-full sm:w-72">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">search</span>
                <input type="text" id="publisher-search"
                    class="admin-input w-full pl-10 pr-4 py-2 text-sm"
                    placeholder="Tìm kiếm nhà xuất bản...">
            </div>
            <div class="flex items-center gap-2">
                <button class="admin-btn-secondary flex items-center gap-2 px-4 py-2 text-sm">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span> Lọc
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse admin-table min-w-[700px]">
                <thead>
                    <tr>
                        <th class="w-28">Mã NXB</th>
                        <th>Tên Nhà Xuất Bản</th>
                        <th class="text-center">Số lượng đầu sách</th>
                        <th>Ngày hợp tác</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($publishers as $publisher)
                        {{-- code, logo_url, status_label, status_badge_class are Model Accessors --}}
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">

                            {{-- Mã NXB --}}
                            <td class="px-5 py-4">
                                <span class="font-mono font-semibold text-slate-500 dark:text-slate-400 text-sm">
                                    {{ $publisher->code }}
                                </span>
                            </td>

                            {{-- Tên NXB + Logo --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex-shrink-0 overflow-hidden flex items-center justify-center border border-slate-200 dark:border-slate-600">
                                        {{-- logo_url is a Model Accessor --}}
                                        <img src="{{ $publisher->logo_url }}"
                                            alt="{{ $publisher->name }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <span class="font-bold text-slate-900 dark:text-white text-sm">
                                        {{ $publisher->name }}
                                    </span>
                                </div>
                            </td>

                            {{-- Số đầu sách --}}
                            <td class="px-5 py-4 text-center">
                                <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">
                                    {{ number_format($publisher->books_count ?? 0) }}
                                </span>
                            </td>

                            {{-- Ngày hợp tác --}}
                            <td class="px-5 py-4">
                                <span class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ $publisher->created_at ? $publisher->created_at->format('d/m/Y') : '—' }}
                                </span>
                            </td>

                            {{-- Trạng thái --}}
                            <td class="px-5 py-4 text-center">
                                {{-- status_label and status_badge_class are Model Accessors --}}
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $publisher->status_badge_class }}">
                                    {{ $publisher->status_label }}
                                </span>
                            </td>

                            {{-- Thao tác --}}
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.publishers.edit', $publisher->id) }}"
                                        class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-lg transition-all"
                                        title="Chỉnh sửa">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.publishers.destroy', $publisher->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Xác nhận xóa nhà xuất bản {{ addslashes($publisher->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-lg transition-all"
                                            title="Xóa">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-400 text-3xl">apartment</span>
                                    </div>
                                    <p class="text-slate-500 font-medium text-sm">Chưa có nhà xuất bản nào</p>
                                    <a href="{{ route('admin.publishers.create') }}" class="admin-btn-primary text-sm px-4 py-2">
                                        Thêm NXB đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($publishers->hasPages())
            <div class="p-5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between flex-wrap gap-3">
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    Hiển thị
                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ $publishers->firstItem() }}-{{ $publishers->lastItem() }}</span>
                    trong <span class="font-bold text-slate-800 dark:text-slate-100">{{ $publishers->total() }}</span> nhà xuất bản
                </p>
                {{ $publishers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Client-side search filter
    document.getElementById('publisher-search')?.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush
