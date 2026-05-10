@extends('layouts.admin')

@section('title', 'Quản Lý Tác Giả')
@section('page-title', 'Quản lý Tác Giả')

@section('content')
<div class="max-w-[1230px] mx-auto space-y-6">

    {{-- ── Page Header ── --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white"
                style="font-family: var(--font-heading, 'Lora', serif)">
                Quản lý tác giả
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Danh sách các tác giả và nhà văn trong hệ thống của bạn.
            </p>
        </div>
        <div class="flex items-center gap-3">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2.5 rounded-xl flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-green-500 text-[18px]">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif
            <a href="{{ route('admin.authors.create') }}"
                class="inline-flex items-center gap-2 bg-primary text-white font-bold px-5 py-2.5 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.3)] text-sm">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                Thêm tác giả mới
            </a>
        </div>
    </div>

    {{-- ── Stats Cards — data from AuthorController stats ── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        {{-- Total Authors --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">TotalAuthors</p>
            <div class="flex items-end justify-between">
                <p class="text-4xl font-black text-primary">{{ number_format($stats['total']) }}</p>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">+12%</span>
            </div>
        </div>

        {{-- Active --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Active</p>
            <div class="flex items-end justify-between">
                <p class="text-4xl font-black text-slate-900 dark:text-white">{{ number_format($stats['active']) }}</p>
                <span class="material-symbols-outlined text-emerald-500 text-2xl">trending_up</span>
            </div>
        </div>

        {{-- Diverse Nationalities --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Diverse Nationalities</p>
            <div class="flex items-end justify-between">
                <p class="text-4xl font-black text-slate-900 dark:text-white">{{ number_format($stats['countries']) }}</p>
                <span class="text-xs text-slate-400 font-medium">Global Reach</span>
            </div>
        </div>

        {{-- Total Works --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">TotalWorks</p>
            <div class="flex items-end justify-between">
                @php
                    $tw = $stats['totalBooks'];
                    $twFormatted = $tw >= 1000 ? number_format($tw / 1000, 1) . 'k' : $tw;
                @endphp
                <p class="text-4xl font-black text-slate-900 dark:text-white">{{ $twFormatted }}</p>
                <span class="material-symbols-outlined text-slate-400 text-2xl">library_books</span>
            </div>
        </div>
    </div>

    {{-- ── Author Table Card ── --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-slate-800">
            <h2 class="font-bold text-slate-800 dark:text-slate-200 text-base"
                style="font-family: var(--font-heading, 'Lora', serif)">
                Danh lục lưu trữ
            </h2>
            <div class="flex items-center gap-2">
                <button class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                    <span class="material-symbols-outlined text-[16px]">filter_list</span>
                    Lọc
                </button>
                <button class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                    <span class="material-symbols-outlined text-[16px]">download</span>
                    Xuất dữ liệu
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[640px]">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tác giả</th>
                        <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Quốc tịch</th>
                        <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tác phẩm</th>
                        <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Trạng thái</th>
                        <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $author)
                        {{-- avatar_url, code, status_label, status_badge_class are Model Accessors --}}
                        <tr class="border-b border-slate-50 dark:border-slate-800/50 hover:bg-slate-50/60 dark:hover:bg-slate-800/20 transition-colors group">

                            {{-- Avatar + Name + Code --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 flex-shrink-0">
                                        {{-- avatar_url is a Model Accessor --}}
                                        <img src="{{ $author->avatar_url }}"
                                            alt="{{ $author->name }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-white text-sm">{{ $author->name }}</p>
                                        {{-- code is a Model Accessor --}}
                                        <p class="text-[11px] text-slate-400 mt-0.5">ID: {{ $author->code }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Country --}}
                            <td class="px-4 py-4">
                                <span class="text-sm text-slate-600 dark:text-slate-400">
                                    {{ $author->country ?? '—' }}
                                </span>
                            </td>

                            {{-- Book count --}}
                            <td class="px-4 py-4">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    {{ number_format($author->books_count ?? 0) }} Books
                                </span>
                            </td>

                            {{-- Status badge --}}
                            <td class="px-4 py-4">
                                {{-- status_label and status_badge_class are Model Accessors --}}
                                @if(($author->books_count ?? 0) > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-emerald-200 bg-emerald-50 text-emerald-700">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-slate-200 bg-slate-50 text-slate-500">
                                        Archived
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.authors.edit', $author->id) }}"
                                        class="p-1.5 text-slate-400 hover:text-primary hover:bg-red-50 rounded-lg transition-all"
                                        title="Chỉnh sửa">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.authors.destroy', $author->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Xác nhận xóa tác giả {{ addslashes($author->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                                            title="Xóa">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-400 text-3xl">person_edit</span>
                                    </div>
                                    <p class="text-slate-500 font-medium text-sm">Chưa có tác giả nào</p>
                                    <a href="{{ route('admin.authors.create') }}"
                                        class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-primary/90 transition-all">
                                        Thêm tác giả đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-slate-500">
                Hiển thị {{ $authors->count() }} trên {{ number_format($authors->total()) }} tác giả
            </p>

            @if($authors->hasPages())
                <div class="flex items-center gap-1">
                    {{-- Prev --}}
                    @if($authors->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </span>
                    @else
                        <a href="{{ $authors->previousPageUrl() }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($authors->getUrlRange(max(1, $authors->currentPage() - 1), min($authors->lastPage(), $authors->currentPage() + 1)) as $page => $url)
                        @if($page == $authors->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary text-white text-sm font-bold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if($authors->lastPage() > 3)
                        <span class="text-slate-400 text-sm px-1">...</span>
                        <a href="{{ $authors->url($authors->lastPage()) }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm transition-all">
                            {{ $authors->lastPage() }}
                        </a>
                    @endif

                    {{-- Next --}}
                    @if($authors->hasMorePages())
                        <a href="{{ $authors->nextPageUrl() }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
