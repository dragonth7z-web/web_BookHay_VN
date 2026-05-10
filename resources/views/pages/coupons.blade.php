@extends('layouts.app')

@section('title', 'Kho Mã Giảm Giá - THLD')

@section('content')

{{-- ── Hero Header ── --}}
<div class="mb-8">
    <div class="inline-flex items-center gap-2 bg-primary text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full mb-4">
        Special Offers
    </div>
    <h1 class="text-4xl font-black text-gray-900 mb-3" style="font-family: var(--font-heading, 'Lora', serif)">
        Kho mã giảm giá
    </h1>
    <p class="text-gray-500 text-sm max-w-lg leading-relaxed">
        Săn ưu đãi, bùng sáng tri thức. Những đặc quyền dành riêng cho độc giả của THLD.
    </p>
</div>

{{-- ── Apply Coupon Bar ── --}}
<div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 mb-8 flex flex-col sm:flex-row items-center gap-4">
    <div class="flex items-center gap-3 flex-shrink-0">
        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-primary text-xl">confirmation_number</span>
        </div>
        <div>
            <p class="font-bold text-gray-900 text-sm">Bạn có mã ưu đãi riêng?</p>
            <p class="text-xs text-gray-500">Nhập mã tại đây để nhận chiết khấu trực tiếp.</p>
        </div>
    </div>
    <form class="flex gap-2 flex-1 max-w-md ml-auto" onsubmit="return false">
        <input type="text" id="apply-coupon-input"
            placeholder="Nhập mã voucher..."
            class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all bg-white">
        <button type="submit"
            class="px-6 py-2.5 bg-primary text-white font-bold text-sm rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] whitespace-nowrap">
            Áp dụng
        </button>
    </form>
</div>

{{-- ── Category Filter Tabs ── --}}
<div class="flex items-center gap-2 mb-6 flex-wrap">
    @php
        $activeFilter = request('type', 'all');
        $tabs = [
            'all'        => 'Tất cả',
            'percentage' => 'Sách mới',
            'fixed'      => 'Văn học',
            'academic'   => 'Academic',
            'freeship'   => 'Vận chuyển',
        ];
    @endphp
    @foreach($tabs as $key => $label)
        <a href="{{ route('pages.coupons') }}{{ $key !== 'all' ? '?type=' . $key : '' }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
                {{ $activeFilter === $key
                    ? 'bg-primary text-white shadow-[0_4px_12px_rgba(201,33,39,0.25)]'
                    : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- ── Coupon Grid — dùng <x-voucher-card> component ── --}}
{{-- All display logic delegated to Coupon Model Accessors via x-voucher-card --}}
@forelse($coupons as $coupon)
    @if($loop->first)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
    @endif

    <x-voucher-card
        :coupon="$coupon"
        action="copy"
        notch-bg="#f9fafb" />

    @if($loop->last)
        </div>
    @endif

@empty
    <div class="text-center py-16 mb-10">
        <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-5">
            <span class="material-symbols-outlined text-primary text-4xl">confirmation_number</span>
        </div>
        <p class="text-gray-500 font-medium text-lg mb-2">Hiện chưa có mã giảm giá nào đang hoạt động</p>
        <a href="{{ route('books.search') }}"
            class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:underline mt-2">
            Tiếp tục mua sắm
            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </a>
    </div>
@endforelse

{{-- ── VIP Member Section ── --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

    {{-- Left: Image banner --}}
    <div class="relative overflow-hidden rounded-2xl min-h-[280px]"
        style="background-image: url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=600&q=70'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
        <div class="absolute bottom-6 left-6 text-white">
            <p class="text-xs font-bold uppercase tracking-widest text-white/70 mb-1">The Curator's Choice</p>
            <p class="text-sm italic text-white/80">"Sách là ngọn đèn sáng bất diệt của tri thức con người."</p>
        </div>
    </div>

    {{-- Right: VIP benefits --}}
    <div class="flex flex-col justify-center">
        <h2 class="text-2xl font-black text-gray-900 mb-3 leading-tight"
            style="font-family: var(--font-heading, 'Lora', serif)">
            Chương trình đặc quyền dành cho<br>thành viên tri thức
        </h2>
        <p class="text-gray-500 text-sm leading-relaxed mb-5">
            Đăng ký trở thành thành viên của THLD để nhận những mã giảm giá độc quyền lên đến 50% hàng tháng. Chúng tôi tin rằng tri thức nên được tiếp cận một cách dễ dàng và đầy cảm hứng.
        </p>
        <ul class="space-y-3 mb-6">
            @foreach([
                ['icon' => 'local_offer',    'text' => 'Voucher sinh nhật giảm 30% cho mọi đơn hàng.'],
                ['icon' => 'local_shipping', 'text' => 'Miễn phí vận chuyển không giới hạn cho đơn từ 0đ (Hạng Kim Cương).'],
                ['icon' => 'verified',       'text' => 'Ưu tiên đặt trước các bản in giới hạn và sách có chữ ký tác giả.'],
            ] as $benefit)
                <li class="flex items-start gap-3 text-sm text-gray-600">
                    <span class="material-symbols-outlined text-primary text-[18px] mt-0.5 flex-shrink-0">{{ $benefit['icon'] }}</span>
                    {{ $benefit['text'] }}
                </li>
            @endforeach
        </ul>
        <a href="{{ route('register') }}"
            class="inline-flex items-center gap-2 bg-primary text-white font-bold px-6 py-3 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] text-sm w-fit">
            Đăng ký thành viên ngay
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </a>
    </div>
</div>

{{-- ── Newsletter Banner ── --}}
<div class="bg-primary rounded-2xl p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute -top-10 -right-10 w-60 h-60 rounded-full bg-white"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full bg-white"></div>
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
