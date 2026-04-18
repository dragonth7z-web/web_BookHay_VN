@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng')
@section('page-title', $order->order_number ?? ('Đơn hàng #' . ($order->id ?? '—')))

@section('content')
@php
    $orderCode = $order->order_number ?? ('#ORD-' . ($order->id ?? '—'));
    $createdAt = $order->created_at ? $order->created_at->format('d/m/Y H:i') : '—';
    $subtotal = (int) ($order->subtotal ?? 0);
    $shipping = (int) ($order->shipping_fee ?? 0);
    $discount = (int) ($order->discount_amount ?? 0);
    $grandTotal = (int) ($order->total ?? 0);
    $voucherName = $order->coupon?->code ?? $order->coupon?->name;
@endphp

<div class="max-w-[1230px] mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-3">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Admin</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <a href="{{ route('admin.orders.index') }}" class="hover:text-primary">Đơn hàng</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-primary font-semibold">{{ $orderCode }}</span>
            </nav>

            <h1 class="text-2xl font-black text-gray-900 dark:text-slate-100">
                Chi tiết đơn hàng {{ $orderCode }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Tạo ngày: {{ $createdAt }} |
                Thanh toán: <span class="font-bold">{{ $order->payment_status?->name ?? '—' }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.index') }}"
               class="w-10 h-10 bg-white dark:bg-slate-700 rounded-full border border-gray-200 dark:border-slate-600 flex items-center justify-center text-gray-600 dark:text-slate-200 hover:text-primary hover:border-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            {{-- Products --}}
            <div class="bg-white dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900 dark:text-slate-100">
                        Sản phẩm đã đặt ({{ $order->items?->count() ?? 0 }})
                    </h2>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                        {{ $order->payment_method?->name ?? '—' }}
                    </span>
                </div>

                <div class="p-5">
                    @forelse($order->items as $ct)
                        @php
                            $img = $ct->book_image_snapshot ?: ($ct->book?->cover_image ?? null);
                            $imgUrl = null;
                            if (!empty($img)) {
                                $imgUrl = filter_var($img, FILTER_VALIDATE_URL) ? $img : asset('storage/' . $img);
                            }
                        @endphp
                        <div class="flex gap-4 py-3 border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                            <div class="w-14 h-18 bg-gray-50 dark:bg-slate-700 rounded border border-gray-200 dark:border-slate-600 p-0.5 flex-shrink-0 overflow-hidden flex items-center justify-center">
                                @if($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="{{ $ct->book_title_snapshot ?? ($ct->book?->title ?? 'Sách') }}"
                                         class="w-full h-full object-contain">
                                @else
                                    <span class="material-symbols-outlined text-gray-400">image</span>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 dark:text-slate-100 text-sm line-clamp-2">
                                            {{ $ct->book_title_snapshot ?? $ct->book?->title ?? '—' }}
                                        </p>
                                        @php $sku = $ct->book?->sku; @endphp
                                        @if(!empty($sku))
                                            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">
                                                SKU: {{ $sku }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-slate-600 dark:text-slate-300">
                                            Đơn giá:
                                            <span class="font-bold text-gray-900 dark:text-slate-100">
                                                {{ number_format((int) ($ct->unit_price ?? 0), 0, ',', '.') }} đ
                                            </span>
                                        </p>
                                        <p class="text-xs text-slate-600 dark:text-slate-300">
                                            SL:
                                            <span class="font-bold text-gray-900 dark:text-slate-100">
                                                {{ (int) ($ct->quantity ?? 0) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400">
                                        Thành tiền
                                    </span>
                                    <span class="font-black text-primary text-sm">
                                        {{ number_format((int) ($ct->subtotal ?? 0), 0, ',', '.') }} đ
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-slate-400 dark:text-slate-500">
                            Không có sản phẩm nào.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Update status --}}
            <div class="bg-white dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900 dark:text-slate-100">Cập nhật trạng thái đơn hàng</h2>
                </div>

                <div class="p-5">
                    <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-200 mb-2">
                                    Trạng thái đơn hàng
                                </label>
                                <select name="status"
                                        class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-slate-600 focus:outline-none focus:ring-1 focus:ring-primary text-sm dark:bg-slate-700 dark:text-white">
                                    @foreach(\App\Enums\OrderStatus::cases() as $st)
                                        <option value="{{ $st->value }}" {{ ($order->status === $st) ? 'selected' : '' }}>
                                            {{ $st->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-200 mb-2">
                                    Trạng thái thanh toán
                                </label>
                                <select name="payment_status"
                                        class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-slate-600 focus:outline-none focus:ring-1 focus:ring-primary text-sm dark:bg-slate-700 dark:text-white">
                                    @foreach(\App\Enums\PaymentStatus::cases() as $pt)
                                        <option value="{{ $pt->value }}" {{ ($order->payment_status === $pt) ? 'selected' : '' }}>
                                            {{ $pt->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-200 mb-2">
                                    Lý do hủy (nếu có)
                                </label>
                                <textarea name="cancel_reason"
                                          rows="3"
                                          class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-slate-600 focus:outline-none focus:ring-1 focus:ring-primary text-sm dark:bg-slate-700 dark:text-white">{{ $order->cancel_reason }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-200 mb-2">
                                    Ghi chú
                                </label>
                                <textarea name="notes"
                                          rows="3"
                                          class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-slate-600 focus:outline-none focus:ring-1 focus:ring-primary text-sm dark:bg-slate-700 dark:text-white">{{ $order->notes }}</textarea>
                            </div>
                        </div>

                        <div class="mt-5 flex justify-end">
                            <button type="submit"
                                    class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right side: customer, address, totals --}}
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700">
                    <h2 class="font-bold text-gray-900 dark:text-slate-100">Thông tin khách hàng</h2>
                </div>
                <div class="p-5 space-y-3">
                    <div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Tên</p>
                        <p class="font-black text-gray-900 dark:text-slate-100">{{ $order->user?->name ?? $order->recipient_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">SĐT</p>
                        <p class="font-bold text-gray-900 dark:text-slate-100">{{ $order->user?->phone ?? $order->recipient_phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Email</p>
                        <p class="font-bold text-gray-900 dark:text-slate-100">{{ $order->user?->email ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700">
                    <h2 class="font-bold text-gray-900 dark:text-slate-100">Giao hàng</h2>
                </div>
                <div class="p-5 space-y-3">
                    <div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Người nhận</p>
                        <p class="font-black text-gray-900 dark:text-slate-100">{{ $order->recipient_name ?? $order->user?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Địa chỉ</p>
                        <p class="text-sm text-slate-700 dark:text-slate-200 leading-relaxed">{{ $order->shipping_address ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900 dark:text-slate-100">Tổng kết</h2>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">{{ $voucherName ? 'Có voucher' : 'Không voucher' }}</span>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span>Tạm tính</span>
                        <span class="font-bold">{{ number_format($subtotal, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span>Phí vận chuyển</span>
                        <span class="font-bold">{{ number_format($shipping, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span>Giảm giá</span>
                        <span class="font-bold text-green-700 dark:text-green-300">
                            -{{ number_format($discount, 0, ',', '.') }} đ
                        </span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-slate-100 dark:border-slate-700">
                        <span class="font-black text-gray-900 dark:text-slate-100">Tổng cộng</span>
                        <span class="text-xl font-black text-primary">
                            {{ number_format($grandTotal, 0, ',', '.') }} đ
                        </span>
                    </div>

                    @if($order->coupon)
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-700">
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Voucher</p>
                            <p class="text-sm text-gray-900 dark:text-slate-100 font-bold">
                                {{ $order->coupon->code }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



