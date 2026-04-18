@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng')
@section('page-title', 'Xử lý Đơn hàng & Giao vận')

@section('content')
<div class="max-w-[1230px] mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Quản lý đơn hàng</h1>
            <p class="text-sm text-slate-500 dark:text-slate-300 mt-1">
                Danh sách đơn hàng từ hệ thống.
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-500">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse admin-table">
                <thead>
                    <tr>
                        <th class="w-12 text-center">
                            <input type="checkbox" class="rounded text-[#C92127] focus:ring-[#C92127] h-4 w-4 border-gray-300">
                        </th>
                        <th>Mã đơn &amp; Ngày tạo</th>
                        <th>Khách hàng</th>
                        <th>Thanh toán</th>
                        <th class="text-center">Trạng thái giao hàng</th>
                        <th class="text-right">Tổng tiền</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($orders as $order)
                        @php
                            $itemsCount = $order->items?->count() ?? 0;
                            $paymentMethod = $order->payment_method?->value ?? '';
                            $paymentStatus = $order->payment_status?->value ?? '';
                            $status = $order->status;
                            $total = (int) ($order->total ?? 0);
                        @endphp

                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                            <td class="px-5 py-4 text-center align-top">
                                <input type="checkbox" class="rounded text-primary focus:ring-primary h-4 w-4 border-gray-300">
                            </td>

                            <td class="px-5 py-4 align-top">
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="font-black text-gray-800 dark:text-slate-100 text-sm hover:text-primary transition-colors block mb-1">
                                    {{ $order->order_number ?? ('#ORD-' . $order->id) }}
                                </a>
                                <p class="text-[11px] text-gray-500 dark:text-slate-400 font-medium flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px]">calendar_today</span>
                                    {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '—' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 align-top">
                                <p class="font-bold text-gray-800 dark:text-slate-100 text-sm mb-0.5">
                                    {{ $order->user?->name ?? $order->recipient_name ?? '—' }}
                                </p>
                                <p class="text-[11px] text-gray-600 dark:text-slate-400 font-medium">
                                    {{ $order->user?->phone ?? $order->recipient_phone ?? '—' }}
                                </p>
                                <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-1 line-clamp-1" title="{{ $order->shipping_address }}">
                                    {{ $order->shipping_address ?? '' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 align-top">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-10 h-4 bg-gray-100 dark:bg-slate-700 rounded border border-gray-200 dark:border-slate-600 text-[8px] font-black flex items-center justify-center text-gray-600 dark:text-slate-300">
                                        {{ strtoupper($paymentMethod) ?: 'PAY' }}
                                    </span>
                                    <span class="text-xs font-semibold text-gray-700 dark:text-slate-200">
                                        {{ $order->payment_method?->name ?? '—' }}
                                    </span>
                                </div>
                                <span class="bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    {{ $order->payment_status?->name ?? '—' }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-center align-top">
                                <x-status-badge :status="$order->status" type="order" />
                            </td>

                            <td class="px-5 py-4 text-right align-top">
                                <span class="font-black text-gray-900 dark:text-slate-100 block text-sm">
                                    {{ number_format($total, 0, ',', '.') }} đ
                                </span>
                                <span class="text-[10px] text-gray-400 dark:text-slate-500 font-semibold block mt-0.5">
                                    {{ $itemsCount }} Sản phẩm
                                </span>
                            </td>

                            <td class="px-5 py-4 text-right align-top">
                                <div class="flex flex-col items-end gap-1 opacity-100">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="px-3 py-1 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-700 dark:text-slate-200 text-[11px] font-bold rounded-lg hover:border-primary hover:text-primary transition-colors flex items-center gap-1">
                                        Chi tiết
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                Không có đơn hàng nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-[var(--admin-border)] flex items-center justify-between flex-wrap gap-3">
            <p class="text-xs text-slate-500 dark:text-slate-300 font-medium">
                Hiển thị
                <span class="font-bold text-slate-800 dark:text-slate-100">
                    {{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }}
                </span>
                trong <span class="font-bold text-slate-800 dark:text-slate-100">{{ $orders->total() }}</span> đơn hàng
            </p>
            <div>{{ $orders->links() }}</div>
        </div>
    </div>
</div>
@endsection


