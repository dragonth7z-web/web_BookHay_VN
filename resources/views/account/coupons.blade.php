@extends('layouts.app')

@section('title', 'Kho Voucher - THLD')

@section('content')
<div class="min-h-screen bg-[#F0F2F5] py-8">
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex flex-col lg:flex-row gap-6 min-h-[600px]">

            {{-- ── Sidebar ── --}}
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="flex items-center gap-4 mb-6 px-2">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary bg-gray-100">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <img src="{{ urlencode($user->name) }}&background=C92127&color=fff" alt="Avatar" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tài khoản của</p>
                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                    </div>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('account.profile') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">person</span>
                        <span>Thông tin cá nhân</span>
                    </a>
                    <a href="{{ route('account.orders') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">package_2</span>
                        <span>Đơn hàng của tôi</span>
                    </a>
                    <a href="{{ route('account.wishlist') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">favorite</span>
                        <span>Sách yêu thích</span>
                    </a>
                    <a href="{{ route('account.notifications') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                        <span>Thông báo</span>
                    </a>
                    <a href="{{ route('account.addresses') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">location_on</span>
                        <span>Sổ địa chỉ</span>
                    </a>
                    <a href="{{ route('account.coupons') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold bg-primary text-white shadow-[0_6px_20px_rgba(201,33,39,0.25)] transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">confirmation_number</span>
                        <span>Kho Voucher</span>
                    </a>
                    <div class="pt-4 mt-4 border-t border-gray-100">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-coupons" class="hidden">@csrf</form>
                        <a href="javascript:void(0)"
                            onclick="document.getElementById('logout-form-coupons').submit();"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-red-500 font-medium transition-all duration-200 hover:bg-red-50 hover:text-red-600">
                            <span class="material-symbols-outlined text-xl">logout</span>
                            <span>Đăng xuất</span>
                        </a>
                    </div>
                </nav>
            </aside>

            {{-- ── Main Content ── --}}
            <section class="flex-1 min-w-0">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 mb-5 text-sm">
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-primary font-medium transition-colors">Trang chủ</a>
                    <span class="material-symbols-outlined text-gray-400 text-base">chevron_right</span>
                    <a href="{{ route('account.profile') }}" class="text-gray-500 hover:text-primary font-medium transition-colors">Tài khoản</a>
                    <span class="material-symbols-outlined text-gray-400 text-base">chevron_right</span>
                    <span class="text-primary font-bold">Kho Voucher</span>
                </nav>

                {{-- Page Header --}}
                <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] px-7 py-5 mb-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Kho Voucher</h1>
                            <p class="text-sm text-gray-500 mt-0.5">
                                Các mã giảm giá đang hoạt động — sao chép và áp dụng khi thanh toán.
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-xl text-sm font-bold">
                            <span class="material-symbols-outlined text-[18px]">confirmation_number</span>
                            {{ $coupons->count() }} voucher
                        </span>
                    </div>

                    {{-- Input nhập mã --}}
                    <div class="mt-5 flex gap-3">
                        <div class="flex-1 relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl">confirmation_number</span>
                            <input
                                id="manual-coupon-input"
                                type="text"
                                placeholder="Nhập mã voucher tại đây..."
                                class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all uppercase tracking-wider font-mono">
                        </div>
                        <button
                            onclick="copyManualCoupon()"
                            class="px-6 py-2.5 bg-primary text-white font-bold text-sm rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)]">
                            Lưu
                        </button>
                    </div>
                </div>

                {{-- Coupon Grid --}}
                {{-- discount_label, expiry_label, expiry_urgency_class, remaining_usage come from Coupon Model Accessors --}}
                @forelse($coupons as $coupon)
                    @if($loop->first)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @endif

                    @php
                        $isPercentage = $coupon->type?->value === 'percentage';
                        $iconBg = $isPercentage ? 'bg-primary' : 'bg-teal-500';
                    @endphp

                    <div class="relative bg-white rounded-2xl overflow-hidden flex items-stretch border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 group" style="min-height:120px">

                        {{-- Left icon area — icon_config is a Model Accessor --}}
                        @php $ic = $coupon->icon_config; @endphp
                        <div class="w-[88px] flex-shrink-0 {{ $ic['bg'] }} flex items-center justify-center relative">
                            @if($ic['is_text'])
                                {{-- FREE SHIP style --}}
                                <div class="flex flex-col items-center justify-center px-2 py-3">
                                    <span class="text-white font-black text-base leading-tight text-center uppercase">FREE</span>
                                    <span class="text-white font-black text-base leading-tight text-center uppercase">SHIP</span>
                                </div>
                            @else
                                {{-- % or đ icon --}}
                                <div class="w-14 h-14 rounded-2xl {{ $ic['text_bg'] }} flex items-center justify-center">
                                    <span class="text-primary font-black text-2xl leading-none select-none">{{ $ic['symbol'] }}</span>
                                </div>
                            @endif
                            {{-- Notch right --}}
                            <div class="absolute top-1/2 -right-[9px] -translate-y-1/2 w-[18px] h-[18px] bg-[#F0F2F5] rounded-full z-10"></div>
                        </div>

                        {{-- Center content — nền trắng --}}
                        <div class="flex-1 px-5 py-4 bg-white relative">
                            {{-- Notch left --}}
                            <div class="absolute top-1/2 -left-[9px] -translate-y-1/2 w-[18px] h-[18px] bg-[#F0F2F5] rounded-full z-10"></div>

                            {{-- Tên voucher --}}
                            <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mb-1">
                                {{ $coupon->name ?? 'Mã giảm giá' }}
                            </p>

                            {{-- discount_label is a Model Accessor --}}
                            <h3 class="text-2xl font-black text-gray-900 leading-none mb-2" style="font-family: var(--font-heading, 'Lora', serif)">
                                {{ $coupon->discount_label }}
                            </h3>

                            <div class="space-y-0.5">
                                @if($coupon->min_order_amount)
                                    <p class="text-xs text-gray-600">
                                        Đơn tối thiểu {{ number_format($coupon->min_order_amount, 0, ',', '.') }}đ
                                    </p>
                                @endif
                                @if($isPercentage && $coupon->max_discount)
                                    <p class="text-xs text-gray-600">
                                        Giảm tối đa {{ number_format($coupon->max_discount, 0, ',', '.') }}đ
                                    </p>
                                @endif
                            </div>

                            {{-- Divider dashed --}}
                            <div class="border-t border-dashed border-gray-200 mt-3 pt-2.5 flex items-center justify-between">
                                {{-- expiry_urgency_class and expiry_label are Model Accessors --}}
                                <span class="text-xs {{ $coupon->expiry_urgency_class }} font-semibold flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    {{ $coupon->expiry_label }}
                                </span>
                                {{-- remaining_usage is a Model Accessor --}}
                                @if($coupon->remaining_usage !== null)
                                    <span class="text-xs text-gray-400">
                                        Còn {{ number_format($coupon->remaining_usage) }} lượt
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Right tab — đỏ đặc, chữ dọc "DÙNG NGAY" --}}
                        <button
                            onclick="copyCoupon('{{ $coupon->code }}', this)"
                            class="w-[60px] flex-shrink-0 bg-primary hover:bg-primary/90 flex flex-col items-center justify-center gap-2 relative transition-all duration-200">
                            {{-- Notch left --}}
                            <div class="absolute top-1/2 -left-[9px] -translate-y-1/2 w-[18px] h-[18px] bg-[#F0F2F5] rounded-full z-10"></div>

                            {{-- Chữ dọc --}}
                            <span class="text-white text-[11px] font-black uppercase tracking-[0.2em] coupon-label select-none"
                                style="writing-mode:vertical-rl;text-orientation:mixed;transform:rotate(180deg)">
                                Dùng Ngay
                            </span>

                            {{-- Sparkle --}}
                            <span class="material-symbols-outlined text-white/50 text-sm absolute bottom-3">auto_awesome</span>
                        </button>
                    </div>

                    @if($loop->last)
                        </div>
                    @endif

                @empty
                    <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-12 text-center">
                        <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <span class="material-symbols-outlined text-primary text-4xl">confirmation_number</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Kho voucher trống</h3>
                        <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                            Hiện chưa có mã giảm giá nào đang hoạt động. Hãy quay lại sau nhé!
                        </p>
                        <a href="{{ route('books.search') }}"
                            class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-primary/90 hover:scale-105 transition-all shadow-[0_6px_20px_rgba(201,33,39,0.25)]">
                            <span class="material-symbols-outlined text-[18px]">explore</span>
                            Tiếp tục mua sắm
                        </a>
                    </div>
                @endforelse

                @if($coupons->isNotEmpty())
                    <p class="text-center text-xs text-gray-400 mt-6">
                        Mã giảm giá áp dụng tại bước thanh toán. Mỗi mã chỉ dùng một lần/tài khoản.
                    </p>
                @endif

            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyCoupon(code, btn) {
        navigator.clipboard.writeText(code).then(() => {
            const label = btn.querySelector('.coupon-label');
            const orig  = label.textContent;

            label.textContent = 'Đã sao!';
            btn.classList.add('bg-green-600');
            btn.classList.remove('bg-primary');

            setTimeout(() => {
                label.textContent = orig;
                btn.classList.remove('bg-green-600');
                btn.classList.add('bg-primary');
            }, 2000);
        });
    }

    function copyManualCoupon() {
        const input = document.getElementById('manual-coupon-input');
        const code  = input.value.trim().toUpperCase();
        if (!code) return;

        navigator.clipboard.writeText(code).then(() => {
            input.classList.add('border-green-400', 'bg-green-50');
            setTimeout(() => {
                input.classList.remove('border-green-400', 'bg-green-50');
            }, 2000);
        });
    }
</script>
@endpush
