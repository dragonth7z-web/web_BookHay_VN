{{-- ========================================================
CATEGORY NAVBAR — Thanh danh mục nằm ngang
Dữ liệu thật từ DB: $sidebarCategories (từ HomeRepository)
Sử dụng Tailwind CSS "Bảo trì vàng"
======================================================== --}}
<nav class="bg-white dark:bg-slate-900 border-b-2 border-slate-100 dark:border-slate-800 relative z-40 shadow-[0_2px_8px_rgba(0,0,0,0.04)] dark:shadow-none"
    aria-label="Danh mục sách">
    <div class="max-w-[1100px] mx-auto px-4 flex items-center gap-1 overflow-x-auto no-scrollbar relative py-1">

        {{-- Flash Sale shortcut (nổi bật nhất) --}}
        <a href="#flash-sale"
            class="flex items-center gap-1 px-3 py-1.5 min-w-max text-[13px] font-bold text-primary bg-red-50 dark:bg-red-900/20 rounded-md hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors my-1 border border-transparent">
            <span class="material-symbols-outlined text-primary text-base font-[FILL_1]">bolt</span>
            Flash Sale
            <span
                class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-red-200 text-primary dark:bg-red-950 dark:text-red-300 ml-1">Hot</span>
        </a>

        <div class="w-px h-4 bg-slate-200 dark:bg-slate-700 mx-2 flex-shrink-0"></div>

        {{-- Dynamic categories từ DB --}}
        @if(isset($sidebarCategories) && $sidebarCategories->isNotEmpty())
            @foreach($sidebarCategories->take(10) as $cat)
                @php
                    $isActive = request()->get('category') == $cat->id;
                @endphp
                <a href="{{ route('books.search', ['category' => $cat->id]) }}"
                    class="flex items-center gap-1.5 px-3 py-2 min-w-max text-[13px] font-semibold border-b-2 transition-all 
                                  {{ $isActive ? 'text-primary border-primary' : 'text-slate-700 dark:text-slate-300 border-transparent hover:text-primary dark:hover:text-primary hover:border-primary' }}">

                    <span
                        class="material-symbols-outlined text-base {{ $isActive ? 'text-primary font-[FILL_1]' : 'text-slate-400 dark:text-slate-500 font-[FILL_0] group-hover:text-primary' }}">
                        {{ $cat->icon ?: 'menu_book' }}
                    </span>

                    {{ Str::limit($cat->name, 20) }}

                    @if($cat->badge_text)
                        @php
                            $badgeCls = strtolower($cat->badge_text) === 'hot'
                                ? 'bg-red-100 text-primary dark:bg-red-900/50 dark:text-red-300'
                                : (strtolower($cat->badge_text) === 'new'
                                    ? 'bg-green-100 text-green-600 dark:bg-green-900/50 dark:text-green-300'
                                    : 'bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-300');
                        @endphp
                        <span
                            class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider leading-none ml-0.5 {{ $badgeCls }}">
                            {{ $cat->badge_text }}
                        </span>
                    @endif
                </a>
            @endforeach
        @else
            {{-- Static fallback nếu DB chưa có data --}}
            <a href="{{ route('books.search') }}?genre=van-hoc"
                class="flex items-center gap-1.5 px-3 py-2 min-w-max text-[13px] font-semibold text-slate-700 dark:text-slate-300 border-b-2 border-transparent hover:text-primary hover:border-primary">
                <span class="material-symbols-outlined text-base text-slate-400">auto_stories</span>Văn học
            </a>
            <a href="{{ route('books.search') }}?genre=kinh-te"
                class="flex items-center gap-1.5 px-3 py-2 min-w-max text-[13px] font-semibold text-slate-700 dark:text-slate-300 border-b-2 border-transparent hover:text-primary hover:border-primary">
                <span class="material-symbols-outlined text-base text-slate-400">trending_up</span>Kinh tế
            </a>
        @endif

        <div class="w-px h-4 bg-slate-200 dark:bg-slate-700 mx-2 flex-shrink-0"></div>

        {{-- Xem thêm tất cả --}}
        <a href="{{ route('books.search') }}"
            class="group flex items-center gap-1 px-4 py-2 min-w-max text-[13px] font-bold text-primary hover:text-primary-hover transition-colors">
            Xem thêm
            <span
                class="material-symbols-outlined text-[14px] transition-transform group-hover:translate-x-0.5">chevron_right</span>
        </a>

        {{-- Gradient fade ở bên phải thanh trượt --}}
        <div
            class="pointer-events-none absolute right-0 top-0 bottom-0 w-16 bg-gradient-to-l from-white dark:from-slate-900 to-transparent">
        </div>
    </div>
</nav>
