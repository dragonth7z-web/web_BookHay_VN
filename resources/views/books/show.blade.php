@extends('layouts.app')

@section('content')
<main class="max-w-main mx-auto px-2 py-8 space-y-8 bg-transparent dark:bg-transparent">

    {{-- Breadcrumb --}}
    <nav aria-label="Breadcrumb" class="flex text-xs font-medium text-gray-500 dark:text-slate-400">
        <ol class="inline-flex items-center space-x-2">
            <li class="inline-flex items-center">
                <a class="hover:text-primary dark:text-slate-300 transition-colors" href="{{ route('home') }}">Trang chủ</a>
            </li>
            @if($book->category)
            <li class="flex items-center">
                <span class="material-symbols-outlined text-sm opacity-40">chevron_right</span>
                <a class="ml-2 hover:text-primary dark:text-slate-300 transition-colors" href="{{ route('books.search', ['category' => $book->category_id]) }}">
                    {{ $book->category->name }}
                </a>
            </li>
            @endif
            <li class="flex items-center">
                <span class="material-symbols-outlined text-sm opacity-40">chevron_right</span>
                <span class="ml-2 text-dark truncate max-w-[200px]">{{ $book->title }}</span>
            </li>
        </ol>
    </nav>

    {{-- Main Product Suite --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Left: Visual Gallery --}}
        <div class="lg:col-span-5 bg-white dark:bg-slate-800/50 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 p-6 shadow-sm sticky top-24">
            <div class="product-image-container relative aspect-[4/5] bg-gray-50 rounded-xl overflow-hidden group">
                @php
                    $mainImg = $book->cover_image
                        ? (\Illuminate\Support\Str::startsWith($book->cover_image, ['http://', 'https://'])
                            ? $book->cover_image
                            : asset('storage/' . $book->cover_image))
                        : 'https://placehold.co/400x400?text=No+Image';
                @endphp
                <img id="main-book-img"
                     alt="{{ $book->title }}"
                     class="w-full h-full object-contain p-8 transition-transform group-hover:scale-105 duration-700 ease-out"
                     src="{{ $mainImg }}">
                
                {{-- Lightbox / Zoom hint --}}
                <div class="absolute bottom-4 right-4 bg-white/80 backdrop-blur p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-gray-600">zoom_in</span>
                </div>
            </div>

            @php $extraImgs = $book->extra_images ?? []; @endphp
            @if(count($extraImgs) > 0)
            <div class="flex gap-3 mt-4 overflow-x-auto hide-scrollbar pb-2">
                <button class="w-20 h-24 flex-shrink-0 border-2 border-primary rounded-lg p-1 bg-gray-50 transition-all shadow-sm"
                     onclick="updateMainImage(this, '{{ $mainImg }}')">
                    <img class="w-full h-full object-contain mix-blend-multiply" src="{{ $mainImg }}" alt="Ảnh chính">
                </button>
                @foreach($extraImgs as $img)
                @php
                    $imgUrl = \Illuminate\Support\Str::startsWith($img, ['http://', 'https://']) ? $img : asset('storage/' . $img);
                @endphp
                <button class="w-20 h-24 flex-shrink-0 border border-slate-200 dark:border-slate-700 rounded-lg p-1 bg-gray-50 hover:border-primary transition-all shadow-sm"
                     onclick="updateMainImage(this, '{{ $imgUrl }}')">
                    <img class="w-full h-full object-contain mix-blend-multiply" src="{{ $imgUrl }}" alt="Ảnh phụ">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Right: 2-column detail layout --}}
        <div class="lg:col-span-7">
            @php
                $stock   = $book->stock ?? 0;
                $inStock = $book->status?->value === 'in_stock';
                $ratingAvg = number_format($book->rating_avg ?? 4.8, 1);
                $reviewCount = $book->reviews->count();
                $discountPct = ($book->original_price > $book->sale_price && $book->original_price > 0)
                    ? round((($book->original_price - $book->sale_price) / $book->original_price) * 100)
                    : 0;
            @endphp

            {{-- ═══════════════════════════════════════════════════════ --}}
            {{-- PHẦN 1: THÔNG TIN TIÊU ĐIỂM                           --}}
            {{-- ═══════════════════════════════════════════════════════ --}}
            <div class="bg-white dark:bg-slate-800/50 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-sm p-6 space-y-5">

                {{-- Badge bestseller + trạng thái --}}
                <div class="flex items-center gap-2 flex-wrap">
                    @if($inStock)
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">
                            <span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                            Còn hàng
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-red-500 bg-red-50 border border-red-200 px-2.5 py-1 rounded-full">
                            <span class="material-symbols-outlined text-[13px]">cancel</span>
                            Hết hàng
                        </span>
                    @endif
                    @if($inStock && $stock > 0 && $stock < 20)
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full">
                            <span class="material-symbols-outlined text-[13px]">local_fire_department</span>
                            Sắp hết — còn {{ $stock }}
                        </span>
                    @endif
                </div>

                {{-- Tên sách --}}
                <h1 class="product-title leading-tight">{{ $book->title }}</h1>

                {{-- Tác giả / Dịch giả --}}
                @if($book->authors->count())
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $book->authors->pluck('name')->join(', ') }}
                    @if($book->translator ?? false)
                        <span class="mx-1 text-slate-300">|</span>
                        {{ $book->translator }}
                    @endif
                </p>
                @endif

                {{-- Chips: NXB · Loại bìa · Số trang --}}
                <div class="flex items-center gap-2 flex-wrap">
                    @if($book->publisher)
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 px-2.5 py-1.5 rounded-lg">
                        <span class="material-symbols-outlined text-[13px] text-slate-400">corporate_fare</span>
                        {{ $book->publisher->name }}
                    </span>
                    @endif
                    @if($book->cover_type)
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 px-2.5 py-1.5 rounded-lg">
                        <span class="material-symbols-outlined text-[13px] text-slate-400">menu_book</span>
                        {{ strtoupper($book->cover_type->value) }}
                        @if($book->pages) • {{ number_format($book->pages) }} TRANG @endif
                    </span>
                    @elseif($book->pages)
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 px-2.5 py-1.5 rounded-lg">
                        <span class="material-symbols-outlined text-[13px] text-slate-400">menu_book</span>
                        {{ number_format($book->pages) }} TRANG
                    </span>
                    @endif
                </div>

                {{-- Đánh giá --}}
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex items-center gap-0.5">
                        @for($s = 1; $s <= 5; $s++)
                            <span class="material-symbols-outlined text-base {{ $s <= floor($ratingAvg) ? 'text-yellow-400' : 'text-gray-300' }}"
                                  style="font-variation-settings:'FILL' 1">star</span>
                        @endfor
                    </div>
                    <span class="text-sm font-black text-slate-800 dark:text-white">{{ $ratingAvg }}/5</span>
                    <span class="text-xs text-slate-400">·</span>
                    <span class="text-xs text-slate-500">{{ number_format($reviewCount ?: 1200) }} đánh giá</span>
                    <span class="text-xs text-slate-400">·</span>
                    <span class="text-xs text-slate-500">Đã bán <strong class="text-slate-700 dark:text-slate-300">{{ number_format($book->sold_count ?? 0) }}+</strong></span>
                </div>

                {{-- Giá --}}
                <div class="flex items-baseline gap-3 flex-wrap bg-slate-50 dark:bg-slate-900/40 rounded-xl px-5 py-4 border border-slate-100 dark:border-slate-700/50">
                    <span class="product-price-anchor">
                        {{ number_format($book->sale_price, 0, ',', '.') }}<span class="text-base font-bold ml-0.5">đ</span>
                    </span>
                    @if($discountPct > 0)
                        <span class="product-price-original">{{ number_format($book->original_price, 0, ',', '.') }}đ</span>
                        <span class="bg-brand-primary text-white text-xs font-black px-2.5 py-1 rounded-lg shadow-sm">-{{ $discountPct }}%</span>
                    @endif
                </div>

                {{-- Badge danh hiệu --}}
                @php
                    $isBestseller = ($book->is_bestseller ?? false) || ($book->sold_count ?? 0) >= 500;
                    $isHot        = ($book->is_hot ?? false) || ($book->sold_count ?? 0) >= 200;
                    $soldPercent  = min(100, (int)(($book->sold_count ?? 0) / 10));
                @endphp
                @if($isBestseller || $isHot || $book->category)
                <div class="flex items-center gap-2 flex-wrap">
                    @if($book->category)
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wide text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-full">
                        <span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1">emoji_events</span>
                        Hạng 1 trong {{ $book->category->name }}
                    </span>
                    @endif
                    @if($isBestseller)
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wide text-brand-primary bg-red-50 border border-red-200 px-3 py-1.5 rounded-full">
                        <span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1">local_fire_department</span>
                        Bán chạy nhất
                    </span>
                    @endif
                </div>
                @endif

                {{-- Progress bar đã bán --}}
                @if(($book->sold_count ?? 0) > 0)
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <p class="text-xs text-slate-500">
                            Đã bán được <strong class="text-brand-primary">{{ number_format($book->sold_count) }}</strong> bản tuần này
                        </p>
                        @if($soldPercent >= 70)
                        <span class="text-[10px] font-black text-brand-primary uppercase tracking-widest">Cực hot</span>
                        @endif
                    </div>
                    <div class="w-full h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-brand-primary to-rose-400 rounded-full transition-all duration-1000"
                             style="width: {{ min(100, $soldPercent) }}%"></div>
                    </div>
                </div>
                @endif

                {{-- Quantity --}}
                @if($inStock)
                <div class="flex items-center gap-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Số lượng</span>
                    <div class="inline-flex items-center border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
                        <button onclick="changeQty(-1)" class="w-10 h-10 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors border-r border-slate-200 dark:border-slate-700 active-feedback">
                            <span class="material-symbols-outlined text-sm">remove</span>
                        </button>
                        <input id="qty-input" class="w-14 text-center border-none focus:ring-0 font-bold text-dark bg-transparent" type="number" value="1" min="1" max="{{ $book->stock ?? 99 }}">
                        <button onclick="changeQty(1)" class="w-10 h-10 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors border-l border-slate-200 dark:border-slate-700 active-feedback">
                            <span class="material-symbols-outlined text-sm">add</span>
                        </button>
                    </div>
                </div>
                @endif

            </div>

            {{-- ═══════════════════════════════════════════════════════ --}}
            {{-- PHẦN 2: CHI TIẾT & CHUYỂN ĐỔI                         --}}
            {{-- ═══════════════════════════════════════════════════════ --}}
            <div class="mt-4 bg-white dark:bg-slate-800/50 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-sm overflow-hidden divide-y divide-slate-100 dark:divide-slate-700/60">

                {{-- 1. Điểm nhấn & Mô tả --}}
                <div class="p-5">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">menu_book</span>
                        Điểm nhấn
                    </p>
                    @if($book->summary ?? $book->description)
                    <blockquote class="border-l-4 border-brand-primary/40 pl-3 mb-3">
                        <p class="text-sm italic text-slate-600 dark:text-slate-300 line-clamp-2">
                            "{{ Str::limit(strip_tags($book->summary ?? $book->description), 120) }}"
                        </p>
                    </blockquote>
                    @endif
                    <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" id="desc-content">
                        @if($book->description)
                            <div id="desc-short" class="line-clamp-4">{!! nl2br(e($book->description)) !!}</div>
                            <div id="desc-full" class="hidden">{!! nl2br(e($book->description)) !!}</div>
                            @if(strlen($book->description) > 300)
                            <button onclick="toggleDesc()" id="desc-toggle"
                                class="mt-2 text-brand-primary text-xs font-bold hover:underline flex items-center gap-1">
                                Xem thêm tóm tắt nội dung
                                <span class="material-symbols-outlined text-sm">expand_more</span>
                            </button>
                            @endif
                        @else
                            <p class="italic text-slate-400">Chưa có mô tả chi tiết cho sản phẩm này.</p>
                        @endif
                    </div>
                </div>

                {{-- 2. Thông số kỹ thuật --}}
                <div class="p-5">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">info</span>
                        2. Thông số kỹ thuật
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @php
                            $specs = [
                                ['label' => 'Mã sản phẩm (ISBN)', 'value' => $book->isbn ?? '—'],
                                ['label' => 'Số trang',           'value' => ($book->pages ?? null) ? $book->pages . ' trang' : '—'],
                                ['label' => 'Định dạng',          'value' => $book->cover_type?->value ?? 'Bìa mềm'],
                                ['label' => 'Kích thước',         'value' => $book->dimensions ?? '14.5 x 20.5 cm'],
                                ['label' => 'Nhà xuất bản',       'value' => $book->publisher?->name ?? '—'],
                                ['label' => 'Năm xuất bản',       'value' => $book->published_year ?? '—'],
                            ];
                        @endphp
                        @foreach($specs as $spec)
                        <div class="flex justify-between items-start gap-2 bg-slate-50 dark:bg-slate-900/30 rounded-lg px-3 py-2.5 text-xs">
                            <span class="text-slate-500 font-medium shrink-0">{{ $spec['label'] }}</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-right">{{ $spec['value'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- 3. Chính sách & Cam kết --}}
                <div class="p-5">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">verified_user</span>
                        3. Chính sách & Cam kết
                    </p>
                    <div class="space-y-2.5">
                        <div class="flex items-start gap-3 bg-slate-50 dark:bg-slate-900/30 rounded-xl p-3 border border-slate-100 dark:border-slate-700/40">
                            <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-blue-600 text-base">local_shipping</span>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-wide">Giao hàng dự kiến</p>
                                <p class="text-xs text-slate-500 mt-0.5">Nhận hàng trong 2–3 ngày làm việc.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 bg-slate-50 dark:bg-slate-900/30 rounded-xl p-3 border border-slate-100 dark:border-slate-700/40">
                            <div class="w-9 h-9 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-emerald-600 text-base">assignment_return</span>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-wide">Đổi trả dễ dàng</p>
                                <p class="text-xs text-slate-500 mt-0.5">Kiểm tra hàng trước khi nhận, đổi trả trong 7 ngày.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 bg-slate-50 dark:bg-slate-900/30 rounded-xl p-3 border border-slate-100 dark:border-slate-700/40">
                            <div class="w-9 h-9 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-amber-600 text-base">card_giftcard</span>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-wide">Quà tặng kèm</p>
                                <p class="text-xs text-slate-500 mt-0.5">Tặng kèm Bookmark hoặc Postcard cao cấp (nếu có).</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. Thao tác mua hàng --}}
                @if($inStock)
                <div class="p-5" id="buy-zone-actions">
                    <div class="space-y-3">
                        {{-- Mua ngay BÂY GIỜ --}}
                        <form action="{{ route('cart.add', $book->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="quantity" id="qty-buy-now" value="1">
                            <input type="hidden" name="buy_now" value="1">
                            <button type="submit"
                                class="w-full bg-brand-primary hover:bg-brand-primary-dark text-white font-black text-sm py-4 px-6 rounded-2xl transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl active-feedback momentum-main-cta"
                                data-track="buy_now">
                                MUA NGAY BÂY GIỜ
                                <span class="material-symbols-outlined text-lg">arrow_forward</span>
                            </button>
                        </form>
                        {{-- Thêm vào giỏ + Yêu thích --}}
                        <div class="flex gap-2">
                            <form action="{{ route('cart.add', $book->id) }}" method="POST" class="flex-1" id="form-add-cart">
                                @csrf
                                <input type="hidden" name="quantity" id="qty-add-cart" value="1">
                                <button type="button" onclick="handleAddToCartClick(this)"
                                    class="w-full border-2 border-slate-200 dark:border-slate-600 hover:border-brand-primary bg-white dark:bg-slate-800 hover:bg-red-50 text-slate-700 dark:text-slate-200 hover:text-brand-primary font-bold text-sm py-3.5 px-6 rounded-2xl transition-all duration-200 flex items-center justify-center gap-2"
                                    data-track="cta_click">
                                    <span class="material-symbols-outlined text-lg">shopping_cart</span>
                                    THÊM GIỎ HÀNG
                                </button>
                            </form>
                            <button type="button"
                                onclick="toggleWishlist(this, {{ $book->id }})"
                                class="w-14 h-14 rounded-2xl border-2 border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:border-red-400 hover:text-red-500 transition-all duration-200 bg-white dark:bg-slate-800 shrink-0"
                                aria-label="Thêm vào yêu thích">
                                <span class="material-symbols-outlined text-xl">favorite</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedBooks->count())
    <section class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold tracking-tight text-dark uppercase">Sản phẩm liên quan</h2>
            <a href="{{ route('books.search', ['category' => $book->category_id]) }}" class="text-sm font-bold text-primary group flex items-center gap-1">
                Xem thêm <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>
        <div class="grid-book-layout">
            @foreach($relatedBooks as $related)
                <x-book-card :book="$related" />
            @endforeach
        </div>
    </section>
    @endif

    {{-- Mobile Sticky Bar --}}
    @if($inStock)
    <div class="mobile-sticky-cta lg:hidden">
        <div class="mobile-sticky-price">
            <span class="text-[10px] font-bold text-gray-400 uppercase leading-[1.2]">Giá hiện tại</span>
            <span class="text-lg font-black text-primary leading-[1]">{{ number_format($book->sale_price, 0, ',', '.') }}đ</span>
        </div>
        <form action="{{ route('cart.add', $book->id) }}" method="POST" class="flex items-center gap-2">
            @csrf
            <input type="hidden" name="quantity" value="1">
            <input type="hidden" name="buy_now" value="1">
            <button type="submit" class="mobile-sticky-btn bg-primary text-white font-bold rounded-lg px-6 active-feedback">
                MUA NGAY
            </button>
        </form>
    </div>
    @endif

    {{-- Momentum Dialog Component --}}
    @include('components.momentum-dialog')

</main>

<script>
function changeQty(delta) {
    const input = document.getElementById('qty-input');
    const cartInput = document.getElementById('qty-add-cart');
    let val = parseInt(input.value) + delta;
    val = Math.max(1, Math.min(val, parseInt(input.max) || 99));
    input.value = val;
    if (cartInput) cartInput.value = val;
}

function toggleDesc() {
    const short  = document.getElementById('desc-short');
    const full   = document.getElementById('desc-full');
    const toggle = document.getElementById('desc-toggle');
    const isExpanded = !full.classList.contains('hidden');
    short.classList.toggle('hidden', !isExpanded);
    full.classList.toggle('hidden', isExpanded);
    toggle.innerHTML = isExpanded
        ? 'Xem thêm tóm tắt nội dung <span class="material-symbols-outlined text-sm">expand_more</span>'
        : 'Thu gọn <span class="material-symbols-outlined text-sm">expand_less</span>';
}

function toggleWishlist(btn, bookId) {
    fetch(`/account/wishlist/${bookId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content, 'Accept': 'application/json' }
    }).then(r => r.json()).then(data => {
        const icon = btn.querySelector('.material-symbols-outlined');
        const active = data.wishlisted ?? false;
        icon.style.fontVariationSettings = active ? "'FILL' 1" : "'FILL' 0";
        btn.classList.toggle('text-red-500', active);
        btn.classList.toggle('border-red-400', active);
    }).catch(() => {});
}

function updateMainImage(btn, src) {
    document.getElementById('main-book-img').src = src;
    btn.parentElement.querySelectorAll('button').forEach(b => {
        b.classList.remove('border-primary');
        b.classList.add('border-gray-100');
    });
    btn.classList.add('border-primary');
    btn.classList.remove('border-gray-100');
}




function handleAddToCartClick(btn) {
    const form = document.getElementById('form-add-cart');
    const formData = new FormData(form);
    const qty = formData.get('quantity');
    const originalHtml = btn.innerHTML;

    // Track
    if (window.THLD_Analytics) window.THLD_Analytics.trackEvent('add_to_cart', { id: `{{ $book->id }}`, qty });

    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> Đang xử lý...';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) throw new Error(data.error);
        
        // Success -> trigger momentum
        if (window.THLD_Momentum) {
            window.THLD_Momentum.openMomentumDialog({
                id: `{{ $book->id }}`,
                title: `{{ $book->title }}`,
                image: `{{ $mainImg }}`,
                price: {{ $book->sale_price }}
            }, data.cart_total || 0);
        }

        btn.disabled = false;
        btn.innerHTML = originalHtml;
    })
    .catch(err => {
        alert(err.message || 'Có lỗi xảy ra, vui lòng thử lại.');
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Check if idle tracking should be initialized
    if (window.THLD_Momentum) {
        window.THLD_Momentum.initIdleDetection('.momentum-main-cta', '#buy-zone-actions');
    }
});
</script>
@endsection

