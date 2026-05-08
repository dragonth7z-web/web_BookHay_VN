@php
    $savePercent = $combo->original_price > 0 && $combo->original_price > $combo->sale_price
        ? round((($combo->original_price - $combo->sale_price) / $combo->original_price) * 100)
        : 0;
    $coverBooks  = $combo->books->take(4);
    $coverCount  = $coverBooks->count();
    $bookCount   = $combo->books->count();
    $comboId     = $combo->id;
    $comboName   = addslashes($combo->name);
    $comboPrice  = $combo->sale_price;
@endphp

<div class="product-card-container h-full active-feedback group/card" data-combo-id="{{ $comboId }}">
    <div class="relative rounded-[6px] overflow-hidden bg-white dark:bg-slate-800 border border-slate-100/80 dark:border-white/[0.06] shadow-sm hover:shadow-[var(--shadow-book-hover)] hover:-translate-y-2 hover:rotate-[-1.5deg] transition-all duration-500 cursor-pointer flex flex-col h-full">

        {{-- Image Area --}}
        <div class="card-image-wrap aspect-square bg-gray-50 dark:bg-slate-800/80 overflow-hidden flex items-center justify-center relative p-2">
            <a href="{{ route('combo.show', $comboId) }}" 
               class="block w-full h-full relative z-10"
               onclick="if(typeof trackComboView === 'function') trackComboView({{ $comboId }}, '{{ $comboName }}')">
                
                @if($coverCount === 0)
                    <div class="w-full h-full flex flex-col items-center justify-center gap-2 text-slate-300">
                        <span class="material-symbols-outlined text-5xl">collections_bookmark</span>
                        <span class="text-xs font-medium">Combo sách</span>
                    </div>
                @elseif($coverCount === 1)
                    <img loading="lazy" src="{{ $coverBooks->first()->cover_image_url }}" alt="{{ $combo->name }}"
                         class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-1000">
                @elseif($coverCount === 2)
                    <div class="grid grid-cols-2 h-full gap-px">
                        @foreach($coverBooks as $cb)
                        <img loading="lazy" src="{{ $cb->cover_image_url }}" alt="{{ $combo->name }}"
                             class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-1000">
                        @endforeach
                    </div>
                @elseif($coverCount === 3)
                    <div class="grid grid-cols-2 h-full gap-px">
                        <img loading="lazy" src="{{ $coverBooks[0]->cover_image_url }}" alt="{{ $combo->name }}"
                             class="w-full h-full object-cover row-span-2 group-hover/card:scale-110 transition-transform duration-1000">
                        <img loading="lazy" src="{{ $coverBooks[1]->cover_image_url }}" alt="{{ $combo->name }}"
                             class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-1000">
                        <img loading="lazy" src="{{ $coverBooks[2]->cover_image_url }}" alt="{{ $combo->name }}"
                             class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-1000">
                    </div>
                @else
                    <div class="grid grid-cols-2 grid-rows-2 h-full gap-px">
                        @foreach($coverBooks as $cb)
                        <img loading="lazy" src="{{ $cb->cover_image_url }}" alt="{{ $combo->name }}"
                             class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-1000">
                        @endforeach
                    </div>
                @endif
            </a>

            {{-- Action Badges --}}
            @if($savePercent > 0)
                <div class="absolute top-3 left-3 bg-gradient-to-br from-red-500 to-rose-600 text-white text-[10px] font-black px-2.5 py-1 rounded-[4px] shadow-lg z-30 tracking-tight">Tiết kiệm {{ $savePercent }}%</div>
            @endif
            
            <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-sm text-white text-[10px] font-black px-2 py-1 rounded-[4px] shadow-md z-30 border border-white/10 flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">menu_book</span>
                {{ $bookCount }} cuốn
            </div>

            {{-- 1. Premium Shimmer Effect on Hover --}}
            <div class="absolute inset-0 bg-gradient-to-tr from-white/20 via-transparent to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-700 pointer-events-none z-10"></div>
            <div class="absolute top-0 -left-[100%] w-1/2 h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -skew-x-12 group-hover/card:animate-shimmer-slide z-20"></div>

            {{-- 2. Slide-up Action Buttons (Book-card style) --}}
            <div class="absolute bottom-3 right-3 flex gap-1.5 z-30 translate-y-12 opacity-0 group-hover/card:translate-y-0 group-hover/card:opacity-100 transition-all duration-500">
                {{-- Mua ngay --}}
                <div class="relative group/tip">
                    <a href="{{ route('combo.show', $comboId) }}?buy=1"
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
                            onclick="if(typeof addComboToCart === 'function') { addComboToCart({{ $comboId }}, '{{ $comboName }}', event) } else { addToCart({{ $comboId }}, '{{ $comboName }}', event) }">
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
                            class="w-9 h-9 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-[4px] flex items-center justify-center shadow-xl border border-white/20 dark:border-slate-700/50 hover:bg-primary hover:text-white transition-all duration-300 cursor-pointer"
                            aria-label="Lưu yêu thích"
                            onclick="if(typeof toggleComboWishlist === 'function') { toggleComboWishlist({{ $comboId }}, event) } else { toggleWishlist({{ $comboId }}) }">
                        <span class="material-symbols-outlined !text-[1.2rem]">favorite</span>
                    </button>
                    <span class="pointer-events-none absolute bottom-full right-0 mb-2 whitespace-nowrap bg-slate-900 text-white text-[10px] font-medium px-2 py-1 rounded shadow-lg opacity-0 group-hover/tip:opacity-100 transition-opacity duration-200 z-50">
                        Lưu yêu thích
                        <span class="absolute top-full right-[14px] border-4 border-transparent border-t-slate-900"></span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-2 md:p-3 flex flex-col flex-1 bg-white dark:bg-slate-900">
            <a href="{{ route('combo.show', $comboId) }}" class="block mb-1 group/title">
                <h3 class="text-sm font-medium leading-tight text-slate-800 dark:text-slate-100 line-clamp-2 transition-colors group-hover/title:text-primary h-10 overflow-hidden"
                    style="font-family: var(--font-ui, 'Inter', sans-serif);">
                    {{ $combo->name }}
                </h3>
            </a>

            {{-- Mô tả ngắn --}}
            @if(!empty($combo->description))
            <p class="text-[10px] text-slate-400 leading-snug line-clamp-2 mb-1">{{ $combo->description }}</p>
            @endif

            <div class="mt-auto">
                <div class="flex flex-col gap-0.5">
                    <div class="flex items-center flex-wrap gap-1.5">
                        <span class="text-sm md:text-base font-bold text-red-600 dark:text-red-500 tracking-tight">
                            {{ number_format($comboPrice, 0, ',', '.') }}<span class="text-[0.7em] ml-0.5 align-top uppercase">đ</span>
                        </span>
                        
                        @if($savePercent >= 5)
                            <span class="bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 text-[10px] font-black px-1.5 py-0.5 rounded-sm">-{{ $savePercent }}%</span>
                        @endif
                    </div>
                    
                    @if($combo->original_price > $comboPrice)
                        <span class="text-xs text-slate-400 line-through font-medium">
                            {{ number_format($combo->original_price, 0, ',', '.') }}đ
                        </span>
                    @endif

                    {{-- Sold count --}}
                    @if(($combo->sold_count ?? 0) > 0)
                    <div class="text-slate-400 text-[10px] mt-1 font-medium">
                        Đã bán {{ number_format($combo->sold_count) }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
