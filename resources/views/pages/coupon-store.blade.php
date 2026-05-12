@extends('layouts.app')

@section('title', 'Kho Mã Giảm Giá - THLD')

@section('content')

{{-- ── Hero Banner Đỏ ── --}}
<div class="relative bg-primary rounded-2xl overflow-hidden mb-6 py-10 px-6 md:px-12 text-center">
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-white"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 rounded-full bg-white"></div>
    </div>
    <div class="relative z-10">
        <p class="text-yellow-300 font-black text-4xl md:text-5xl uppercase leading-tight mb-2"
            style="font-family: var(--font-heading, 'Lora', serif); text-shadow: 2px 2px 0 rgba(0,0,0,0.2)">
            5 Tặng Ưu Đãi
        </p>
        <p class="text-white font-black text-2xl md:text-3xl uppercase mb-4">
            Càng Mua Càng Lời
        </p>
        <p class="text-white/80 text-sm mb-6">Áp dụng không giới hạn, hướng tới tiêu dùng thông minh</p>

        {{-- Quick nav tabs --}}
        <div class="flex flex-wrap justify-center gap-2">
            @foreach([
                ['icon' => 'bolt',                'label' => 'Flash Sale',      'color' => 'bg-yellow-400 text-yellow-900'],
                ['icon' => 'confirmation_number', 'label' => 'Coupon Thêm',     'color' => 'bg-white/20 text-white border border-white/30'],
                ['icon' => 'local_offer',         'label' => 'Giảm Giảm Giá',  'color' => 'bg-white/20 text-white border border-white/30'],
                ['icon' => 'stars',               'label' => 'Tích Lũy Điểm',  'color' => 'bg-white/20 text-white border border-white/30'],
            ] as $tab)
                <a href="#{{ \Illuminate\Support\Str::slug($tab['label']) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold transition-all {{ $tab['color'] }}">
                    <span class="material-symbols-outlined text-[14px]">{{ $tab['icon'] }}</span>
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Mã Ưu Đãi Hot Theo Khung Giờ ── --}}
<div class="mb-8" id="coupon-them">
    <h2 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-xl">local_fire_department</span>
        Mã Ưu Đãi Hot Theo Khung Giờ
    </h2>

    {{-- Coupon grid — All display logic via Coupon Model Accessors --}}
    @forelse($coupons as $coupon)
        @if($loop->first)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @endif

        {{-- Dùng chung <x-voucher-card> component --}}
        <x-voucher-card :coupon="$coupon" action="copy" notch-bg="#f9fafb" />

        @if($loop->last)
            </div>
        @endif

    @empty
        <div class="bg-gray-50 rounded-2xl p-8 text-center border border-dashed border-gray-200">
            <span class="material-symbols-outlined text-gray-300 text-4xl block mb-3">confirmation_number</span>
            <p class="text-gray-400 text-sm font-medium">Chưa có mã giảm giá nào đang hoạt động</p>
        </div>
    @endforelse

    {{-- Apply manual code --}}
    <div class="mt-4 flex items-center justify-center gap-2 text-xs text-gray-500">
        <span class="material-symbols-outlined text-[14px]">info</span>
        Mã có thể áp dụng tại bước thanh toán.
        <a href="{{ route('books.search') }}" class="text-primary font-bold hover:underline">Mua sắm ngay</a>
    </div>
</div>

{{-- ── Ưu Đãi Đối Tác Ngập Tràn ── --}}
<div class="mb-8">
    <h2 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-xl">handshake</span>
        Ưu Đãi Đối Tác Ngập Tràn
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Flash Sale banner --}}
        <a href="{{ route('flash-sale.index') }}"
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary to-rose-700 p-6 flex flex-col justify-between min-h-[160px] group hover:shadow-xl transition-all">
            <div class="absolute -top-8 -right-8 w-40 h-40 bg-white/10 rounded-full pointer-events-none"></div>
            <div>
                <div class="inline-flex items-center gap-1.5 bg-yellow-400 text-yellow-900 text-[10px] font-black px-2.5 py-1 rounded-full mb-3">
                    <span class="material-symbols-outlined text-[12px]">bolt</span>
                    Flash Sale
                </div>
                <p class="text-white font-black text-2xl leading-tight">Giảm 100K</p>
                <p class="text-white/80 text-xs mt-1">Freeship toàn quốc</p>
            </div>
            <div class="flex items-center gap-2 text-white/80 text-xs font-medium mt-4">
                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                Xem ngay
            </div>
        </a>

        {{-- Partner discount banner --}}
        <a href="{{ route('books.search') }}"
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 p-6 flex flex-col justify-between min-h-[160px] group hover:shadow-xl transition-all">
            <div class="absolute -top-8 -right-8 w-40 h-40 bg-white/10 rounded-full pointer-events-none"></div>
            <div>
                <div class="inline-flex items-center gap-1.5 bg-white/20 text-white text-[10px] font-black px-2.5 py-1 rounded-full mb-3 border border-white/30">
                    <span class="material-symbols-outlined text-[12px]">local_offer</span>
                    Đối tác
                </div>
                <p class="text-white font-black text-2xl leading-tight">Giảm ngay</p>
                <p class="text-yellow-300 font-black text-xl">100.000 VNĐ</p>
                <p class="text-white/70 text-xs mt-1">Cho đơn hàng đầu tiên</p>
            </div>
            <div class="flex items-center gap-2 text-white/80 text-xs font-medium mt-4">
                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                Khám phá
            </div>
        </a>
    </div>
</div>

{{-- ── Xu Hướng Mua Sắm ── --}}
<div class="mb-8" id="flash-sale">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-xl">trending_up</span>
            Xu Hướng Mua Sắm
        </h2>
        <a href="{{ route('books.search') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
            Xem thêm <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
        </a>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-2 mb-4 border-b border-gray-100 pb-3">
        @foreach(['Xu hướng tháng', 'Sách HOT - Giảm Sốc', 'Bestseller Ngoại Văn'] as $i => $tab)
            <button class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $i === 0 ? 'bg-primary text-white' : 'text-gray-500 hover:text-primary' }}">
                {{ $tab }}
            </button>
        @endforeach
    </div>

    {{-- Books — trendingBooks come from PageController via CouponRepository --}}
    @if($trendingBooks->isNotEmpty())
        <div class="grid-book-layout">
            @foreach($trendingBooks as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>
    @endif

    <div class="text-center mt-5">
        <a href="{{ route('books.search') }}"
            class="inline-flex items-center gap-2 border-2 border-gray-200 text-gray-600 font-bold px-6 py-2.5 rounded-xl hover:border-primary hover:text-primary transition-all text-sm">
            Xem Thêm
        </a>
    </div>
</div>

{{-- ── Gợi Ý Cho Bạn ── --}}
<div class="mb-8">
    <h2 class="text-lg font-black text-gray-900 mb-4 text-center">
        ✨ Gợi ý cho bạn ✨
    </h2>

    {{-- recommendedBooks come from PageController --}}
    @if($recommendedBooks->isNotEmpty())
        <div class="grid-book-layout">
            @foreach($recommendedBooks as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>
    @endif

    <div class="text-center mt-5">
        <a href="{{ route('books.search') }}"
            class="inline-flex items-center gap-2 border-2 border-gray-200 text-gray-600 font-bold px-6 py-2.5 rounded-xl hover:border-primary hover:text-primary transition-all text-sm">
            Xem tất cả →
        </a>
    </div>
</div>

{{-- ── Newsletter ── --}}
<div class="bg-primary rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute -top-10 -right-10 w-60 h-60 rounded-full bg-white"></div>
    </div>
    <div class="relative z-10 text-white">
        <h3 class="text-xl font-black mb-1">Đăng ký nhận bản tin tri thức</h3>
        <p class="text-white/80 text-sm">Nhận thông tin về sách mới và ưu đãi độc quyền sớm nhất.</p>
    </div>
    <form class="flex gap-2 relative z-10 w-full md:w-auto" onsubmit="return false">
        <input type="email" placeholder="Email của bạn..."
            class="flex-1 md:w-64 px-4 py-2.5 rounded-xl text-sm outline-none bg-white/10 border border-white/30 text-white placeholder-white/60 focus:bg-white/20 transition-all">
        <button type="submit"
            class="px-6 py-2.5 bg-white text-primary font-bold text-sm rounded-xl hover:bg-white/90 transition-all whitespace-nowrap">
            Đăng ký
        </button>
    </form>
</div>

@endsection

@push('scripts')
<script>
    function copyCoupon(code, btn) {
        navigator.clipboard.writeText(code).then(() => {
            const label = btn.querySelector('.coupon-label');
            const orig  = label?.textContent;
            if (label) label.textContent = 'Đã sao!';
            btn.classList.add('bg-green-600');
            btn.classList.remove('bg-primary');
            setTimeout(() => {
                if (label) label.textContent = orig;
                btn.classList.remove('bg-green-600');
                btn.classList.add('bg-primary');
            }, 2000);
        });
    }
</script>
@endpush
