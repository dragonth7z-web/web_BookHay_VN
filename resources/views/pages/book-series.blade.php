@extends('layouts.app')

@section('title', 'Khám Phá Trọn Bộ Truyện - THLD')

@section('content')

{{-- ── Page Header ── --}}
<div class="mb-8">
    <div class="inline-flex items-center gap-2 bg-primary text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full mb-4">
        Curated Collections
    </div>
    <h1 class="text-4xl font-black text-gray-900 mb-3 leading-tight uppercase"
        style="font-family: var(--font-heading, 'Lora', serif)">
        Khám Phá Trọn Bộ Truyện
    </h1>
    <p class="text-gray-500 text-sm max-w-xl leading-relaxed">
        Những tuyệt tác văn học và tiểu thuyết đồ họa được tuyển chọn kỹ lưỡng. Sở hữu trọn bộ để trải nghiệm với thiết kế Boxset sang trọng, nâng tầm không gian lưu trữ và chiều sâu tri thức của bạn.
    </p>
</div>

{{-- ── Series List ── --}}
<div class="flex flex-col gap-5 mb-8">
    @forelse($bookSeries as $series)
    @php
        $totalBooks  = $series->books->count();
        $allBooks    = $series->books->sortBy('id')->values();

        $badgeText   = strtolower($series->badge_text ?? '');
        $isOngoing   = str_contains($badgeText, 'đang') || str_contains($badgeText, 'ongoing') || str_contains($badgeText, 'updating');

        $realCovers  = $series->books->filter(fn($b) =>
            !empty($b->cover_image) && !str_contains($b->cover_image_url, 'ui-avatars.com')
        );

        $gradients   = ['from-violet-600 to-indigo-700','from-rose-500 to-pink-700','from-amber-500 to-orange-600','from-emerald-500 to-teal-700','from-sky-500 to-blue-700','from-fuchsia-500 to-purple-700'];
        $gc          = $gradients[$series->id % count($gradients)];
        $hasImg      = !empty($series->image);

        $tapLabel    = $totalBooks . ' Tập';
        $seriesUrl   = route('books.search', ['series' => $series->id]);
        $discountPct = $series->original_price > $series->sale_price && $series->original_price > 0
            ? round((($series->original_price - $series->sale_price) / $series->original_price) * 100) : 0;
        $saving      = max(0, $series->original_price - $series->sale_price);

        $mainCoverUrl = $hasImg ? $series->image_url
            : ($realCovers->count() ? $realCovers->first()->cover_image_url : null);

        $categoryLabel = $series->badge_text ?? ($isOngoing ? 'Đang Cập Nhật' : 'Bộ Truyện Hoàn Chỉnh');

        // Volume numbers for the tab row (max 8 shown + "...")
        $volumeCount = $totalBooks;
        $showVolumes = min($volumeCount, 8);
    @endphp

    <div class="flex bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden"
         style="min-height: 280px;">

        {{-- ── Cover image (left panel) ── --}}
        <div class="relative flex-shrink-0 overflow-hidden bg-gray-100" style="width: 220px;">

            {{-- Tap count badge --}}
            <div class="absolute top-3 left-3 z-20 inline-flex items-center gap-1
                        {{ $isOngoing ? 'bg-amber-500' : 'bg-emerald-500' }}
                        text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow-md">
                {{ $tapLabel }}
            </div>

            @if($mainCoverUrl)
                <div class="absolute inset-0">
                    <img src="{{ $mainCoverUrl }}" alt="" class="w-full h-full object-cover scale-110 blur-lg opacity-40">
                </div>
                <img loading="lazy" src="{{ $mainCoverUrl }}" alt="{{ $series->name }}"
                     class="relative z-10 w-full h-full object-contain p-3 drop-shadow-xl">
            @else
                <div class="absolute inset-0 bg-gradient-to-br {{ $gc }}">
                    <div class="absolute inset-0 opacity-10"
                         style="background-image:repeating-linear-gradient(45deg,transparent,transparent 10px,rgba(255,255,255,.15) 10px,rgba(255,255,255,.15) 11px)"></div>
                </div>
                <div class="relative z-10 h-full flex flex-col items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-white/50 text-[52px]">library_books</span>
                    <span class="text-white/60 text-xs font-bold text-center px-3">{{ $series->name }}</span>
                </div>
            @endif
        </div>

        {{-- ── Content (right panel) ── --}}
        <div class="flex flex-col flex-1 min-w-0 py-5 px-6">

            {{-- Category label --}}
            <div class="flex items-center gap-1.5 mb-1">
                <span class="material-symbols-outlined text-amber-500 text-[14px]">auto_stories</span>
                <p class="text-[10px] font-black uppercase tracking-widest text-primary">{{ $categoryLabel }}</p>
            </div>

            {{-- Title --}}
            <h2 class="text-xl font-black text-gray-900 mb-2 leading-tight"
                style="font-family: var(--font-heading, 'Lora', serif)">
                {{ $series->name }}
            </h2>

            {{-- Description --}}
            @if($series->description)
            <p class="text-xs text-gray-500 leading-relaxed mb-3 line-clamp-3">{{ $series->description }}</p>
            @endif

            {{-- Volume tabs --}}
            <div class="mb-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-2">
                    {{ $isOngoing ? 'Bộ Tập Giới Hạn' : 'Danh Sách Các Tập' }}
                </p>
                <div class="flex items-center gap-1.5 flex-wrap">
                    @for($v = 1; $v <= $showVolumes; $v++)
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[11px] font-black transition-all
                        {{ $v === 1
                            ? 'bg-primary text-white shadow-[0_2px_8px_rgba(201,33,39,0.3)]'
                            : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                        {{ $v }}
                    </div>
                    @endfor
                    @if($volumeCount > $showVolumes)
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs font-bold">
                        ...
                    </div>
                    @endif
                </div>
            </div>

            {{-- Price + buttons --}}
            <div class="flex items-center gap-4 flex-wrap mt-auto pt-3 border-t border-gray-100">
                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-primary font-black text-xl leading-none">
                            {{ number_format($series->sale_price, 0, ',', '.') }}<span class="text-sm ml-0.5 align-top">đ</span>
                        </span>
                        @if($discountPct > 0)
                            <span class="bg-red-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded">-{{ $discountPct }}%</span>
                        @endif
                    </div>
                    @if($series->original_price > $series->sale_price)
                    <p class="text-gray-400 text-xs line-through mt-0.5">{{ number_format($series->original_price, 0, ',', '.') }}đ</p>
                    @endif
                    @if($saving > 0)
                    <p class="text-emerald-600 text-[11px] font-bold mt-0.5">Tiết kiệm {{ number_format($saving, 0, ',', '.') }}đ</p>
                    @endif
                </div>

                <div class="flex gap-2 ml-auto flex-shrink-0">
                    <a href="{{ $seriesUrl }}?buy=1"
                        class="inline-flex items-center gap-1.5 border border-gray-300 hover:border-primary bg-white hover:bg-primary/5 text-gray-700 hover:text-primary text-xs font-bold py-2.5 px-5 rounded-xl transition-all whitespace-nowrap">
                        <span class="material-symbols-outlined text-[14px]">bolt</span>
                        Mua ngay
                    </a>
                    <button type="button"
                        class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary/90 text-white text-xs font-bold py-2.5 px-5 rounded-xl transition-all shadow-sm whitespace-nowrap"
                        onclick="if(typeof addSeriesToCart==='function') addSeriesToCart({{ $series->id }},'{{ addslashes($series->name) }}',event)">
                        <span class="material-symbols-outlined text-[14px]">shopping_bag</span>
                        Thêm vào giỏ
                    </button>
                </div>
            </div>
        </div>
    </div>

    @empty
    <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-200 p-16 text-center">
        <span class="material-symbols-outlined text-gray-300 text-5xl block mb-4">library_books</span>
        <p class="text-gray-500 font-semibold text-lg mb-2">Chưa có bộ truyện nào</p>
        <a href="{{ route('books.search') }}"
            class="inline-flex items-center gap-2 bg-primary text-white font-bold px-6 py-3 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] text-sm mt-4">
            Khám phá sách lẻ
        </a>
    </div>
    @endforelse
</div>

{{-- ── Explore more CTA ── --}}
<div class="flex justify-center mb-6">
    <a href="{{ route('books.search') }}"
        class="inline-flex items-center gap-2 border-2 border-primary text-primary font-black px-8 py-3 rounded-xl hover:bg-primary hover:text-white transition-all text-sm uppercase tracking-wider">
        + Khám Phá Thêm Bộ Truyện
    </a>
</div>

{{-- ── Pagination ── --}}
@if($bookSeries->hasPages())
<div class="flex items-center justify-between py-4 border-t border-gray-100">
    <p class="text-xs text-gray-400">
        Hiển thị {{ $bookSeries->firstItem() }}-{{ $bookSeries->lastItem() }} trên {{ $bookSeries->total() }} bộ sưu tập
    </p>

    <div class="flex items-center gap-1">
        @if($bookSeries->onFirstPage())
            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-xs text-gray-300 border border-gray-100">‹</span>
        @else
            <a href="{{ $bookSeries->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-xs text-gray-600 border border-gray-200 hover:border-primary hover:text-primary transition-all">‹</a>
        @endif

        @foreach($bookSeries->getUrlRange(1, $bookSeries->lastPage()) as $page => $url)
            <a href="{{ $url }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all
                    {{ $page == $bookSeries->currentPage()
                        ? 'bg-primary text-white shadow-[0_2px_8px_rgba(201,33,39,0.25)]'
                        : 'text-gray-600 border border-gray-200 hover:border-primary hover:text-primary' }}">
                {{ $page }}
            </a>
        @endforeach

        @if($bookSeries->hasMorePages())
            <a href="{{ $bookSeries->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-xs text-gray-600 border border-gray-200 hover:border-primary hover:text-primary transition-all">›</a>
        @else
            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-xs text-gray-300 border border-gray-100">›</span>
        @endif
    </div>

    <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;"
        class="text-xs font-black text-primary hover:underline uppercase tracking-wider">
        Về Đầu Trang
    </a>
</div>
@endif

@endsection
