@extends('layouts.app')

@section('title', 'Bộ Sưu Tập Combo - THLD')

@section('content')

{{-- ── Page Header ── --}}
<div class="flex items-center gap-4 mb-6">
    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center flex-shrink-0">
        <span class="material-symbols-outlined text-primary text-2xl">auto_awesome_mosaic</span>
    </div>
    <div>
        <h1 class="text-3xl font-black text-gray-900 leading-tight"
            style="font-family: var(--font-heading, 'Lora', serif)">
            Bộ Sưu Tập Combo
        </h1>
        <p class="text-gray-500 text-sm mt-0.5">Mua theo bộ — giá cực hời cho những tâm hồn curation.</p>
    </div>
</div>

{{-- ── Category Filter Tabs ── --}}
<div class="flex items-center gap-2 mb-6 flex-wrap">
    <a href="{{ route('combo.index') }}"
        class="px-4 py-2 rounded-xl text-sm font-bold transition-all
            {{ !$activeCategory
                ? 'bg-primary text-white shadow-[0_4px_12px_rgba(201,33,39,0.25)]'
                : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary' }}">
        Tất cả
    </a>
    @forelse($categoryTabs as $tab)
        <a href="{{ route('combo.index', ['category' => $tab]) }}"
            class="px-4 py-2 rounded-xl text-sm font-bold transition-all
                {{ $activeCategory === $tab
                    ? 'bg-primary text-white shadow-[0_4px_12px_rgba(201,33,39,0.25)]'
                    : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary' }}">
            {{ $tab }}
        </a>
    @empty
    @endforelse
</div>

{{-- ── Combo Grid ── --}}
@forelse($combos as $combo)
    @if($loop->first)
        <div class="grid-book-layout mb-8">
    @endif

    @include('home.combo-card-item', ['combo' => $combo])

    @if($loop->last)
        </div>
    @endif

@empty
    <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-200 p-16 text-center mb-8">
        <span class="material-symbols-outlined text-gray-300 text-5xl block mb-4">collections_bookmark</span>
        <p class="text-gray-500 font-semibold text-lg mb-2">Chưa có combo nào</p>
        <p class="text-gray-400 text-sm mb-6">Hãy quay lại sau khi có thêm bộ sưu tập mới.</p>
        <a href="{{ route('books.search') }}"
            class="inline-flex items-center gap-2 bg-primary text-white font-bold px-6 py-3 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] text-sm">
            <span class="material-symbols-outlined text-[18px]">explore</span>
            Khám phá sách lẻ
        </a>
    </div>
@endforelse

@endsection
