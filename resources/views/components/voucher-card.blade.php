{{--
    Voucher Card Component — dùng chung cho tất cả trang có voucher
    Props:
        $coupon  — Coupon model instance (Accessors: icon_config, discount_label, expiry_label, expiry_urgency_class, remaining_usage)
        $action  — 'copy' (default) | 'link'
        $href    — URL khi $action = 'link'
        $notchBg — màu nền notch tròn (default: '#ffffff')
--}}
@props([
    'coupon',
    'action'  => 'copy',
    'href'    => null,
    'notchBg' => '#ffffff',
])

@php
    $ic           = $coupon->icon_config;
    $isPercentage = $coupon->type?->value === 'percentage';
    $resolvedHref = $href ?? route('account.coupons');
@endphp

<div class="relative bg-white rounded-2xl overflow-hidden flex items-stretch border border-gray-100 shadow-sm hover:shadow-[0_4px_16px_rgba(0,0,0,0.10)] transition-all duration-200 group" style="min-height:140px">

    {{-- ── Left icon area ── --}}
    <div class="w-[90px] flex-shrink-0 {{ $ic['bg'] }} flex flex-col items-center justify-center gap-1.5 py-4 px-2 relative">
        @if($ic['type'] === 'freeship')
            {{-- Freeship: truck icon --}}
            <div class="w-12 h-12 rounded-2xl {{ $ic['icon_bg'] }} flex items-center justify-center">
                <span class="material-symbols-outlined text-green-700 text-2xl">local_shipping</span>
            </div>
            <span class="text-green-800 text-[9px] font-black uppercase tracking-wide">FREESHIP</span>
        @else
            {{-- % or đ --}}
            <div class="w-12 h-12 rounded-2xl {{ $ic['icon_bg'] }} flex items-center justify-center">
                <span class="text-primary font-black text-2xl leading-none select-none">{{ $ic['symbol'] }}</span>
            </div>
        @endif
        {{-- Notch right --}}
        <div class="absolute top-1/2 -right-[9px] -translate-y-1/2 w-[18px] h-[18px] rounded-full z-10"
            style="background-color: {{ $notchBg }}"></div>
    </div>

    {{-- ── Center content ── --}}
    <div class="flex-1 px-4 py-3.5 bg-white relative min-w-0">
        {{-- Notch left --}}
        <div class="absolute top-1/2 -left-[9px] -translate-y-1/2 w-[18px] h-[18px] rounded-full z-10"
            style="background-color: {{ $notchBg }}"></div>

        {{-- Tên voucher --}}
        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-0.5 line-clamp-1">
            {{ $coupon->name ?? 'Mã giảm giá' }}
        </p>

        {{-- discount_label is a Model Accessor — số lớn đen --}}
        <p class="text-[22px] font-black text-gray-900 leading-none mb-1.5">
            {{ $coupon->discount_label }}
        </p>

        <div class="space-y-0.5">
            @if($coupon->min_order_amount)
                <p class="text-[11px] text-gray-500">
                    Đơn tối thiểu {{ number_format($coupon->min_order_amount, 0, ',', '.') }}đ
                </p>
            @endif
            @if($isPercentage && $coupon->max_discount)
                <p class="text-[11px] text-gray-500">
                    Giảm tối đa {{ number_format($coupon->max_discount, 0, ',', '.') }}đ
                </p>
            @endif
        </div>

        {{-- Dashed divider + HSD + lượt --}}
        <div class="border-t border-dashed border-gray-200 mt-2.5 pt-2 flex items-center justify-between gap-2">
            {{-- expiry_urgency_class and expiry_label are Model Accessors --}}
            <span class="text-[11px] {{ $coupon->expiry_urgency_class }} font-semibold flex items-center gap-1 flex-shrink-0">
                <span class="material-symbols-outlined text-[13px]">schedule</span>
                {{ $coupon->expiry_label }}
            </span>
            {{-- remaining_usage is a Model Accessor --}}
            @if($coupon->remaining_usage !== null)
                <span class="text-[11px] text-gray-400 whitespace-nowrap">
                    Còn {{ number_format($coupon->remaining_usage) }} lượt
                </span>
            @endif
        </div>
    </div>

    {{-- ── Right tab "DÙNG NGAY" ── --}}
    @if($action === 'copy')
        <button
            onclick="copyCoupon('{{ $coupon->code }}', this)"
            class="w-[56px] flex-shrink-0 {{ $ic['tab_bg'] }} hover:opacity-90 flex flex-col items-center justify-center relative transition-all duration-200 cursor-pointer">
            <div class="absolute top-1/2 -left-[9px] -translate-y-1/2 w-[18px] h-[18px] rounded-full z-10"
                style="background-color: {{ $notchBg }}"></div>
            <span class="text-white text-[10px] font-black uppercase tracking-[0.18em] coupon-label select-none"
                style="writing-mode:vertical-rl;text-orientation:mixed;transform:rotate(180deg)">
                Dùng Ngay
            </span>
            <span class="material-symbols-outlined text-white/40 text-[13px] absolute bottom-2.5">auto_awesome</span>
        </button>
    @else
        <a href="{{ $resolvedHref }}"
            class="w-[56px] flex-shrink-0 {{ $ic['tab_bg'] }} hover:opacity-90 flex flex-col items-center justify-center relative transition-all duration-200">
            <div class="absolute top-1/2 -left-[9px] -translate-y-1/2 w-[18px] h-[18px] rounded-full z-10"
                style="background-color: {{ $notchBg }}"></div>
            <span class="text-white text-[10px] font-black uppercase tracking-[0.18em] select-none"
                style="writing-mode:vertical-rl;text-orientation:mixed;transform:rotate(180deg)">
                Dùng Ngay
            </span>
            <span class="material-symbols-outlined text-white/40 text-[13px] absolute bottom-2.5">auto_awesome</span>
        </a>
    @endif
</div>
