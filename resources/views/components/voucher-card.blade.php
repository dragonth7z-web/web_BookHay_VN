{{--
    Voucher Card Component — dùng chung cho account/coupons và home/gift-cards
    Props:
        $coupon  — Coupon model instance (dùng các Accessor: icon_config, discount_label, expiry_label, expiry_urgency_class, remaining_usage)
        $action  — 'copy' (default, dùng JS copyCoupon) | 'link' (dùng href)
        $href    — URL khi $action = 'link' (default: route('account.coupons'))
        $notchBg — màu nền notch tròn (default: '#F0F2F5')
--}}
@props([
    'coupon',
    'action'   => 'copy',
    'href'     => null,
    'notchBg'  => '#F0F2F5',
])

@php
    $ic           = $coupon->icon_config;
    $isPercentage = $coupon->type?->value === 'percentage';
    $resolvedHref = $href ?? route('account.coupons');
@endphp

<div class="relative bg-white rounded-2xl overflow-hidden flex items-stretch border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 group" style="min-height:120px">

    {{-- ── Left icon area ── --}}
    <div class="w-[88px] flex-shrink-0 {{ $ic['bg'] }} flex flex-col items-center justify-center gap-1.5 py-4 px-2 relative">
        @if($ic['type'] === 'freeship')
            <div class="w-14 h-14 rounded-2xl {{ $ic['icon_bg'] }} flex items-center justify-center">
                <span class="material-symbols-outlined text-green-700 text-3xl">local_shipping</span>
            </div>
            <span class="text-green-800 text-[9px] font-black uppercase tracking-wide">FREESHIP</span>
        @else
            <div class="w-14 h-14 rounded-2xl {{ $ic['icon_bg'] }} flex items-center justify-center">
                <span class="text-primary font-black text-2xl leading-none select-none">{{ $ic['symbol'] }}</span>
            </div>
        @endif
        {{-- Notch right --}}
        <div class="absolute top-1/2 -right-[9px] -translate-y-1/2 w-[18px] h-[18px] rounded-full z-10"
            style="background-color: {{ $notchBg }}"></div>
    </div>

    {{-- ── Center content ── --}}
    <div class="flex-1 px-5 py-4 bg-white relative">
        {{-- Notch left --}}
        <div class="absolute top-1/2 -left-[9px] -translate-y-1/2 w-[18px] h-[18px] rounded-full z-10"
            style="background-color: {{ $notchBg }}"></div>

        {{-- Tên voucher --}}
        <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mb-1">
            {{ $coupon->name ?? 'Mã giảm giá' }}
        </p>

        {{-- discount_label is a Model Accessor --}}
        <h3 class="text-2xl font-black text-gray-900 leading-none mb-2"
            style="font-family: var(--font-heading, 'Lora', serif)">
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

    {{-- ── Right tab "DÙNG NGAY" ── --}}
    @if($action === 'copy')
        <button
            onclick="copyCoupon('{{ $coupon->code }}', this)"
            class="w-[60px] flex-shrink-0 {{ $ic['tab_bg'] }} hover:opacity-90 flex flex-col items-center justify-center gap-2 relative transition-all duration-200">
            <div class="absolute top-1/2 -left-[9px] -translate-y-1/2 w-[18px] h-[18px] rounded-full z-10"
                style="background-color: {{ $notchBg }}"></div>
            <span class="text-white text-[11px] font-black uppercase tracking-[0.2em] coupon-label select-none"
                style="writing-mode:vertical-rl;text-orientation:mixed;transform:rotate(180deg)">
                Dùng Ngay
            </span>
            <span class="material-symbols-outlined text-white/50 text-sm absolute bottom-3">auto_awesome</span>
        </button>
    @else
        <a href="{{ $resolvedHref }}"
            class="w-[60px] flex-shrink-0 {{ $ic['tab_bg'] }} hover:opacity-90 flex flex-col items-center justify-center gap-2 relative transition-all duration-200">
            <div class="absolute top-1/2 -left-[9px] -translate-y-1/2 w-[18px] h-[18px] rounded-full z-10"
                style="background-color: {{ $notchBg }}"></div>
            <span class="text-white text-[11px] font-black uppercase tracking-[0.2em] select-none"
                style="writing-mode:vertical-rl;text-orientation:mixed;transform:rotate(180deg)">
                Dùng Ngay
            </span>
            <span class="material-symbols-outlined text-white/50 text-sm absolute bottom-3">auto_awesome</span>
        </a>
    @endif
</div>
