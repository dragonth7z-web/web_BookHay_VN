@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng - THLD')

@section('content')
@php
    $orderCode  = $order->order_number ?? ('#ORD-' . $order->id);
    $createdAt  = $order->created_at ? $order->created_at->format('d/m/Y H:i') : '—';
    $items      = $order->items ?? collect();
    $subtotal   = (int) ($order->subtotal ?? 0);
    $shipping   = (int) ($order->shipping_fee ?? 0);
    $discount   = (int) ($order->discount_amount ?? 0);
    $grandTotal = (int) ($order->total ?? 0);

    $orderStatus = $order->status?->value ?? '';
    $progressMap = [
        'pending'   => 20,
        'confirmed' => 40,
        'shipping'  => 60,
        'delivered' => 80,
        'completed' => 100,
        'cancelled' => 100,
        'returned'  => 100,
    ];
    $progressPercent = $progressMap[$orderStatus] ?? 0;

    $receiverName  = $order->recipient_name  ?? $user?->name ?? '—';
    $receiverPhone = $order->recipient_phone ?? $user?->phone ?? '—';
@endphp

<div class="min-h-screen bg-[#F0F2F5] py-8">
    <div class="max-w-5xl mx-auto px-4">
        <a href="{{ route('account.orders') }}" class="inline-flex items-center gap-2 text-primary font-bold hover:underline mb-6">
            <span class="material-symbols-outlined" style="font-size:20px">arrow_back</span>
            Chi tiết đơn hàng
        </a>

        <div class="bg-white dark:bg-slate-800/40 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="font-bold text-gray-900 dark:text-slate-100 text-lg">
                        Mã đơn hàng: <span class="text-primary">{{ $orderCode }}</span>
                    </p>
                    <p class="text-sm text-gray-500 dark:text-slate-400">{{ $createdAt }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-red-600 border border-red-100 text-xs font-bold">
                        <span class="material-symbols-outlined text-[14px]">local_shipping</span>
                        {{ $orderStatus ?: '—' }}
                    </span>
                </div>
            </div>

            <div class="mt-5">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-bold text-primary uppercase tracking-wider">Tiến trình</span>
                    <span class="text-xs font-bold text-gray-500 dark:text-slate-400">{{ $progressPercent }}%</span>
                </div>
                <div class="h-2 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full" style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="md:col-span-2 space-y-5">
                <div class="bg-white dark:bg-slate-800/40 rounded-2xl border border-gray-100 dark:border-slate-700 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-primary">auto_stories</span>
                        <h3 class="font-bold text-gray-900 dark:text-slate-100">Danh sách sản phẩm ({{ $items->count() }})</h3>
                    </div>

                    <div class="space-y-4">
                        @forelse($items as $item)
                        @php
                            $img = $item->book_image_snapshot ?: $item->book?->cover_image;
                            $imgUrl = $img ? (filter_var($img, FILTER_VALIDATE_URL) ? $img : asset('storage/' . $img)) : null;
                        @endphp
                        <div class="flex gap-4 pb-4 border-b border-gray-100 dark:border-slate-700 last:border-b-0">
                            <div class="w-16 h-20 bg-gray-50 dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700 p-1 flex-shrink-0 overflow-hidden flex items-center justify-center">
                                @if($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="{{ $item->book_title_snapshot }}" class="w-full h-full object-contain">
                                @else
                                    <span class="material-symbols-outlined text-gray-400">image</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 dark:text-slate-100 text-sm line-clamp-2">
                                    {{ $item->book_title_snapshot ?? '—' }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
                                    Số lượng: <span class="font-bold">{{ $item->quantity }}</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">
                                    Đơn giá: <span class="font-bold text-primary">{{ number_format($item->unit_price, 0, ',', '.') }} đ</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">
                                    Thành tiền: <span class="font-bold">{{ number_format($item->subtotal, 0, ',', '.') }} đ</span>
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="py-10 text-center text-gray-500 dark:text-slate-400">Chưa có sản phẩm.</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800/40 rounded-2xl border border-gray-100 dark:border-slate-700 p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-primary">location_on</span>
                        <h3 class="font-bold text-gray-900 dark:text-slate-100">Địa chỉ nhận hàng</h3>
                    </div>
                    <p class="font-bold text-gray-900 dark:text-slate-100">{{ $receiverName }}</p>
                    <p class="text-sm text-gray-600 dark:text-slate-300 mt-1">{{ $receiverPhone }}</p>
                    <p class="text-sm text-gray-600 dark:text-slate-300 mt-1">{{ $order->shipping_address ?? '—' }}</p>
                </div>
            </div>

            <div class="space-y-5">
                <div class="bg-white dark:bg-slate-800/40 rounded-2xl border border-gray-100 dark:border-slate-700 p-6">
                    <h3 class="font-bold text-gray-900 dark:text-slate-100 mb-4">Tổng kết đơn hàng</h3>
                    <div class="flex justify-between text-sm text-gray-600 dark:text-slate-300 py-1">
                        <span>Tạm tính ({{ $items->count() }} sản phẩm)</span>
                        <span class="font-semibold">{{ number_format($subtotal, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 dark:text-slate-300 py-1">
                        <span>Phí vận chuyển</span>
                        <span class="font-semibold">{{ number_format($shipping, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 dark:text-slate-300 py-1">
                        <span>Giảm giá</span>
                        <span class="font-semibold text-green-600 dark:text-green-300">-{{ number_format($discount, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="flex justify-between text-lg font-black text-gray-900 dark:text-slate-100 pt-3 border-t border-gray-100 dark:border-slate-700 mt-3">
                        <span>Tổng cộng</span>
                        <span class="text-primary">{{ number_format($grandTotal, 0, ',', '.') }} đ</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800/40 rounded-2xl border border-gray-100 dark:border-slate-700 p-6">
                    <h3 class="font-bold text-gray-900 dark:text-slate-100 mb-4">Thanh toán</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-8 bg-gradient-to-r from-blue-600 to-blue-800 rounded flex items-center justify-center">
                            <span class="text-white text-[10px] font-black">
                                {{ strtoupper(substr($order->payment_method?->value ?? 'PAY', 0, 4)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-slate-100">{{ $order->payment_method?->value ?? '—' }}</p>
                            <p class="text-xs font-medium text-green-600 dark:text-green-300">{{ $order->payment_status?->value ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

