{{-- COMBO TRENDING — Đồng bộ phong cách trang chủ --}}
@if(isset($combos) && $combos->count())

    @php
        $typeLabels = [
            'genre_combo' => 'Combo Kinh Tế',
            'series' => 'Combo Văn Học',
            'author_combo' => 'Combo Tác Giả',
        ];

        $comboCategories = $combos->groupBy(function ($c) {
            // Lấy category name của cuốn sách đầu tiên trong combo
            $firstCat = $c->books->map(function ($b) {
                return $b->category?->name;
            })->filter()->first();

            if ($firstCat) {
                return 'Combo ' . $firstCat;
            }

            // Fallback về mapping theo type
            return $typeLabels[$c->type] ?? 'Combo Đặc Biệt';
        });
    @endphp

    <section id="combo-trending"
        class="scroll-reveal bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden py-8 md:py-10">

        {{-- ── Section Header (chuẩn x-section-header pattern) ── --}}
        <div class="px-6 md:px-8 mb-6">
            <div class="flex items-center justify-between gap-4">
                {{-- Left: Icon + Title --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-primary/10">
                        <span class="material-symbols-outlined text-primary text-2xl">auto_awesome_mosaic</span>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-slate-900 leading-tight tracking-tight"
                            style="font-family: var(--font-heading, 'Lora', serif);">
                            Combo Nổi Bật
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5 font-medium">Mua theo bộ — giá cực hời</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Category Tabs ── --}}
        <div class="tab-scroll-wrapper relative bg-[#F9F7F2] border-b border-gray-200/60">
            <button class="tab-scroll-btn tab-scroll-left" aria-label="Cuộn trái"><span class="material-symbols-outlined text-[18px]">chevron_left</span></button>
            <div class="ct-tabs tab-scroll-inner flex flex-nowrap overflow-x-auto gap-2 px-6 md:px-8 py-4 scrollbar-hide"
                role="tablist" aria-label="Lọc combo theo thể loại">
                <button class="ct-tab tab-pill active" role="tab" aria-selected="true" data-cat="all">Tất cả</button>
                @foreach($comboCategories->keys() as $catKey)
                    <button class="ct-tab tab-pill" role="tab" aria-selected="false" data-cat="{{ $catKey }}">{{ $catKey }}</button>
                @endforeach
            </div>
            <button class="tab-scroll-btn tab-scroll-right" aria-label="Cuộn phải"><span class="material-symbols-outlined text-[18px]">chevron_right</span></button>
        </div>

        {{-- ── Combo Card Panels ── --}}
        <div class="px-6 md:px-8 mt-6">

            {{-- Panel Tất cả --}}
            <div class="ct-panel" data-panel="all">
                <div class="grid-book-layout">
                    @foreach($combos->take(10) as $combo)
                        @include('home.combo-card-item', ['combo' => $combo])
                    @endforeach
                </div>
            </div>

            @foreach($comboCategories as $catKey => $catCombos)
                <div class="ct-panel hidden" data-panel="{{ $catKey }}">
                    <div class="grid-book-layout">
                        @foreach($catCombos->take(5) as $combo)
                            @include('home.combo-card-item', ['combo' => $combo])
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── View All Button ── --}}
        <div class="flex justify-center mt-8 px-6 md:px-8">
            <a href="{{ route('combo.index') }}" class="btn-view-all-premium group">
                <span
                    class="material-symbols-outlined text-xl transition-transform group-hover:rotate-12">auto_awesome_mosaic</span>
                Xem tất cả combo tiết kiệm
            </a>
        </div>

    </section>

    <script>
        (function () {
            const section = document.getElementById('combo-trending');
            if (!section) return;
            section.querySelectorAll('.ct-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    const cat = tab.dataset.cat;

                    // Update tabs
                    section.querySelectorAll('.ct-tab').forEach(t => {
                        const active = t.dataset.cat === cat;
                        t.classList.toggle('active', active);
                        if (active) {
                            t.classList.remove('border-slate-200', 'text-slate-500', 'bg-white');
                            t.classList.add('border-transparent');
                        } else {
                            t.classList.add('border-slate-200', 'text-slate-500', 'bg-white');
                            t.classList.remove('active', 'border-transparent');
                        }
                        t.setAttribute('aria-selected', active ? 'true' : 'false');
                    });

                    // Show/hide panels with fade
                    section.querySelectorAll('.ct-panel').forEach(p => {
                        const show = p.dataset.panel === cat;
                        if (show) {
                            p.classList.remove('hidden');
                            p.style.opacity = 0;
                            requestAnimationFrame(() => {
                                p.style.transition = 'opacity 0.3s ease';
                                p.style.opacity = 1;
                            });
                        } else {
                            p.classList.add('hidden');
                        }
                    });
                });
            });
        })();
    </script>
@endif