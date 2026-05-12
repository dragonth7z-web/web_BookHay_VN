@extends('layouts.app')

@section('title', 'Flash Sale - THLD')

@section('content')

{{-- ── Hero Banner — luôn hiển thị đỏ theo ảnh mẫu ── --}}
<div class="relative w-full overflow-hidden rounded-2xl mb-6 bg-primary" style="min-height: 280px;">
    {{-- Decorative circles --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-12 -left-12 w-56 h-56 rounded-full bg-black/10"></div>
        <div class="absolute top-1/2 right-1/4 w-32 h-32 rounded-full bg-white/5"></div>
    </div>

    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between h-full px-8 md:px-14 py-10 gap-8">

        {{-- Left: Info --}}
        <div class="text-white flex-1">
            <div class="inline-flex items-center gap-2 bg-white/20 border border-white/30 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full mb-5">
                <span class="material-symbols-outlined text-[13px]">bolt</span>
                Flash Sale Tri Thức
            </div>
            <h1 class="text-3xl md:text-4xl font-black leading-tight mb-3 uppercase"
                style="font-family: var(--font-heading, 'Lora', serif)">
                @if($activeSale)
                    {{ $activeSale->name }}
                @else
                    Cơ Hội Vàng:<br>Sở Hữu Tinh Hoa
                @endif
            </h1>
            <p class="text-white/80 text-sm max-w-md mb-7 leading-relaxed">
                Chương trình ưu đãi lớn nhất dành cho giới học thuật và những người yêu sách. Chỉ diễn ra trong thời gian giới hạn.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="#flash-books"
                    class="inline-flex items-center gap-2 bg-white text-primary font-bold px-6 py-3 rounded-xl hover:bg-white/90 transition-all shadow-lg text-sm">
                    <span class="material-symbols-outlined text-[18px]">bolt</span>
                    Săn Deal Ngay
                </a>
                <a href="{{ route('books.search') }}"
                    class="inline-flex items-center gap-2 bg-white/10 border border-white/30 text-white font-bold px-6 py-3 rounded-xl hover:bg-white/20 transition-all text-sm">
                    <span class="material-symbols-outlined text-[18px]">category</span>
                    Danh Mục Giảm Giá
                </a>
            </div>
        </div>

        {{-- Right: Countdown box — end_date comes from FlashSale model --}}
        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 text-center min-w-[220px] flex-shrink-0">
            <p class="text-rose-400 text-[10px] font-black uppercase tracking-widest mb-4">Kết thúc trong</p>
            <div class="flex items-end justify-center gap-1"
                id="flash-countdown"
                data-end="{{ $activeSale?->end_date?->toIso8601String() ?? now()->addHours(3)->toIso8601String() }}">
                <div class="text-center">
                    <span class="text-5xl font-black text-primary block leading-none" id="cd-hours">02</span>
                    <span class="text-[9px] text-rose-400 uppercase tracking-wider mt-1 block">Giờ</span>
                </div>
                <span class="text-4xl font-black text-primary mb-4 leading-none">:</span>
                <div class="text-center">
                    <span class="text-5xl font-black text-primary block leading-none" id="cd-minutes">45</span>
                    <span class="text-[9px] text-rose-400 uppercase tracking-wider mt-1 block">Phút</span>
                </div>
                <span class="text-4xl font-black text-primary mb-4 leading-none">:</span>
                <div class="text-center">
                    <span class="text-5xl font-black text-primary block leading-none" id="cd-seconds">12</span>
                    <span class="text-[9px] text-rose-400 uppercase tracking-wider mt-1 block">Giây</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Session Tabs ── --}}
<div class="flex items-center gap-6 mb-6 pb-4 border-b border-gray-100">
    <div class="flex items-center gap-2 text-primary font-black text-sm border-b-2 border-primary pb-3 -mb-4">
        <span class="material-symbols-outlined text-[18px]">bolt</span>
        Đang diễn ra
    </div>
    @forelse($upcomingSales as $upcoming)
        <div class="text-gray-400 text-sm font-medium pb-3 -mb-4">
            {{ $upcoming->start_date->format('H:i') }} Sắp tới
        </div>
    @empty
        <div class="text-gray-400 text-sm font-medium pb-3 -mb-4">14:00 Sắp tới</div>
        <div class="text-gray-400 text-sm font-medium pb-3 -mb-4">20:00 Sắp tới</div>
        <div class="text-gray-400 text-sm font-medium pb-3 -mb-4">Ngày mai</div>
    @endforelse
</div>

{{-- ── Flash Sale Books ── --}}
{{-- Books come from FlashSalePageService → FlashSaleRepository or fallback from controller --}}
<div id="flash-books">
    @php
        $hasActiveSaleBooks = $activeSale && $activeSale->items->isNotEmpty();
        $displayBooks = $hasActiveSaleBooks
            ? $activeSale->items->filter(fn($i) => $i->book)->map(function($item) {
                $book = $item->book;
                $book->flash_price  = (int) $item->flash_price;
                $book->is_sale      = true;
                $book->sale_percent = $book->original_price > 0
                    ? round((($book->original_price - $book->flash_price) / $book->original_price) * 100)
                    : 0;
                $total = ($book->stock ?? 0) + ($book->sold_count ?? 0);
                $book->sold_percent = $total > 0
                    ? round((($book->sold_count ?? 0) / $total) * 100) : 0;
                return $book;
            })
            : $fallbackBooks;
    @endphp

    @if($displayBooks->isNotEmpty())
        <div class="grid-book-layout">
            @foreach($displayBooks as $book)
                <x-book-card :book="$book" :show-progress="true" />
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
            <div class="w-20 h-20 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-amber-500 text-4xl">bolt</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Chưa có sách Flash Sale</h3>
            <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                Hãy khám phá các sách đang giảm giá trong cửa hàng.
            </p>
            <a href="{{ route('books.search') }}"
                class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-[0_6px_20px_rgba(201,33,39,0.25)]">
                <span class="material-symbols-outlined text-[18px]">explore</span>
                Xem tất cả sách
            </a>
        </div>
    @endif
</div>

{{-- ── Next Round + Notify ── --}}
<div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-5">

    {{-- Next sale preview --}}
    <div class="relative overflow-hidden rounded-2xl bg-slate-800 p-8 text-white min-h-[200px] flex flex-col justify-end"
        style="background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=800&q=60'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/20 text-white text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full mb-3">
                <span class="material-symbols-outlined text-[12px]">schedule</span>
                Tiếp theo: {{ $upcomingSales->first()?->start_date->format('H:i') ?? '16:00' }}
            </div>
            <h3 class="text-2xl font-black text-white mb-2 uppercase"
                style="font-family: var(--font-heading, 'Lora', serif)">
                {{ $upcomingSales->first()?->name ?? 'Bản Giới Hạn & Sách Hiếm' }}
            </h3>
            <p class="text-white/70 text-sm">
                Đón chờ những bộ sưu tập đặc biệt từ các nhà xuất bản hàng đầu được giảm tới 30%.
            </p>
        </div>
    </div>

    {{-- Notify form --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 flex flex-col justify-center">
        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-primary text-2xl">notifications_active</span>
        </div>
        <h3 class="font-bold text-gray-900 text-base mb-1">Đừng bỏ lỡ round kế tiếp</h3>
        <p class="text-sm text-gray-500 mb-5 leading-relaxed">
            Đăng ký nhận thông báo trước khi phiên Flash Sale bắt đầu 15 phút.
        </p>
        <form class="flex gap-2" onsubmit="return false">
            <input type="email" placeholder="Email của bạn..."
                class="flex-1 px-4 py-3 border border-gray-200 rounded-xl text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
            <button type="submit"
                class="px-6 py-3 bg-primary text-white font-bold text-sm rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] whitespace-nowrap">
                Nhắc tôi
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function () {
        const el = document.getElementById('flash-countdown');
        if (!el) return;

        const endDate = new Date(el.dataset.end).getTime();

        function tick() {
            const diff = Math.max(0, endDate - Date.now());
            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);

            document.getElementById('cd-hours').textContent   = String(h).padStart(2, '0');
            document.getElementById('cd-minutes').textContent = String(m).padStart(2, '0');
            document.getElementById('cd-seconds').textContent = String(s).padStart(2, '0');

            if (diff > 0) setTimeout(tick, 1000);
        }

        tick();
    })();
</script>
@endpush
