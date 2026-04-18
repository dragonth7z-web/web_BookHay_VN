@extends('layouts.admin')

@section('title', 'Quản lý Banner')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Quản lý Banner Trang chủ</h1>
                <p class="text-slate-500 text-sm mt-1">Cấu hình hình ảnh quảng cáo slider chính.</p>
            </div>
            <a href="{{ route('admin.banner.create') }}"
                class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[20px]">upload_file</span>
                Tải lên Banner Mới
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-green-500">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($banners as $banner)
                <div class="admin-card overflow-hidden p-4 group">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <div class="relative w-full lg:w-72 h-40 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
                            @if($banner->image)
                                @php
                                    if (\Illuminate\Support\Str::startsWith($banner->image, ['http://', 'https://'])) {
                                        $imgSrc = $banner->image;
                                    } else {
                                        $imgSrc = asset('storage/' . $banner->image);
                                    }
                                @endphp
                                <img src="{{ $imgSrc }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined text-5xl">image</span>
                                </div>
                            @endif
                            <div class="absolute top-2 left-2 bg-black/50 text-white text-[10px] px-2 py-1 rounded-lg backdrop-blur-md">
                                {{ $banner->position }}
                            </div>
                        </div>
                        <div class="flex-1 space-y-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">
                                        {{ $banner->title ?? 'Banner không tiêu đề' }}
                                    </h3>
                                    <p class="text-sm text-slate-500">
                                        Thứ tự: {{ $banner->sort_order ?? '—' }}
                                        &nbsp;•&nbsp;
                                        STT: #{{ $banner->id_banner }}
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.banner.edit', $banner->id_banner) }}"
                                        class="p-2 text-slate-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <form action="{{ route('admin.banner.destroy', $banner->id_banner) }}" method="POST"
                                        onsubmit="return confirm('Xóa banner này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold uppercase text-slate-400">Link liên kết</label>
                                    <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 dark:bg-slate-800 rounded-lg border border-[var(--admin-border)]">
                                        <span class="material-symbols-outlined text-sm text-slate-400">link</span>
                                        <span
                                            class="text-xs truncate text-slate-600">{{ $banner->link_url ?? 'Không có' }}</span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold uppercase text-slate-400">Text phụ (Badge/Nút)</label>
                                    <div class="flex flex-col justify-center px-3 py-1.5 bg-slate-50 dark:bg-slate-800 rounded-lg border border-[var(--admin-border)]">
                                        <span class="text-[10px] truncate text-slate-500">Badge: <strong
                                                class="text-slate-700 dark:text-slate-300">{{ $banner->badge_text ?? '—' }}</strong></span>
                                        <span class="text-[10px] truncate text-slate-500">Nút: <strong
                                                class="text-slate-700 dark:text-slate-300">{{ $banner->button_text ?? '—' }}</strong></span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold uppercase text-slate-400">Trạng thái</label>
                                    <div class="flex items-center gap-3 h-full">
                                        @if($banner->status)
                                            <span class="inline-flex items-center gap-1 text-green-600 text-sm font-bold">
                                                <span class="w-3 h-3 rounded-full bg-green-500"></span>Đang hiển thị
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-slate-400 text-sm font-bold">
                                                <span class="w-3 h-3 rounded-full bg-slate-300"></span>Đang ẩn
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="admin-card overflow-hidden border-2 border-dashed p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-slate-300 block mb-3">image_not_supported</span>
                    <p class="text-slate-500">Chưa có banner nào. Thêm banner mới để bắt đầu!</p>
                </div>
            @endforelse
        </div>

        @if($banners instanceof \Illuminate\Pagination\LengthAwarePaginator && $banners->hasPages())
            <div class="mt-4 border-t border-[var(--admin-border)] pt-4">
                {{ $banners->links() }}
            </div>
        @endif
    </div>
@endsection
