{{-- SHOPPING TREND — Xu Hướng Mua Sắm --}}
<section id="shopping-trend" class="scroll-reveal rounded-[2rem] overflow-hidden shadow-[var(--shadow-book)] border border-slate-100/80">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-[#C92127] to-[#f43f5e] min-h-16 px-6 flex items-center gap-4 border-b border-white/10 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')] opacity-10 pointer-events-none"></div>
        <span class="material-symbols-outlined text-white relative z-10 text-2xl animate-pulse">trending_up</span>
        <h2 class="font-[Lora,serif] font-bold text-white text-xl lg:text-2xl tracking-tight flex-1 relative z-10">
            Xu Hướng Mua Sắm
        </h2>
        <span class="bookmark-badge bg-white/20 text-white shadow-lg relative z-10 backdrop-blur-sm">HOT</span>

        {{-- ButtonGroup thời gian — nhỏ gọn góc phải, giữ class .st-period-tabs để JS gốc bind --}}
        <div class="st-period-tabs relative z-10 flex items-center bg-white/15 backdrop-blur-sm rounded-xl p-0.5 gap-0.5"
             role="tablist" aria-label="Lọc theo thời gian">
            @foreach([['day','Ngày'],['week','Tuần'],['month','Tháng'],['year','Năm']] as [$p, $l])
            <button class="px-3 py-1.5 rounded-lg text-[11px] font-bold transition-all duration-200 cursor-pointer
                           {{ $p === 'day' ? 'bg-white text-brand-primary shadow-sm border-b-2 border-brand-primary' : 'text-white/80 hover:text-white hover:bg-white/20 border-b-2 border-transparent' }}"
                    role="tab"
                    aria-selected="{{ $p === 'day' ? 'true' : 'false' }}"
                    data-period="{{ $p }}">{{ $l }}</button>
            @endforeach
        </div>
    </div>

    {{-- ===== TAB CẤP 1: BXH Theo Nhóm Sách / BXH Theo Chủ Đề ===== --}}
    <div class="flex bg-white border-b-2 border-slate-100" id="st-main-tabs">
        <button class="st-main-tab flex-1 flex items-center justify-center gap-2 py-3 text-sm font-black border-b-[3px] border-brand-primary text-brand-primary bg-red-50/40 transition-all duration-200"
                aria-selected="true" data-main-tab="category">
            <span class="material-symbols-outlined text-[17px]">category</span>
            BXH Theo Nhóm Sách
        </button>
        <div class="w-px bg-slate-200 my-2 shrink-0"></div>
        <button class="st-main-tab flex-1 flex items-center justify-center gap-2 py-3 text-sm font-black border-b-[3px] border-transparent text-slate-500 hover:text-brand-primary hover:bg-red-50/20 transition-all duration-200"
                aria-selected="false" data-main-tab="genre">
            <span class="material-symbols-outlined text-[17px]">local_library</span>
            BXH Theo Chủ Đề
        </button>
    </div>

    {{-- ===== PANEL: Nhóm Sách ===== --}}
    <div class="st-main-panel bg-[#F9F7F2]" data-panel="category">

        {{-- Sub-tabs từ DB — giữ nguyên class .st-cat-tabs để JS gốc bind --}}
        @if(isset($rankingCategories) && $rankingCategories->isNotEmpty())
        <div class="tab-scroll-wrapper relative bg-white border-b border-gray-200/60">
            <button class="tab-scroll-btn tab-scroll-left" aria-label="Cuộn trái"><span class="material-symbols-outlined text-[18px]">chevron_left</span></button>
            <div class="st-cat-tabs tab-scroll-inner flex flex-nowrap overflow-x-auto gap-2 px-5 py-3 scrollbar-hide"
                 role="tablist" aria-label="Lọc theo nhóm sách">
                <button class="st-cat-tab tab-pill active" data-category="all">Tất cả</button>
                @foreach($rankingCategories as $cat)
                <button class="st-cat-tab tab-pill" data-category="{{ $cat->id }}">
                    @if($cat->icon)<span class="material-symbols-outlined !text-[12px] mr-0.5">{{ $cat->icon }}</span>@endif
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>
            <button class="tab-scroll-btn tab-scroll-right" aria-label="Cuộn phải"><span class="material-symbols-outlined text-[18px]">chevron_right</span></button>
        </div>
        @endif

        {{-- Grid — JS gốc render vào đây --}}
        <div id="st-grid" class="grid-book-layout p-5 min-h-[400px]">
            @for($i = 0; $i < 6; $i++)
            <div class="st-skeleton animate-pulse bg-white rounded-[6px] overflow-hidden shadow-[var(--shadow-page)] border border-slate-100/80" style="border-left: 3px solid #e2e8f0;">
                <div class="aspect-square bg-gray-200"></div>
                <div class="p-3 space-y-2">
                    <div class="h-3 bg-gray-200 rounded w-full"></div>
                    <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-5 bg-gray-200 rounded w-1/2 mt-3"></div>
                    <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                </div>
            </div>
            @endfor
        </div>

        <div class="flex justify-center py-5 px-4">
            <a href="{{ route('shopping-trend.index') }}" class="btn-view-all-premium"
               aria-label="Xem thêm xu hướng mua sắm">
                <span class="material-symbols-outlined">local_fire_department</span>
                Xem Thêm
            </a>
        </div>
    </div>

    {{-- ===== PANEL: Chủ Đề ===== --}}
    <div class="st-main-panel hidden bg-[#F9F7F2]" data-panel="genre">
        @php
            $genreColors = [
                'from-violet-500 to-purple-600','from-emerald-500 to-teal-600',
                'from-sky-500 to-blue-600','from-amber-400 to-orange-500',
                'from-rose-500 to-pink-600','from-cyan-500 to-blue-500',
                'from-stone-500 to-slate-600','from-fuchsia-500 to-purple-600',
            ];
        @endphp

        {{-- Sub-tabs chủ đề từ DB — cũng dùng .st-cat-tabs để JS gốc bind khi panel active --}}
        @if(isset($rankingCategories) && $rankingCategories->isNotEmpty())
        <div class="tab-scroll-wrapper relative bg-white border-b border-gray-200/60">
            <button class="tab-scroll-btn tab-scroll-left" aria-label="Cuộn trái"><span class="material-symbols-outlined text-[18px]">chevron_left</span></button>
            <div class="st-cat-tabs tab-scroll-inner flex flex-nowrap overflow-x-auto gap-2 px-5 py-3 scrollbar-hide">
                @foreach($rankingCategories as $i => $cat)
                @php
                    $color = !empty($cat->bg_gradient) ? $cat->bg_gradient : ($genreColors[$i % count($genreColors)]);
                    $icon  = $cat->icon ?: 'local_library';
                @endphp
                <button class="st-cat-tab tab-pill {{ $i === 0 ? 'active' : '' }} flex items-center gap-1.5"
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
        <div class="px-5 py-2 bg-white border-b border-slate-100 flex flex-wrap gap-1.5 items-center">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mr-1">Lọc sâu:</span>
            @foreach(['Bestseller','Mới nhất','Giá tốt','Bìa cứng','Sách khổ lớn'] as $tag)
            <button class="st-tag-btn px-2.5 py-0.5 rounded-full text-[10px] font-bold border border-slate-200 text-slate-500 bg-white hover:border-brand-primary/50 hover:text-brand-primary transition-all duration-150 cursor-pointer"
                    data-tag="{{ Str::slug($tag) }}">{{ $tag }}</button>
            @endforeach
        </div>

        {{-- Grid chủ đề — JS render vào #st-grid-genre (tránh duplicate id với panel category) --}}
        <div id="st-grid-genre" class="grid-book-layout p-5 min-h-[400px]">
            @for($i = 0; $i < 6; $i++)
            <div class="animate-pulse bg-white rounded-[6px] overflow-hidden shadow-[var(--shadow-page)] border border-slate-100/80" style="border-left: 3px solid #e2e8f0;">
                <div class="aspect-square bg-gray-200"></div>
                <div class="p-3 space-y-2">
                    <div class="h-3 bg-gray-200 rounded w-full"></div>
                    <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-5 bg-gray-200 rounded w-1/2 mt-3"></div>
                </div>
            </div>
            @endfor
        </div>

        <div class="flex justify-center py-5 px-4">
            <a href="{{ route('shopping-trend.index') }}" class="btn-view-all-premium">
                <span class="material-symbols-outlined">local_fire_department</span>
                Xem Thêm
            </a>
        </div>
    </div>

</section>

{{-- Tab toggle + genre/tag logic handled in resources/js/home/shopping-trend.js --}}
