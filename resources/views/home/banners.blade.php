@php
    $hasMain = isset($mainBanners) && $mainBanners->count() > 0;
    $hasMini = isset($miniBanners) && $miniBanners->count() > 0;

    if (!function_exists('getBannerUrl')) {
        function getBannerUrl($url)
        {
            if (empty($url))
                return asset('images/home/hero-bg.png');
            return preg_match('/^https?:\/\//', $url) ? $url : asset('storage/' . $url);
        }
    }
@endphp

@if(!$hasMain)
    {{-- CASE 0: Static Hero Fallback --}}
    <section class="mb-8 scroll-reveal">
        <div
            class="w-full min-h-[480px] bg-gradient-to-br from-slate-50 to-slate-200 dark:from-slate-900 dark:to-slate-800 flex items-center justify-center p-8 md:p-16 rounded-[2.5rem] overflow-hidden relative shadow-sm border border-slate-100 dark:border-white/5">
            <div
                class="grid grid-cols-1 lg:grid-cols-[1.2fr_0.8fr] gap-12 max-w-6xl w-full items-center z-10 text-center lg:text-left">
                <div data-aos="fade-right">
                    <span
                        class="inline-block text-[10px] font-black text-primary tracking-[0.2em] mb-6 px-4 py-2 bg-red-500/5 rounded-full uppercase">Kính
                        chào quý khách</span>
                    <h1 class="font-heading text-3xl md:text-5xl lg:text-6xl font-black leading-[1.1] text-slate-900 dark:text-white mb-6 tracking-tight"
                        style="font-family: var(--font-heading, 'Lora', serif);">THLD Bookstore</h1>
                    <p
                        class="text-base md:text-lg text-slate-500 dark:text-slate-400 leading-relaxed mb-10 max-w-lg mx-auto lg:mx-0 font-medium">
                        Hệ thống đang chuẩn bị những ưu đãi và đầu sách tốt nhất dành cho bạn. Đừng bỏ lỡ bất kỳ điều gì!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#all-products"
                            class="px-10 py-4 bg-primary text-white rounded-[4px] font-black uppercase tracking-widest shadow-brand hover:bg-primary-dark hover:-translate-y-1 transition-all duration-300 text-sm">Khám
                            phá ngay</a>
                        <a href="{{ route('books.search') }}"
                            class="px-10 py-4 border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded-[4px] font-black uppercase tracking-widest hover:border-primary hover:text-primary hover:-translate-y-1 transition-all duration-300 text-sm">Xem
                            tất cả</a>
                    </div>
                </div>
            </div>
            {{-- Visual decoration --}}
            <div
                class="absolute -top-20 -right-20 w-[600px] h-[600px] bg-[radial-gradient(circle,rgba(201,33,39,0.05)_0%,transparent_70%)] rounded-full animate-blob-float">
            </div>
        </div>
    </section>
@else
    {{-- CASE 1: Dynamic Layout (Tailwind) --}}
    <section class="grid grid-cols-1 {{ $hasMini ? 'lg:grid-cols-10' : '' }} gap-4 md:gap-5 mb-6 md:mb-8 scroll-reveal">

        {{-- ── Main Hero Slider ── --}}
        <div class="{{ $hasMini ? 'lg:col-span-7' : '' }} hero-slider-wrap relative overflow-hidden rounded-[2rem] w-full h-[360px] md:h-[440px] lg:h-[500px] shadow-premium border border-black/5 dark:border-white/5"
            role="region" aria-label="Banner quảng cáo">
            <div class="hero-slider relative w-full h-full" id="heroSlider">
                @foreach($mainBanners as $index => $banner)
                    @php $bannerImg = getBannerUrl($banner->image_url ?: $banner->image); @endphp
                    <div
                        class="hero-slide absolute inset-0 flex items-center opacity-0 transition-opacity duration-1000 ease-in-out pointer-events-none {{ $index === 0 ? 'active opacity-100 pointer-events-auto' : '' }}">
                        <div class="slide-bg absolute inset-0 bg-cover bg-center bg-no-repeat opacity-30 transition-transform duration-[10s] ease-linear {{ $index === 0 ? 'scale-110 opacity-50' : '' }}"
                            style="background-image: url('{{ $bannerImg }}')">
                        </div>
                        <div
                            class="slide-overlay absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/40 to-transparent">
                        </div>
                        <div class="slide-content-wrapper relative z-10 px-8 md:px-16 lg:px-20 h-full flex items-center">
                            <div class="max-w-xl animate-slide-in-left">
                                <span
                                    class="inline-block text-[10px] font-black text-rose-400 uppercase tracking-[0.3em] mb-5 px-3 py-1 bg-white/5 backdrop-blur-sm rounded-lg border border-white/10">{{ $banner->badge_text ?? 'ƯU ĐÃI ĐỘC QUYỀN' }}</span>
                                <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-black text-white leading-[1.1] mb-6 tracking-tight drop-shadow-2xl"
                                    style="font-family: var(--font-heading, 'Lora', serif);">
                                    {{ $banner->title ?? 'Thế Giới Sách Trong Tầm Tay' }}
                                </h2>
                                <p class="text-sm md:text-base text-white/70 leading-relaxed mb-10 max-w-md font-medium">
                                    {{ $banner->description ?? '🔥 Khám phá hàng ngàn tựa sách mới nhất với ưu đãi lên đến 50%. Miễn phí vận chuyển cho đơn hàng từ 200k.' }}
                                </p>
                                <div class="flex items-center gap-5">
                                    <a href="{{ $banner->url ?? '#' }}"
                                        class="inline-flex px-10 py-4 bg-primary text-white font-black rounded-[4px] hover:bg-primary-dark transition-all duration-500 tracking-widest shadow-brand hover:-translate-y-1 text-xs uppercase animate-attention-burst-infinite">
                                        {{ $banner->button_text ?? 'Khám phá ngay' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Autoplay progress bar --}}
            <div class="slide-progress-bar" id="slideProgressBar"></div>

            {{-- Prev / Next buttons --}}
            @if($mainBanners->count() > 1)
                <button
                    class="slider-btn slider-prev z-30 bg-white/10 backdrop-blur-md border border-white/20 transition-all duration-300 hover:bg-white/25 hover:border-white/50 hover:scale-105 w-12 h-12 rounded-2xl flex items-center justify-center left-4 group"
                    id="heroPrev" aria-label="Slide trước"><span
                        class="material-symbols-outlined text-white transition-transform group-hover:-translate-x-1">chevron_left</span></button>
                <button
                    class="slider-btn slider-next z-30 bg-white/10 backdrop-blur-md border border-white/20 transition-all duration-300 hover:bg-white/25 hover:border-white/50 hover:scale-105 w-12 h-12 rounded-2xl flex items-center justify-center right-4 group"
                    id="heroNext" aria-label="Slide sau"><span
                        class="material-symbols-outlined text-white transition-transform group-hover:translate-x-1">chevron_right</span></button>

                {{-- Dots navigation --}}
                <div class="slider-dots z-30 bg-black/20 backdrop-blur-md rounded-2xl px-3 py-2 bottom-6 left-1/2 -translate-x-1/2"
                    id="sliderDots">
                    @foreach($mainBanners as $index => $banner)
                        <button class="slider-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"
                            aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── Mini Banners (Adaptive Grid) ── --}}
        @if($hasMini)
            <div class="lg:col-span-3 hidden lg:flex flex-col gap-4 h-[500px]">
                @foreach($miniBanners->take(2) as $banner)
                    @php $miniBannerImg = getBannerUrl($banner->image_url ?: $banner->image); @endphp
                    <a href="{{ $banner->url ?? '#' }}"
                        class="mini-banner flex-1 min-h-0 rounded-[2rem] overflow-hidden relative shadow-premium block transition-all duration-700 hover:-translate-y-2 hover:shadow-2xl group">
                        <img src="{{ $miniBannerImg }}" alt="{{ $banner->title ?? 'Mini banner' }}" loading="lazy"
                            class="mini-banner-img w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/10 to-transparent flex flex-col justify-end p-6 transition-all duration-700 group-hover:from-primary/80 z-20">
                            <span
                                class="text-white text-sm font-black truncate w-full shadow-black drop-shadow-xl tracking-tight">{{ $banner->title ?? '' }}</span>
                        </div>
                        <div
                            class="absolute top-0 -left-[100%] w-1/2 h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -skew-x-[25deg] group-hover:animate-shimmer-slide z-30">
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endif

{{-- ── Service Bar (Premium) ── --}}
<section class="mb-2 scroll-reveal">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $services = [
                ['icon' => 'local_shipping', 'title' => $configs['service_ship_title'] ?? 'Miễn phí vận chuyển', 'sub' => $configs['service_ship_sub'] ?? 'Đơn hàng từ 200.000đ'],
                ['icon' => 'autorenew', 'title' => $configs['service_return_title'] ?? 'Đổi trả dễ dàng', 'sub' => $configs['service_return_sub'] ?? 'Trong vòng 7 ngày'],
                ['icon' => 'verified', 'title' => $configs['service_genuine_title'] ?? '100% Chính hãng', 'sub' => $configs['service_genuine_sub'] ?? 'Cam kết sách thật'],
                ['icon' => 'card_membership', 'title' => $configs['service_member_title'] ?? 'Thành viên VIP', 'sub' => $configs['service_member_sub'] ?? 'Ưu đãi độc quyền'],
            ];
        @endphp
        @foreach($services as $service)
            <div
                class="group flex flex-col md:flex-row items-center md:items-start text-center md:text-left gap-4 p-6 bg-white dark:bg-slate-900 rounded-[6px] border border-slate-100 dark:border-white/5 shadow-sm hover:shadow-premium hover:border-brand-primary/20 hover:-translate-y-1 transition-all duration-500 cursor-pointer">
                <div
                    class="w-12 h-12 rounded-[4px] bg-slate-50 dark:bg-slate-800 text-primary flex items-center justify-center flex-shrink-0 group-hover:bg-primary group-hover:text-white group-hover:shadow-brand transition-all duration-500 shadow-sm">
                    <span class="material-symbols-outlined font-[FILL_1]">{{ $service['icon'] }}</span>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-black text-slate-900 dark:text-slate-100 uppercase tracking-tight mb-1">
                        {{ $service['title'] }}</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                        {{ $service['sub'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
