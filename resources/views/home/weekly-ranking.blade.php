{{-- WEEKLY RANKING --}}
@php use App\Http\Resources\BookResource; @endphp

<section id="weekly-ranking" class="scroll-reveal rounded-[2rem] overflow-hidden shadow-[var(--shadow-book)] border border-slate-100/80">

    {{-- HEADER + ButtonGroup thời gian --}}
    <div class="bg-gradient-to-r from-[#1e293b] to-[#334155] min-h-12 px-4 flex items-center gap-3 border-b border-white/5 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')] opacity-10 pointer-events-none"></div>
        <span class="material-symbols-outlined text-amber-400 relative z-10 text-lg">emoji_events</span>
        <h2 class="font-[Lora,serif] font-bold text-white text-base lg:text-lg tracking-tight flex-1 relative z-10">
            Bảng Xếp Hạng Bán Chạy
        </h2>
        <span class="bookmark-badge bg-brand-primary text-white shadow-lg relative z-10">HOT</span>

        {{-- ButtonGroup thời gian nhỏ gọn --}}
        <div class="relative z-10 flex items-center bg-white/10 backdrop-blur-sm rounded-xl p-0.5 gap-0.5">
            @foreach([['week','Tuần'],['month','Tháng'],['year','Năm']] as [$p, $l])
            <button class="wr-period-btn px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all duration-200 cursor-pointer
                           {{ $p === 'week' ? 'bg-white text-slate-800 shadow-sm' : 'text-white/70 hover:text-white hover:bg-white/15' }}"
                    data-period="{{ $p }}">{{ $l }}</button>
            @endforeach
        </div>
    </div>

    {{-- ===== TAB CẤP 1: BXH Theo Nhóm Sách / BXH Theo Chủ Đề ===== --}}
    <div class="flex bg-white border-b-2 border-slate-100" id="wr-main-tabs">
        <button class="wr-main-tab flex-1 flex items-center justify-center gap-2 py-3 text-sm font-black border-b-[3px] border-brand-primary text-brand-primary bg-red-50/40 transition-all duration-200"
                aria-selected="true" data-main-tab="category">
            <span class="material-symbols-outlined text-[17px]">category</span>
            BXH Theo Nhóm Sách
        </button>
        <div class="w-px bg-slate-200 my-2 shrink-0"></div>
        <button class="wr-main-tab flex-1 flex items-center justify-center gap-2 py-3 text-sm font-black border-b-[3px] border-transparent text-slate-500 hover:text-brand-primary hover:bg-red-50/20 transition-all duration-200"
                aria-selected="false" data-main-tab="genre">
            <span class="material-symbols-outlined text-[17px]">local_library</span>
            BXH Theo Chủ Đề
        </button>
    </div>

    @if($weeklyRankings->isNotEmpty())

    @php
        $booksData = BookResource::collection($weeklyRankings->take(6))->resolve();
        foreach ($booksData as $i => &$b) { $b['index'] = $i; $b['rank'] = $i + 1; }
        unset($b);
        $booksJson = json_encode($booksData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);

        $wrColors = [
            'from-violet-500 to-purple-600','from-emerald-500 to-teal-600',
            'from-sky-500 to-blue-600','from-amber-400 to-orange-500',
            'from-rose-500 to-pink-600','from-cyan-500 to-blue-500',
            'from-stone-500 to-slate-600','from-fuchsia-500 to-purple-600',
        ];
    @endphp

    {{-- ===== PANEL: Nhóm Sách ===== --}}
    <div class="wr-panel" data-panel="category">
        {{-- Sub-tabs từ DB — giữ class .wr-tabs để WeeklyRankingManager bind --}}
        <div class="tab-scroll-wrapper relative bg-[#F9F7F2] border-b border-gray-200/60">
            <button class="tab-scroll-btn tab-scroll-left" aria-label="Cuộn trái"><span class="material-symbols-outlined text-[18px]">chevron_left</span></button>
            <div class="wr-tabs tab-scroll-inner flex flex-nowrap overflow-x-auto gap-2 px-4 py-2.5 scrollbar-hide"
                 role="tablist" aria-label="Lọc theo nhóm sách">
                <button class="wr-tab tab-pill active" role="tab" aria-selected="true" data-category="all">Tất cả</button>
                @if(isset($rankingCategories) && $rankingCategories->isNotEmpty())
                    @foreach($rankingCategories as $cat)
                    <button class="wr-tab tab-pill" role="tab" aria-selected="false" data-category="{{ $cat->id }}">
                        @if($cat->icon)<span class="material-symbols-outlined !text-[12px] mr-0.5">{{ $cat->icon }}</span>@endif
                        {{ $cat->name }}
                    </button>
                    @endforeach
                @endif
            </div>
            <button class="tab-scroll-btn tab-scroll-right" aria-label="Cuộn phải"><span class="material-symbols-outlined text-[18px]">chevron_right</span></button>
        </div>

        {{-- 2-col body — giữ class .wr-body và data-books để WeeklyRankingManager đọc --}}
        <div class="wr-body grid grid-cols-1 lg:grid-cols-[42%_58%] min-h-[400px]"
             data-books="{{ $booksJson }}">
            <div class="wr-list bg-white lg:border-r border-gray-100/80" id="wr-list" data-wr-list></div>
            <div class="wr-preview overflow-y-auto" id="wr-preview" data-wr-preview></div>
        </div>
    </div>

    {{-- ===== PANEL: Chủ Đề ===== --}}
    <div class="wr-panel hidden" data-panel="genre">
        {{-- Sub-tabs chủ đề từ DB --}}
        @if(isset($rankingCategories) && $rankingCategories->isNotEmpty())
        <div class="tab-scroll-wrapper relative bg-[#F9F7F2] border-b border-gray-200/60">
            <button class="tab-scroll-btn tab-scroll-left" aria-label="Cuộn trái"><span class="material-symbols-outlined text-[18px]">chevron_left</span></button>
            <div class="wr-tabs tab-scroll-inner flex flex-nowrap overflow-x-auto gap-2 px-4 py-2.5 scrollbar-hide">
                @foreach($rankingCategories as $i => $cat)
                @php
                    $color = !empty($cat->bg_gradient) ? $cat->bg_gradient : ($wrColors[$i % count($wrColors)]);
                    $icon  = $cat->icon ?: 'local_library';
                @endphp
                <button class="wr-tab tab-pill {{ $i === 0 ? 'active' : '' }} flex items-center gap-1.5"
                        data-category="{{ $cat->id }}" data-color="{{ $color }}">
                    <span class="material-symbols-outlined !text-[13px]">{{ $icon }}</span>
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>
            <button class="tab-scroll-btn tab-scroll-right" aria-label="Cuộn phải"><span class="material-symbols-outlined text-[18px]">chevron_right</span></button>
        </div>
        @endif

        {{-- Tags lọc sâu --}}
        <div class="px-4 py-2 bg-white border-b border-slate-100 flex flex-wrap gap-1.5 items-center">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mr-1">Lọc sâu:</span>
            @foreach(['Bestseller','Mới nhất','Giá tốt','Bìa cứng','Sách khổ lớn'] as $tag)
            <button class="wr-tag-btn px-2.5 py-0.5 rounded-full text-[10px] font-bold border border-slate-200 text-slate-500 bg-white hover:border-brand-primary/50 hover:text-brand-primary transition-all duration-150 cursor-pointer"
                    data-tag="{{ Str::slug($tag) }}">{{ $tag }}</button>
            @endforeach
        </div>

        {{-- 2-col body cho chủ đề --}}
        <div class="wr-body grid grid-cols-1 lg:grid-cols-[42%_58%] min-h-[400px]"
             data-books="{{ $booksJson }}">
            <div class="wr-list bg-white lg:border-r border-gray-100/80" id="wr-list-genre" data-wr-list></div>
            <div class="wr-preview overflow-y-auto" id="wr-preview-genre" data-wr-preview></div>
        </div>
    </div>

    @else
    <div class="flex flex-col items-center justify-center gap-3 py-12 px-4 text-gray-400 text-center bg-[#F9F7F2]">
        <span class="material-symbols-outlined text-5xl opacity-40">analytics</span>
        <p class="text-sm">Đang cập nhật bảng xếp hạng...</p>
    </div>
    @endif

    @if($weeklyRankings->isNotEmpty())
    <div class="flex justify-center py-4 px-4 bg-white border-t border-slate-100">
        <a href="{{ route('books.search', ['sort' => 'weekly_ranking']) }}" class="btn-view-all-premium"
           aria-label="Xem trọn bộ bảng xếp hạng">
            <span class="material-symbols-outlined">auto_stories</span>
            Xem trọn bộ Bảng Xếp Hạng
        </a>
    </div>
    @endif

</section>

{{-- Tab toggle + genre/period/tag logic handled in resources/js/home/weekly-ranking.js --}}
