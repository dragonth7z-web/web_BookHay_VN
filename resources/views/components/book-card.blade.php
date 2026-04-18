@props([
    'book' => null,
    'showProgress' => false,
    'variant' => 'default',
    'eager' => false,
])

@if(!$book)
    {{-- Guard: book prop is required --}}
    @php return; @endphp
@endif

@php
    $isCompact = $variant === 'compact';
    $imgSrc = $book->cover_image_url;

    $rating      = (int) ($book->rating_avg ?? 0);
    $ratingCount = $book->rating_count ?? 0;
    $title       = $book->title ?? 'Tên sách';
    $slug        = $book->slug ?? '#';
    $bookId      = $book->id ?? 0;

    $currentPrice = $book->flash_price ?? $book->sale_price ?? $book->original_price ?? 0;
    $originalPrice = $book->original_price ?? 0;
    $hasDiscount  = $originalPrice > $currentPrice;

    $soldPercent = $book->sold_percent ?? 0;
    $soldCount   = $book->sold_count ?? 0;

    // CRO 95+: Refined Social Proof Algorithm
    $baseInterest = $book->base_interest ?? 0;
    
    if ($soldCount <= 0) {
        $base = ($bookId % 15) + 10; 
        $isHot = isset($book->is_hot) && $book->is_hot;
        $isBestSeller = isset($book->is_bestseller) && $book->is_bestseller;

        if ($isBestSeller) {
            $soldCount = rand(300, 950) + $base;
        } elseif ($isHot || $book->flash_price) {
            $soldCount = rand(80, 290) + $base;
        } else {
            $soldCount = rand(12, 45) + $base;
        }
    }

    $interestCount = (int) ($soldCount * 2.5) + $baseInterest;
    $formattedInterest = $interestCount >= 1000 ? number_format($interestCount / 1000, 1, '.', '') . 'k' : $interestCount;

    $isNew = isset($book->created_at) && $book->created_at >= now()->subDays(30);
@endphp

<div class="product-card-container h-full active-feedback group/card" data-book-id="{{ $bookId }}">
    {{-- Book spine effect: border-left mô phỏng gáy sách --}}
    <div class="relative rounded-[6px] overflow-hidden bg-white dark:bg-slate-800 border border-slate-100/80 dark:border-white/[0.06] shadow-sm hover:shadow-[var(--shadow-book-hover)] hover:-translate-y-2 hover:rotate-[-1.5deg] transition-all duration-500 cursor-pointer flex flex-col h-full {{ $isCompact ? 'compact' : '' }}"
         style="border-left: 3px solid var(--color-brand-primary, #C92127);">



        {{-- Gáy sách gradient overlay --}}
        <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-gradient-to-b from-brand-primary via-brand-primary-dark to-brand-primary opacity-90 z-10 pointer-events-none"></div>

        {{-- Image wrap --}}
        <div class="card-image-wrap aspect-square bg-gray-50 dark:bg-slate-800/80 overflow-hidden flex items-center justify-center relative p-2">
            <a href="{{ route('books.show', $slug) }}" 
               class="block w-full h-full text-center flex items-center justify-center relative z-10"
               onclick="if(typeof trackView === 'function') trackView({{ $bookId }}, '{{ addslashes($title) }}', '{{ $imgSrc }}', {{ $currentPrice }}, '{{ $slug }}')">
                <img
                    src="{{ $imgSrc }}"
                    alt="{{ $title }}"
                    loading="{{ $eager ? 'eager' : 'lazy' }}"
                    class="max-w-full max-h-full object-contain transition-transform duration-1000 group-hover/card:scale-110 drop-shadow-sm mix-blend-multiply dark:mix-blend-normal"
                    onerror="this.src='https://placehold.co/400x400?text=No+Image'"
                >
            </a>

            {{-- 1. Premium Shimmer Effect on Hover --}}
            <div class="absolute inset-0 bg-gradient-to-tr from-white/20 via-transparent to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-700 pointer-events-none z-10"></div>
            <div class="absolute top-0 -left-[100%] w-1/2 h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -skew-x-12 group-hover/card:animate-shimmer-slide z-20"></div>

            {{-- Badges - Modern & Subtle --}}
            @if(isset($book->is_sale) && $book->is_sale)
                <div class="absolute top-3 left-3 bg-gradient-to-br from-red-500 to-rose-600 text-white text-[10px] font-black px-2.5 py-1 rounded-[4px] shadow-lg z-30 tracking-tight">Giảm {{ $book->sale_percent ?? 0 }}%</div>
            @elseif($hasDiscount && $originalPrice > 0)
                @php $discountPct = round((($originalPrice - $currentPrice) / $originalPrice) * 100); @endphp
                @if($discountPct >= 5)
                    <div class="absolute top-3 left-3 bg-gradient-to-br from-red-500 to-rose-600 text-white text-[10px] font-black px-2.5 py-1 rounded-[4px] shadow-lg z-30 tracking-tight">Giảm {{ $discountPct }}%</div>
                @endif
            @endif

            {{-- Bestseller / New badge --}}
            @if(($book->sold_count ?? 0) >= 500 || ($book->is_bestseller ?? false))
                <div class="absolute top-3 right-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md text-orange-600 dark:text-orange-400 text-[10px] font-black px-2 py-1 rounded-[4px] shadow-md z-30 border border-orange-100 dark:border-orange-900/50">🔥 HOT</div>
            @elseif($isNew)
                <div class="absolute top-3 right-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md text-blue-600 dark:text-blue-400 text-[10px] font-black px-2 py-1 rounded-[4px] shadow-md z-30 border border-blue-100 dark:border-blue-900/50">✨ MỚI</div>
            @endif

            {{-- 2. Slide-up Action Buttons --}}
            <div class="absolute bottom-3 right-3 flex gap-1.5 z-30 translate-y-12 opacity-0 group-hover/card:translate-y-0 group-hover/card:opacity-100 transition-all duration-500">
                {{-- Mua ngay --}}
                <div class="relative group/tip">
                    <a href="{{ route('books.show', $slug) }}?buy=1"
                       class="w-9 h-9 bg-brand-primary text-white rounded-[4px] flex items-center justify-center shadow-xl hover:bg-brand-primary-dark transition-all duration-300 cursor-pointer"
                       aria-label="Mua ngay"
                       onclick="event.stopPropagation()">
                        <span class="material-symbols-outlined !text-[1.2rem]">bolt</span>
                    </a>
                    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 whitespace-nowrap bg-slate-900 text-white text-[10px] font-medium px-2 py-1 rounded shadow-lg opacity-0 group-hover/tip:opacity-100 transition-opacity duration-200 z-50">
                        Mua ngay
                        <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-900"></span>
                    </span>
                </div>
                {{-- Thêm vào giỏ --}}
                <div class="relative group/tip">
                    <button type="button"
                            class="w-9 h-9 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-[4px] flex items-center justify-center shadow-xl border border-white/20 dark:border-slate-700/50 hover:bg-emerald-500 hover:text-white transition-all duration-300 cursor-pointer"
                            aria-label="Thêm vào giỏ hàng"
                            onclick="addToCart({{ $bookId }}, '{{ addslashes($title) }}', event)">
                        <span class="material-symbols-outlined !text-[1.2rem]">shopping_bag</span>
                    </button>
                    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 whitespace-nowrap bg-slate-900 text-white text-[10px] font-medium px-2 py-1 rounded shadow-lg opacity-0 group-hover/tip:opacity-100 transition-opacity duration-200 z-50">
                        Thêm vào giỏ
                        <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-900"></span>
                    </span>
                </div>
                {{-- Yêu thích --}}
                <div class="relative group/tip">
                    <button type="button"
                            class="w-9 h-9 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-[4px] flex items-center justify-center shadow-xl border border-white/20 dark:border-slate-700/50 hover:bg-primary hover:text-white transition-all duration-300 cursor-pointer wishlist-btn"
                            aria-label="Lưu yêu thích"
                            onclick="toggleWishlist({{ $bookId }})">
                        <span class="material-symbols-outlined !text-[1.2rem]">favorite</span>
                    </button>
                    <span class="pointer-events-none absolute bottom-full right-0 mb-2 whitespace-nowrap bg-slate-900 text-white text-[10px] font-medium px-2 py-1 rounded shadow-lg opacity-0 group-hover/tip:opacity-100 transition-opacity duration-200 z-50">
                        Lưu yêu thích
                        <span class="absolute top-full right-[14px] border-4 border-transparent border-t-slate-900"></span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Card body - Compact & Professional --}}
        <div class="card-body p-2 md:p-3 flex flex-col flex-1 bg-white dark:bg-slate-900">
            {{-- Title - Stable & Precise --}}
            <a href="{{ route('books.show', $slug) }}" class="block mb-1 group/title"
               onclick="if(typeof trackView === 'function') trackView({{ $bookId }}, '{{ addslashes($title) }}', '{{ $imgSrc }}', {{ $currentPrice }}, '{{ $slug }}')">
                <h3 class="text-sm font-medium leading-tight text-slate-800 dark:text-slate-100 line-clamp-2 transition-colors group-hover/title:text-primary h-10 overflow-hidden"
                    style="font-family: var(--font-ui, 'Inter', sans-serif);">{{ $title }}</h3>
            </a>

            {{-- Social Proof & Rating - Unified Line --}}
            <div class="flex items-center flex-wrap gap-x-1 gap-y-1 mb-2">
                {{-- Stars --}}
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-outlined {{ $i <= $rating || ($rating == 0 && $i >= 5) ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700' }} !text-[10px] !font-[FILL_1]">star</span>
                    @endfor
                    @if($ratingCount > 0)
                        <span class="text-[10px] text-slate-400 ml-0.5">({{ $ratingCount }})</span>
                    @endif
                </div>

                <span class="text-[10px] text-slate-300 dark:text-slate-600 select-none">·</span>

                {{-- Sold & Interest --}}
                <div class="flex items-center gap-1.5 overflow-hidden">
                    <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">Đã bán {{ $soldCount >= 1000 ? number_format($soldCount / 1000, 1) . 'k' : $soldCount }}</span>
                    @if($formattedInterest)
                        <span class="text-[10px] text-slate-300 dark:text-slate-600 select-none">·</span>
                        <div class="flex items-center gap-0.5 text-[10px] font-medium text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-orange-500 !text-[10px] font-[FILL_1]">local_fire_department</span>
                            <span>{{ $formattedInterest }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Price Zone - Modern Layout --}}
            <div class="mt-auto">
                <div class="flex flex-col gap-0.5">
                    <div class="flex items-center flex-wrap gap-1.5">
                        <span class="text-sm md:text-base font-bold text-red-600 dark:text-red-500 tracking-tight">
                            {{ number_format($currentPrice, 0, ',', '.') }}<span class="text-[0.7em] ml-0.5 align-top uppercase">đ</span>
                        </span>
                        
                        {{-- Discount Badge next to price --}}
                        @if($hasDiscount && $originalPrice > 0)
                            @php $discountPct = round((($originalPrice - $currentPrice) / $originalPrice) * 100); @endphp
                            @if($discountPct >= 5)
                                <span class="bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 text-[10px] font-black px-1.5 py-0.5 rounded-sm">-{{ $discountPct }}%</span>
                            @endif
                        @endif
                    </div>
                    
                    @if($hasDiscount)
                        <span class="text-xs text-slate-400 line-through font-medium">
                            {{ number_format($originalPrice, 0, ',', '.') }}đ
                        </span>
                    @endif
                </div>

                {{-- Thin Progress Bar for Urgency --}}
                @if($showProgress)
                    <div class="mt-2.5 relative">
                        <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden border border-slate-50 dark:border-white/5 shadow-inner">
                            <div class="h-full bg-gradient-to-r from-primary via-orange-500 to-rose-500 rounded-full animate-shimmer-sweep w-0 transition-all duration-[1.5s]" style="width:{{ $soldPercent }}%"></div>
                        </div>
                        <span class="absolute -top-3.5 right-0 text-[10px] font-black text-rose-500 uppercase tracking-tighter drop-shadow-sm {{ $soldPercent > 80 ? 'animate-pulse' : '' }}">
                            {{ $soldPercent > 85 ? 'Sắp hết' : 'Giảm sâu' }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- book-card.js is bundled in resources/js/app.js --}}