@extends('layouts.app')

@section('title', 'Bảng Xếp Hạng Tri Thức - THLD')

@section('content')

{{-- ── Page Header ── --}}
<div class="mb-6">
    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3">Weekly Curation</p>
    <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 leading-tight"
        style="font-family: var(--font-heading, 'Lora', serif)">
        Bảng Xếp Hạng Tri Thức
    </h1>
    <p class="text-gray-500 text-sm max-w-lg leading-relaxed">
        Hệ thống phân tích những tác phẩm, tác giả và trào lưu tri thức có ảnh hưởng nhất trong tuần.
        Xếp hạng được xác định bởi sự kết hợp giữa đánh giá phê bình, thảo luận cộng đồng và tác động
        văn hóa lâu dài.
    </p>
</div>

{{-- ── Filter Tabs ── --}}
<div class="flex items-center gap-2 mb-7 flex-wrap">
    @php
        $tabs = [
            'bestseller' => 'Sách Bestseller',
            'author'     => 'Tác Giả Nổi Bật',
            'collection' => 'Bộ Sưu Tập Của Năm',
            'trend'      => 'Xu Hướng Độc Giả',
        ];
        $activeTab = request('tab', 'bestseller');
    @endphp
    @foreach($tabs as $key => $label)
        <a href="{{ route('weekly-ranking.index') }}?tab={{ $key }}"
            class="px-5 py-2 rounded-lg text-sm font-semibold transition-all
                {{ $activeTab === $key
                    ? 'bg-blue-600 text-white shadow-[0_4px_12px_rgba(37,99,235,0.30)]'
                    : 'bg-white border border-gray-200 text-gray-600 hover:border-blue-400 hover:text-blue-600' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- ── Main 2-column layout ── --}}
<div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-6 mb-8">

    {{-- ══ LEFT: #1 Hero + rank 2-5 grid ══ --}}
    <div class="min-w-0 space-y-4">

        {{-- #1 Hero Card --}}
        @if($topBook)
        <div class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm flex flex-col md:flex-row" style="min-height: 260px;">

            {{-- Book cover — full height left panel --}}
            <div class="relative md:w-[200px] flex-shrink-0 bg-gray-800 overflow-hidden" style="min-height: 260px;">
                {{-- #1 badge --}}
                <div class="absolute top-3 left-3 z-10">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-primary text-white font-black text-base shadow-lg">
                        #1
                    </span>
                </div>
                <img src="{{ $topBook->cover_image_url }}"
                     alt="{{ $topBook->title }}"
                     class="w-full h-full object-cover absolute inset-0">
            </div>

            {{-- Book info — right panel --}}
            <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-primary mb-2">
                        Most Influential Work
                    </p>
                    <h2 class="text-2xl font-black text-gray-900 mb-1 leading-tight"
                        style="font-family: var(--font-heading, 'Lora', serif)">
                        {{ $topBook->title }}
                    </h2>
                    <p class="text-primary font-semibold text-sm mb-3">
                        {{ $topBook->authors->pluck('name')->implode(', ') ?: 'Tác giả không rõ' }}
                    </p>

                    {{-- Stars --}}
                    @php $avg = $topBook->rating_avg ?? 0; @endphp
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-outlined text-[15px] {{ $i <= round($avg) ? 'text-amber-400' : 'text-gray-200' }}"
                                      style="font-variation-settings:'FILL' 1">star</span>
                            @endfor
                        </div>
                        <span class="text-[11px] text-gray-400">
                            [{{ number_format($avg, 1) }}/5 • {{ number_format($topBook->rating_count ?? 0) }} Reviews]
                        </span>
                    </div>

                    {{-- Editorial quote --}}
                    @if($topBook->short_description)
                    <div class="mb-4">
                        <p class="text-[13px] text-gray-500 italic leading-relaxed line-clamp-4">
                            "{{ $topBook->short_description }}"
                        </p>
                        <p class="text-[11px] text-gray-400 font-semibold mt-1.5">— Editorial Review Board</p>
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('books.show', $topBook) }}"
                        class="inline-flex items-center gap-2 bg-primary text-white font-bold px-6 py-2.5 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] text-sm">
                        Mua Ngay
                    </a>
                    <button class="w-9 h-9 rounded-xl border border-gray-200 flex items-center justify-center text-gray-400 hover:text-primary hover:border-primary transition-all flex-shrink-0">
                        <span class="material-symbols-outlined text-[18px]">bookmark</span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- Rank 2–5 grid (2×2) — chỉ lấy 4 cuốn --}}
        @php $gridBooks = $rankedBooks->take(4); @endphp
        @if($gridBooks->isNotEmpty())
        <div class="grid grid-cols-2 gap-3">
            @foreach($gridBooks as $index => $book)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 p-4 flex items-center gap-3 group">
                {{-- Rank --}}
                <span class="text-2xl font-black text-gray-200 leading-none flex-shrink-0 w-7 text-center">
                    {{ sprintf('%02d', $index + 2) }}
                </span>

                {{-- Cover --}}
                <div class="w-12 h-[68px] rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                    <img src="{{ $book->cover_image_url }}"
                         alt="{{ $book->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-900 text-[13px] leading-tight line-clamp-2 mb-1">
                        {{ $book->title }}
                    </p>
                    <p class="text-[11px] text-gray-400 line-clamp-1 mb-1.5">
                        {{ $book->authors->pluck('name')->implode(', ') ?: 'Tác giả không rõ' }}
                    </p>
                    <p class="text-primary font-black text-[13px]">
                        {{ $book->formatted_current_price }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-200 p-10 text-center">
            <span class="material-symbols-outlined text-gray-300 text-4xl block mb-3">analytics</span>
            <p class="text-gray-400 text-sm font-medium">Đang cập nhật bảng xếp hạng...</p>
        </div>
        @endif

    </div>

    {{-- ══ RIGHT: Sidebar ══ --}}
    <aside class="space-y-4">

        {{-- Insight Tuần --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-[11px] font-black uppercase tracking-widest text-gray-800 mb-4">Insight Tuần</p>
            <div class="space-y-3.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-[13px] text-gray-600">
                        <span class="material-symbols-outlined text-[16px] text-emerald-500">trending_up</span>
                        Đề cử mới
                    </div>
                    <span class="font-black text-gray-900 text-[13px]">
                        {{ number_format($insightStats['new_titles']) }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-[13px] text-gray-600">
                        <span class="material-symbols-outlined text-[16px] text-blue-500">person_pin</span>
                        Curator tích cực
                    </div>
                    <span class="font-black text-gray-900 text-[13px]">Minh Triết</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-[13px] text-gray-600">
                        <span class="material-symbols-outlined text-[16px] text-violet-500">forum</span>
                        Lượt thảo luận
                    </div>
                    <span class="font-black text-gray-900 text-[13px]">
                        {{ number_format($insightStats['discussion_count']) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Thịnh Hành --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-[11px] font-black uppercase tracking-widest text-gray-800 mb-3">Thịnh Hành</p>
            <div class="flex flex-wrap gap-1.5">
                @forelse($trendingTags as $tag)
                    <a href="{{ route('books.search', ['q' => ltrim($tag, '#')]) }}"
                        class="px-2.5 py-1 bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 rounded-md text-[11px] font-semibold transition-all">
                        {{ $tag }}
                    </a>
                @empty
                    <p class="text-xs text-gray-400">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>

        {{-- Discussion + Article --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
            <div>
                <p class="text-[9px] font-black uppercase tracking-[0.18em] text-primary mb-1.5">Discussion</p>
                <a href="{{ route('books.search') }}"
                    class="text-[13px] font-bold text-gray-800 hover:text-primary transition-colors leading-snug block mb-1">
                    Hawking và di sản tri thức nhân loại: Một góc nhìn mới?
                </a>
                <p class="text-[11px] text-gray-400">104 bình luận • 2 giờ trước</p>
            </div>
            <div class="border-t border-gray-100 pt-4">
                <p class="text-[9px] font-black uppercase tracking-[0.18em] text-primary mb-1.5">Article</p>
                <a href="{{ route('books.search') }}"
                    class="text-[13px] font-bold text-gray-800 hover:text-primary transition-colors leading-snug block mb-1">
                    Tại sao chúng ta vẫn đọc Kinh Dịch trong thế kỷ 21?
                </a>
                <p class="text-[11px] text-gray-400">69 bình luận • 9 giờ trước</p>
            </div>
        </div>

        {{-- Curator note with photo --}}
        <div class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm">
            <div class="relative h-36 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=600&q=70"
                     alt="Library"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
            </div>
            <div class="bg-white p-4">
                <p class="text-[11px] font-black uppercase tracking-widest text-gray-800 mb-2">Lời ngỏ từ Curator</p>
                <p class="text-[12px] text-gray-500 italic leading-relaxed mb-3">
                    "Mỗi cuốn sách trong danh sách này không chỉ là một sản phẩm, mà là một mạch cảm xúc của mỗi trang tri thức sâu thẳm nhất của nhân loại."
                </p>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-white text-[9px] font-black flex-shrink-0">
                        MT
                    </div>
                    <p class="text-[11px] text-gray-500 font-semibold">Minh Triết, Senior Curator</p>
                </div>
            </div>
        </div>

    </aside>
</div>

{{-- ── Bottom Banners ── --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">

    {{-- Limited Edition — xanh dương đậm --}}
    <div class="bg-blue-700 rounded-2xl p-7 flex flex-col justify-between min-h-[200px] relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-48 h-48 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-white/5 rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-blue-200 mb-3">Limited Edition</p>
            <h3 class="text-white font-black text-xl leading-tight mb-2"
                style="font-family: var(--font-heading, 'Lora', serif)">
                Bộ Sưu Tập Tác<br>Giả Kinh Điển
            </h3>
            <p class="text-blue-200 text-xs leading-relaxed">
                Dành riêng cho những tâm hồn đồng điệu với giá trị thời gian.
            </p>
        </div>
        <a href="{{ route('collections.index') }}"
            class="relative z-10 inline-flex items-center gap-2 bg-white text-blue-700 font-bold px-5 py-2.5 rounded-xl hover:bg-blue-50 transition-all text-sm w-fit mt-5">
            Khám Phá Ngay
        </a>
    </div>

    {{-- Exclusive Coupon — đỏ --}}
    <div class="bg-primary rounded-2xl p-7 flex flex-col justify-between min-h-[200px] relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-48 h-48 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-white/5 rounded-full pointer-events-none"></div>
        <div class="relative z-10 text-center">
            <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center mx-auto mb-3">
                <span class="material-symbols-outlined text-white text-3xl">confirmation_number</span>
            </div>
            <h3 class="text-white font-black text-xl leading-tight mb-1">
                Ưu Đãi Độc<br>Quyền
            </h3>
            <p class="text-white/80 text-xs">Giảm ngay 20% cho<br>thành viên Library Circle</p>
        </div>
        <a href="{{ route('coupon-store.index') }}"
            class="relative z-10 inline-flex items-center justify-center gap-2 bg-white text-primary font-bold px-5 py-2.5 rounded-xl hover:bg-white/90 transition-all text-sm mt-5">
            Nhận Mã
        </a>
    </div>

</div>

@endsection
