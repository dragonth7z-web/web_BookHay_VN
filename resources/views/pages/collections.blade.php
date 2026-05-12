@extends('layouts.app')

@section('title', 'Tất Cả Bộ Sưu Tập - THLD')

@section('content')

{{-- ── Page Header ── --}}
<div class="flex items-center gap-3 mb-2">
    <span class="material-symbols-outlined text-primary text-2xl">auto_stories</span>
    <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight">
        Tất Cả Bộ Sưu Tập
    </h1>
</div>
<p class="text-gray-500 text-sm max-w-lg leading-relaxed mb-8">
    Khám phá thế giới tri thức thông qua những tuyển tập được biên soạn kỹ lưỡng. Từ những kiệt tác văn học kinh điển đến các nghiên cứu khoa học hiện đại nhất.
</p>

{{-- ── Collections Grid (2 rows × 5 cols) ── --}}
@forelse($collections as $collection)
    @if($loop->first)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-10">
    @endif

    <a href="{{ route('collections.show', $collection) }}"
        class="group relative block aspect-[3/2] rounded-xl overflow-hidden shadow-sm hover:shadow-[0_8px_24px_rgba(0,0,0,0.15)] hover:-translate-y-1 transition-all duration-300">
        <div class="absolute inset-0">
            @if($collection->image)
                <img src="{{ filter_var($collection->image, FILTER_VALIDATE_URL) ? $collection->image : asset('storage/' . $collection->image) }}"
                     alt="{{ $collection->title }}"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            @else
                <div class="w-full h-full bg-gradient-to-br from-slate-600 to-slate-800"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent group-hover:from-primary/75 transition-colors duration-500"></div>
        </div>
        <div class="absolute inset-0 z-10 flex flex-col justify-end p-3">
            <h3 class="text-xs font-black text-white drop-shadow-md line-clamp-2 group-hover:translate-x-0.5 transition-transform duration-300">
                {{ $collection->title }}
            </h3>
            <p class="text-white/70 text-[10px] mt-0.5 line-clamp-1">
                {{ $collection->subtitle ?? 'Khám phá ngay' }}
            </p>
        </div>
    </a>

    @if($loop->last)
        </div>
    @endif

@empty
    <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-200 p-16 text-center mb-10">
        <span class="material-symbols-outlined text-gray-300 text-5xl block mb-4">auto_stories</span>
        <p class="text-gray-500 font-semibold text-lg mb-2">Chưa có bộ sưu tập nào</p>
        <a href="{{ route('books.search') }}"
            class="inline-flex items-center gap-2 bg-primary text-white font-bold px-6 py-3 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] text-sm mt-4">
            <span class="material-symbols-outlined text-[18px]">explore</span>
            Khám phá sách
        </a>
    </div>
@endforelse

{{-- ── Featured Collections ── --}}
@if($featured->isNotEmpty())
<div class="mb-8">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-black text-gray-900">Bộ Sưu Tập Tiêu Biểu</h2>
        <a href="{{ route('books.search') }}"
            class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
            Xem tất cả
            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-[1fr_280px] gap-4">

        {{-- Featured large card --}}
        @php $first = $featured->first(); @endphp
        <a href="{{ route('collections.show', $first) }}"
            class="group relative block rounded-2xl overflow-hidden min-h-[320px]">
            @if($first->image)
                <img src="{{ filter_var($first->image, FILTER_VALIDATE_URL) ? $first->image : asset('storage/' . $first->image) }}"
                     alt="{{ $first->title }}"
                     class="w-full h-full object-cover absolute inset-0 transition-transform duration-700 group-hover:scale-105">
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-slate-700 to-slate-900"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
            <div class="absolute inset-0 z-10 flex flex-col justify-end p-7">
                @if($first->badge)
                <span class="inline-flex items-center bg-primary text-white text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full mb-3 w-fit">
                    {{ $first->badge }}
                </span>
                @endif
                <h3 class="text-2xl font-black text-white leading-tight mb-2"
                    style="font-family: var(--font-heading, 'Lora', serif)">
                    {{ $first->title }}
                </h3>
                @if($first->subtitle)
                <p class="text-white/70 text-sm leading-relaxed mb-4 max-w-sm">{{ $first->subtitle }}</p>
                @endif
                <span class="inline-flex items-center gap-2 bg-white text-gray-900 font-bold px-5 py-2.5 rounded-xl hover:bg-gray-100 transition-all text-sm w-fit">
                    Khám phá ngay
                </span>
            </div>
        </a>

        {{-- Right: 2 smaller cards --}}
        <div class="flex flex-col gap-4">
            @foreach($featured->skip(1)->take(2) as $col)
            <a href="{{ route('collections.show', $col) }}"
                class="group relative block rounded-2xl overflow-hidden flex-1" style="min-height: 150px;">
                @if($col->image)
                    <img src="{{ filter_var($col->image, FILTER_VALIDATE_URL) ? $col->image : asset('storage/' . $col->image) }}"
                         alt="{{ $col->title }}"
                         class="w-full h-full object-cover absolute inset-0 transition-transform duration-700 group-hover:scale-105">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-slate-600 to-slate-800"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute inset-0 z-10 flex flex-col justify-end p-4">
                    <h3 class="text-sm font-black text-white leading-tight">{{ $col->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
