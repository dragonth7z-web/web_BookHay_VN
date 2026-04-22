@extends('layouts.app')

@section('title', 'Mã Giảm Giá - THLD')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-2 bg-brand-primary/10 text-brand-primary px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
            <span class="material-symbols-outlined text-[16px]">confirmation_number</span>
            Ưu đãi độc quyền
        </div>
        <h1 class="text-3xl font-black text-gray-900 mb-2" style="font-family: var(--font-heading, 'Lora', serif)">
            Mã Giảm Giá
        </h1>
        <p class="text-gray-500 text-sm">Sao chép mã và áp dụng khi thanh toán để nhận ưu đãi</p>
    </div>

    @forelse($coupons as $coupon)
        @if($loop->first)
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @endif

        <div class="relative bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex hover:shadow-md transition-shadow">
            {{-- Left accent --}}
            <div class="w-2 bg-gradient-to-b from-brand-primary to-rose-500 flex-shrink-0"></div>

            {{-- Coupon body --}}
            <div class="flex-1 p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-400 font-medium mb-0.5">{{ $coupon->name ?? 'Mã giảm giá' }}</p>
                        <p class="text-2xl font-black text-brand-primary leading-tight">{{ $coupon->discount_label }}</p>
                        @if($coupon->min_order_amount)
                            <p class="text-xs text-gray-500 mt-1">
                                Đơn tối thiểu {{ number_format($coupon->min_order_amount, 0, ',', '.') }}đ
                            </p>
                        @endif
                        @if($coupon->type === \App\Enums\CouponType::Percentage && $coupon->max_discount)
                            <p class="text-xs text-gray-400">
                                Giảm tối đa {{ number_format($coupon->max_discount, 0, ',', '.') }}đ
                            </p>
                        @endif
                    </div>

                    {{-- Copy button --}}
                    <div class="flex-shrink-0 text-right">
                        <button onclick="copyCoupon('{{ $coupon->code }}', this)"
                            class="group flex items-center gap-1.5 bg-brand-primary/10 hover:bg-brand-primary text-brand-primary hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition-all">
                            <span class="material-symbols-outlined text-[15px]">content_copy</span>
                            <span class="coupon-code font-mono tracking-wider">{{ $coupon->code }}</span>
                        </button>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-dashed border-gray-100">
                    <span class="text-[11px] {{ $coupon->expiry_urgency_class }} font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">schedule</span>
                        {{ $coupon->expiry_label }}
                    </span>
                    @if($coupon->remaining_usage !== null)
                        <span class="text-[11px] text-gray-400 font-medium">
                            Còn {{ number_format($coupon->remaining_usage) }} lượt
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if($loop->last)
        </div>
        @endif

    @empty
        <div class="text-center py-20">
            <span class="material-symbols-outlined text-5xl text-gray-200 block mb-3">confirmation_number</span>
            <p class="text-gray-400 font-medium">Hiện chưa có mã giảm giá nào đang hoạt động</p>
            <a href="{{ route('books.search') }}" class="mt-4 inline-block text-sm font-bold text-brand-primary hover:underline">
                Tiếp tục mua sắm →
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
        const span = btn.querySelector('.coupon-code');
        const icon = btn.querySelector('.material-symbols-outlined');
        const orig = span.textContent;
        icon.textContent = 'check';
        span.textContent = 'Đã sao chép!';
        btn.classList.add('bg-green-500', 'text-white');
        btn.classList.remove('bg-brand-primary/10', 'text-brand-primary');
        setTimeout(() => {
            icon.textContent = 'content_copy';
            span.textContent = orig;
            btn.classList.remove('bg-green-500', 'text-white');
            btn.classList.add('bg-brand-primary/10', 'text-brand-primary');
        }, 2000);
    });
}
</script>
@endpush
