@extends('layouts.app')

@section('title', 'Mã Giảm Giá - THLD')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
            <span class="material-symbols-outlined text-[16px]">confirmation_number</span>
            Ưu đãi độc quyền
        </div>
        <h1 class="text-3xl font-black text-gray-900 mb-2" style="font-family: var(--font-heading, 'Lora', serif)">
            Mã Giảm Giá
        </h1>
        <p class="text-gray-500 text-sm">Sao chép mã và áp dụng khi thanh toán để nhận ưu đãi</p>
        <a href="{{ route('login') }}"
            class="inline-flex items-center gap-2 mt-4 bg-primary text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)]">
            <span class="material-symbols-outlined text-[18px]">login</span>
            Đăng nhập để lưu voucher vào tài khoản
        </a>
    </div>

    {{-- Coupon Grid --}}
    @forelse($coupons as $coupon)
        @if($loop->first)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @endif

        {{-- discount_label, expiry_label, expiry_urgency_class, remaining_usage, icon_config come from Coupon Model Accessors --}}
        <div class="relative bg-white rounded-2xl overflow-hidden flex items-stretch border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 group" style="min-height:120px">

            {{-- Left icon area — icon_config is a Model Accessor --}}
            @php $ic = $coupon->icon_config; @endphp
            <div class="w-[88px] flex-shrink-0 {{ $ic['bg'] }} flex items-center justify-center relative">
                @if($ic['is_text'])
                    <div class="flex flex-col items-center justify-center px-2 py-3">
                        <span class="text-white font-black text-base leading-tight text-center uppercase">FREE</span>
                        <span class="text-white font-black text-base leading-tight text-center uppercase">SHIP</span>
                    </div>
                @else
                    <div class="w-14 h-14 rounded-2xl {{ $ic['text_bg'] }} flex items-center justify-center">
                        <span class="text-primary font-black text-2xl leading-none select-none">{{ $ic['symbol'] }}</span>
                    </div>
                @endif
                <div class="absolute top-1/2 -right-[9px] -translate-y-1/2 w-[18px] h-[18px] bg-gray-50 rounded-full z-10"></div>
            </div>
                <div class="absolute top-1/2 -right-[9px] -translate-y-1/2 w-[18px] h-[18px] bg-gray-50 rounded-full z-10"></div>
            </div>

            {{-- Center content --}}
            <div class="flex-1 px-5 py-4 bg-white relative">
                <div class="absolute top-1/2 -left-[9px] -translate-y-1/2 w-[18px] h-[18px] bg-gray-50 rounded-full z-10"></div>

                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">
                    {{ $coupon->name ?? 'Mã giảm giá' }}
                </p>
                {{-- discount_label is a Model Accessor --}}
                <h3 class="text-3xl font-black text-gray-900 leading-none mb-3">
                    {{ $coupon->discount_label }}
                </h3>

                <div class="space-y-1">
                    @if($coupon->min_order_amount)
                        <p class="text-sm text-gray-600 font-medium">
                            Đơn tối thiểu {{ number_format($coupon->min_order_amount, 0, ',', '.') }}đ
                        </p>
                    @endif
                    @if($coupon->type?->value === 'percentage' && $coupon->max_discount)
                        <p class="text-sm text-gray-500">
                            Giảm tối đa {{ number_format($coupon->max_discount, 0, ',', '.') }}đ
                        </p>
                    @endif
                </div>

                <div class="flex items-center gap-4 mt-4 pt-3 border-t border-dashed border-gray-200">
                    {{-- expiry_urgency_class and expiry_label are Model Accessors --}}
                    <span class="text-xs {{ $coupon->expiry_urgency_class }} font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                        {{ $coupon->expiry_label }}
                    </span>
                    {{-- remaining_usage is a Model Accessor --}}
                    @if($coupon->remaining_usage !== null)
                        <span class="text-xs text-gray-400 font-medium">
                            Còn {{ number_format($coupon->remaining_usage) }} lượt
                        </span>
                    @endif
                </div>
            </div>

            {{-- Right "DÙNG NGAY" tab --}}
            <button
                onclick="copyCoupon('{{ $coupon->code }}', this)"
                class="coupon-tab w-20 flex-shrink-0 bg-primary hover:bg-primary/90 flex items-center justify-center relative transition-all duration-200 group-hover:w-24">
                <div class="absolute top-1/2 -left-2 -translate-y-1/2 w-4 h-4 bg-gray-50 rounded-full"></div>
                <div class="flex flex-col items-center gap-2">
                    <span class="material-symbols-outlined text-white text-2xl coupon-icon">content_copy</span>
                    <span class="text-white text-[10px] font-black uppercase tracking-[0.15em] coupon-label"
                        style="writing-mode:vertical-rl;text-orientation:mixed">
                        Dùng Ngay
                    </span>
                </div>
                <span class="material-symbols-outlined absolute top-2 right-2 text-white/40 text-sm animate-pulse">auto_awesome</span>
            </button>
        </div>

        @if($loop->last)
            </div>
        @endif

    @empty
        <div class="text-center py-20">
            <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-primary text-4xl">confirmation_number</span>
            </div>
            <p class="text-gray-400 font-medium text-lg">Hiện chưa có mã giảm giá nào đang hoạt động</p>
            <a href="{{ route('books.search') }}"
                class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-primary hover:underline">
                Tiếp tục mua sắm
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
    @endforelse

    @if($coupons->isNotEmpty())
        <p class="text-center text-xs text-gray-400 mt-8">
            Mã giảm giá áp dụng tại bước thanh toán. Mỗi mã chỉ dùng một lần/tài khoản.
        </p>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function copyCoupon(code, btn) {
        navigator.clipboard.writeText(code).then(() => {
            const icon  = btn.querySelector('.coupon-icon');
            const label = btn.querySelector('.coupon-label');

            icon.textContent  = 'check';
            label.textContent = 'Đã Sao!';
            btn.classList.add('bg-green-500');
            btn.classList.remove('bg-primary');

            setTimeout(() => {
                icon.textContent  = 'content_copy';
                label.textContent = 'Dùng Ngay';
                btn.classList.remove('bg-green-500');
                btn.classList.add('bg-primary');
            }, 2000);
        });
    }
</script>
@endpush
