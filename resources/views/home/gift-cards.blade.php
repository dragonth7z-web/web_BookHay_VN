{{-- PREMIUM GIFT CARDS SECTION --}}
<section
    class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 md:p-10 border border-slate-100 dark:border-slate-800 shadow-sm scroll-reveal overflow-hidden relative"
    id="gift-cards">
    {{-- Background Ornaments --}}
    <div
        class="absolute -top-12 -right-12 w-64 h-64 bg-primary/5 rounded-full blur-3xl pointer-events-none group-hover:bg-primary/10 transition-all duration-700">
    </div>

    <x-section-header title="Đặc Quyền Hội Viên & Ưu Đãi" subtitle="Đón chờ những món quà tri ân đẳng cấp"
        icon="redeem" />

    {{-- LEVEL 1: ƯU ĐÃI HỆ THỐNG --}}
    <div class="mt-8">
        <div class="flex items-center gap-2 mb-6 opacity-80">
            <span class="w-1 h-5 bg-primary rounded-full shadow-[0_0_10px_rgba(239,68,68,0.3)]"></span>
            <span class="text-xs font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Ưu đãi hệ thống</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if(isset($vouchers) && $vouchers->count() > 0)
                @foreach($vouchers->take(3) as $index => $voucher)
                    <a href="{{ route('books.search') }}"
                        class="group relative flex h-36 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 hover:border-primary/30 hover:shadow-xl hover:shadow-primary/5 transition-all duration-300">
                        {{-- Main part --}}
                        <div class="flex-1 bg-slate-50 dark:bg-slate-800/50 p-5 flex flex-col justify-center relative">
                            {{-- Decorative cutouts --}}
                            <div
                                class="absolute top-1/2 -right-2 -translate-y-1/2 w-4 h-4 bg-white dark:bg-slate-900 rounded-full border-l border-slate-100 dark:border-slate-800 z-10">
                            </div>
    
                            <div class="flex items-center gap-2 mb-1">
                                <span
                                    class="text-[10px] font-black uppercase tracking-widest text-primary bg-primary/10 px-2 py-0.5 rounded-full">Voucher</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Hội viên</span>
                            </div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white leading-tight">Giảm
                                {{ number_format($voucher->value, 0, ',', '.') }}{{ $voucher->type->value === 'percentage' ? '%' : 'đ' }}
                            </h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">Đơn hàng từ
                                {{ number_format($voucher->min_order_amount, 0, ',', '.') }}đ</p>
                        </div>
    
                        {{-- Side part (The "Stub") --}}
                        <div class="w-16 bg-primary dark:bg-red-700 flex items-center justify-center relative">
                            <div
                                class="absolute top-1/2 left-0 -translate-x-1/2 -translate-y-1/2 w-4 h-4 bg-white dark:bg-slate-900 rounded-full z-10">
                            </div>
                            <span
                                class="rotate-[-90deg] whitespace-nowrap text-[10px] font-black text-white uppercase tracking-[0.3em]">KHÁM
                                PHÁ</span>
                        </div>
    
                        {{-- Hover shimmer --}}
                        <div
                            class="absolute top-0 -left-[100%] w-1/2 h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-[25deg] group-hover:animate-shimmer-slide">
                        </div>
                    </a>
                @endforeach
            @else
                {{-- PREMIUM EMPTY STATE --}}
                <div
                    class="col-span-full relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-900/50 border border-slate-200 dark:border-slate-800 p-12 flex flex-col items-center justify-center min-h-[220px] group cursor-pointer transition-all duration-500 hover:shadow-xl hover:border-red-200/20">
                    {{-- (Minified for brevity, keeping all logic) --}}
                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div class="w-16 h-16 mb-4 rounded-2xl bg-white dark:bg-slate-800 shadow-xl border border-primary/10 flex items-center justify-center transform group-hover:-translate-y-2 transition-all duration-500">
                            <span class="material-symbols-outlined text-[30px] text-primary font-[FILL_1]">card_giftcard</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">Đang chuẩn bị ưu đãi mới</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm font-medium">Hệ thống đang thiết lập các chương trình ưu đãi mới.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- LEVEL 2: CHƯƠNG TRÌNH SỰ KIỆN --}}
    <div class="mt-12">
        <div class="flex items-center gap-2 mb-6 opacity-80">
            <span class="w-1 h-5 bg-amber-500 rounded-full shadow-[0_0_10px_rgba(245,158,11,0.3)]"></span>
            <span class="text-xs font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Chương trình sự kiện đang diễn ra</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @if(isset($giftBanners) && $giftBanners->count() > 0)
                @foreach($giftBanners as $banner)
                    <a href="{{ $banner->url ?? route('books.search') }}"
                        class="group relative block h-36 md:h-40 overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all duration-500 hover:-translate-y-1">
                        <img src="{{ function_exists('getBannerUrl') ? getBannerUrl($banner->image_url ?: $banner->image) : asset('storage/' . ($banner->image_url ?: $banner->image)) }}" 
                            alt="{{ $banner->title ?? 'Voucher' }}"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-4">
                            <span class="text-white text-[10px] font-black uppercase tracking-[0.2em] mb-1">Click để nhận ngay</span>
                            <div class="h-0.5 w-0 group-hover:w-full bg-primary transition-all duration-500"></div>
                        </div>
                    </a>
                @endforeach
            @else
                {{-- Fallback: System Exclusive Vouchers (Image-based) --}}
                @foreach(range(1, 4) as $i)
                    <a href="{{ route('books.search') }}"
                        class="group relative block h-36 md:h-40 overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all duration-500 hover:-translate-y-1">
                        <img src="{{ asset('images/vouchers/voucher_'.$i.'.png') }}" alt="Voucher {{ $i }}"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-4">
                            <span class="text-white text-[10px] font-black uppercase tracking-[0.2em] mb-1">Click để nhận ngay</span>
                            <div class="h-0.5 w-0 group-hover:w-full bg-primary transition-all duration-500"></div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Center Action Button --}}
    <div class="flex justify-center mt-12 pb-4">
        <a href="{{ route('books.search') }}" class="btn-view-all-premium group">
            <span class="material-symbols-outlined text-xl transition-transform group-hover:rotate-12">redeem</span>
            Khám phá đặc quyền hội viên
        </a>
    </div>
</section>
