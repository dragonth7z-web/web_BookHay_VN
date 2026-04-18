{{-- FLASH SALE — Fahasa Style --}}
@if(isset($activeFlashSale) && $activeFlashSale)
    <meta name="flash-sale-end" content="{{ $activeFlashSale->end_date?->toIso8601String() ?? '' }}">
@endif

<section
    class="py-8 relative rounded-[1.5rem] fs-midnight-container group/fs border border-white/5 shadow-2xl my-6 mx-2 sm:mx-0 overflow-hidden"
    id="flash-sale">
    {{-- Top Flare effect --}}
    <div class="absolute -top-24 -left-24 w-64 h-64 bg-red-600/20 blur-[100px] pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-orange-600/20 blur-[100px] pointer-events-none"></div>

    <div class="px-6 sm:px-10 relative z-10">
        {{-- Header: Neon & Digital --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 gap-6">
            <div class="flex flex-col gap-4">
                {{-- Flash Badge --}}
                <div
                    class="inline-flex items-center gap-2 bg-red-600 text-white px-3 py-1 rounded-full text-[10px] font-black tracking-widest w-fit shadow-[0_0_15px_rgba(220,38,38,0.5)]">
                    <span class="material-symbols-outlined text-xs animate-pulse">bolt</span>
                    LIMITED TIME ONLY
                </div>

                <h2
                    class="text-3xl sm:text-5xl font-black neon-red-glow neon-flicker-slow tracking-tighter italic flex items-center gap-4">
                    FLASH SALE
                    <div class="flex items-center justify-center w-10 h-10 sm:w-16 sm:h-16">
                        <span
                            class="material-symbols-outlined text-3xl sm:text-5xl font-[FILL_1] animate-[fsBoltPulse_1.5s_infinite_alternate] leading-none">bolt</span>
                    </div>
                </h2>

                <div class="flex items-center gap-3 text-white/80 text-sm font-medium">
                    <div
                        class="flex items-center gap-1.5 bg-white/10 backdrop-blur-sm px-3 py-1.5 rounded-lg border border-white/20">
                        <span
                            class="material-symbols-outlined text-yellow-400 text-lg font-[FILL_1]">local_fire_department</span>
                        @php
                            $viewerMin = (int) ($configs['flash_sale_viewer_min'] ?? 50);
                            $viewerMax = (int) ($configs['flash_sale_viewer_max'] ?? 200);
                            $initialViewer = rand($viewerMin, $viewerMax);
                        @endphp
                        <span id="fs-viewer-num" data-min="{{ $viewerMin }}" data-max="{{ $viewerMax }}"
                            class="text-white font-black">{{ $initialViewer }}</span>
                        <span class="text-white font-bold">đang săn deal</span>
                    </div>
                </div>
            </div>

            {{-- Countdown: Digital Clock Style --}}
            @isset($activeFlashSale)
                <div class="flex flex-col items-start md:items-end gap-3">
                    <span class="text-xs font-bold text-white uppercase tracking-widest drop-shadow-sm">Kết thúc
                        trong</span>
                    <div class="flex items-center gap-3">
                        {{-- Giờ --}}
                        <div class="fs-digital-box">
                            <div class="fs-digital-digit-container" id="fs-hours-wrap">
                                <div class="fs-digital-digit">0</div>
                                <div class="fs-digital-digit">0</div>
                            </div>
                            <span
                                class="text-[10px] font-black text-yellow-300 uppercase mt-1.5 drop-shadow-sm">Hours</span>
                        </div>
                        <span class="text-2xl font-black text-white animate-pulse mb-6">:</span>
                        {{-- Phút --}}
                        <div class="fs-digital-box">
                            <div class="fs-digital-digit-container" id="fs-minutes-wrap">
                                <div class="fs-digital-digit">0</div>
                                <div class="fs-digital-digit">0</div>
                            </div>
                            <span
                                class="text-[10px] font-black text-yellow-300 uppercase mt-1.5 drop-shadow-sm">Minutes</span>
                        </div>
                        <span class="text-2xl font-black text-white animate-pulse mb-6">:</span>
                        {{-- Giây --}}
                        <div class="fs-digital-box">
                            <div class="fs-digital-digit-container" id="fs-seconds-wrap">
                                <div class="fs-digital-digit">0</div>
                                <div class="fs-digital-digit">0</div>
                            </div>
                            <span
                                class="text-[10px] font-black text-yellow-300 uppercase mt-1.5 drop-shadow-sm">Seconds</span>
                        </div>
                    </div>
                </div>
            @endisset
        </div>

        {{-- Product Grid: Back to Standardized Layout --}}
        <div class="relative group/fs mt-8">
            <div class="grid-book-layout">
                @forelse($flashSaleBooks->take(15) as $index => $book)
                    <x-book-card :book="$book" :showProgress="true" />
                @empty
                    <div
                        class="w-full col-span-full flex flex-col items-center justify-center gap-4 py-20 bg-white/10 rounded-3xl border border-dashed border-white/20">
                        <div class="w-20 h-20 rounded-full bg-white/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-slate-100 animate-pulse">timer_off</span>
                        </div>
                        <div class="text-center">
                            <h4 class="text-white font-bold text-lg">Chờ đón bão Deal!</h4>
                            <p class="text-slate-200 text-sm">Chương trình Flash Sale tiếp theo đang được chuẩn bị.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Footer Action --}}
        @if($flashSaleBooks->count() > 0)
            <div class="flex justify-center mt-10">
                <a href="{{ route('books.search') }}"
                    class="group relative flex items-center gap-3 px-8 py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl transition-all duration-300">
                    <span class="text-white font-black text-sm tracking-widest uppercase">Khám phá toàn bộ Deal</span>
                    <span
                        class="material-symbols-outlined text-red-500 transition-transform group-hover:translate-x-2">arrow_forward</span>
                    <div
                        class="absolute inset-0 bg-red-600/10 blur-xl scale-0 group-hover:scale-100 transition-transform duration-500 rounded-full">
                    </div>
                </a>
            </div>
        @endif
    </div>
</section>

<style>
    /* Overlay specific logic to inject the Fire Progress Class into the standard component */
    #flash-sale .card-body .animate-shimmer-sweep {
        @apply flash-fire-progress;
    }

    #flash-sale .card-body .bg-slate-100 {
        @apply bg-white/10 border-white/10;
    }

    #flash-sale .product-card-container {
        @apply transform-none;
        /* Reset book tilt for flash sale cards to keep it clean */
    }
</style>

{{-- flash-sale.js is bundled in resources/js/app.js --}}