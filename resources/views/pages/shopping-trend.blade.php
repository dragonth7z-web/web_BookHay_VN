@extends('layouts.app')

@section('title', 'Xu Hướng Mua Sắm - THLD')

@section('content')

{{-- ── Hero Banner ── --}}
<div class="relative bg-primary rounded-2xl overflow-hidden mb-6 py-8 px-6 md:px-10">
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-white"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 rounded-full bg-white"></div>
    </div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-1.5 bg-white/20 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full mb-3 border border-white/30">
                <span class="material-symbols-outlined text-[13px]">trending_up</span>
                Collection Elite
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-white mb-2 leading-tight"
                style="font-family: var(--font-heading, 'Lora', serif)">
                Xu Hướng Mua Sắm
            </h1>
            <p class="text-white/75 text-sm max-w-md leading-relaxed">
                Discover the literary works currently captivating our global audience of scholars and enthusiasts.
            </p>
        </div>

        {{-- Period filter buttons --}}
        <div class="flex items-center bg-white/15 backdrop-blur-sm rounded-xl p-1 gap-1 flex-shrink-0">
            @foreach(['day' => 'Ngày', 'week' => 'Tuần', 'month' => 'Tháng', 'year' => 'Năm'] as $p => $label)
                <a href="{{ route('shopping-trend.index', array_merge(request()->query(), ['period' => $p])) }}"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all
                        {{ $period === $p
                            ? 'bg-white text-primary shadow-sm'
                            : 'text-white/80 hover:text-white hover:bg-white/20' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Category Filter Tabs ── --}}
<div class="flex items-center gap-2 mb-6 flex-wrap">
    <a href="{{ route('shopping-trend.index', array_merge(request()->except('category'), ['period' => $period])) }}"
        class="px-4 py-2 rounded-xl text-sm font-bold transition-all
            {{ !$categoryId
                ? 'bg-primary text-white shadow-[0_4px_12px_rgba(201,33,39,0.25)]'
                : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary' }}">
        Tất cả
    </a>
    @forelse($categories as $cat)
        <a href="{{ route('shopping-trend.index', ['period' => $period, 'category' => $cat->id]) }}"
            class="px-4 py-2 rounded-xl text-sm font-bold transition-all
                {{ $categoryId === $cat->id
                    ? 'bg-primary text-white shadow-[0_4px_12px_rgba(201,33,39,0.25)]'
                    : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary' }}">
            {{ $cat->name }}
        </a>
    @empty
    @endforelse
</div>

{{-- ── Book Grid ── --}}
@forelse($books as $index => $book)
    @if($loop->first)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
    @endif

    {{-- Rank badge overlay wrapper --}}
    <div class="relative">
        {{-- Rank badge --}}
        <div class="absolute top-2 right-2 z-20 w-7 h-7 rounded-lg bg-gray-800/80 backdrop-blur-sm flex items-center justify-center shadow-md">
            <span class="text-white text-[11px] font-black">{{ $index + 1 }}</span>
        </div>

        {{-- Discount badge --}}
        @if(($book->sale_percent ?? 0) > 0)
        <div class="absolute top-2 left-2 z-20 bg-gradient-to-br from-red-500 to-rose-600 text-white text-[10px] font-black px-2 py-0.5 rounded-[4px] shadow-md">
            GIẢM {{ $book->sale_percent }}%
        </div>
        @endif

        <x-book-card :book="$book" />
    </div>

    @if($loop->last)
        </div>
    @endif

@empty
    <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-200 p-16 text-center mb-8">
        <span class="material-symbols-outlined text-gray-300 text-5xl block mb-4">trending_up</span>
        <p class="text-gray-500 font-semibold text-lg mb-2">Chưa có dữ liệu xu hướng</p>
        <p class="text-gray-400 text-sm mb-6">Hãy quay lại sau khi có thêm dữ liệu mua sắm.</p>
        <a href="{{ route('books.search') }}"
            class="inline-flex items-center gap-2 bg-primary text-white font-bold px-6 py-3 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] text-sm">
            <span class="material-symbols-outlined text-[18px]">explore</span>
            Khám phá sách
        </a>
    </div>
@endforelse

@endsection
