@extends('layouts.admin')
@section('title', 'Chi tiết Phiếu Nhập')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.purchase-orders.index') }}" class="text-gray-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[28px]">arrow_back</span>
        </a>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">Chi tiết Phiếu #{{ $purchaseOrder->po_number }}</h1>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="admin-card p-6 border-t-4 border-t-primary">
                <h2 class="font-bold text-sm uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">info</span> Thông Tin Chung
                </h2>
                <div class="space-y-4 text-sm">
                    <div>
                        <span class="block text-gray-400 font-medium mb-1">Nhân viên tạo phiếu</span>
                        <div class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">person</span> {{ $purchaseOrder->user->name ?? '---' }}
                        </div>
                    </div>
                    <div>
                        <span class="block text-gray-400 font-medium mb-1">Đối tác NXB</span>
                        <div class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">domain</span> {{ $purchaseOrder->publisher->name ?? 'Không chỉ định' }}
                        </div>
                    </div>
                    <div>
                        <span class="block text-gray-400 font-medium mb-1">Thời gian nhập</span>
                        <div class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">calendar_month</span> {{ $purchaseOrder->created_at->format('d/m/Y H:i:s') }}
                        </div>
                    </div>
                    <div class="pt-3 mt-3 border-t border-gray-100 border-dashed">
                        <span class="block text-gray-400 font-medium mb-1">Tổng tiền thanh toán</span>
                        <div class="text-xl font-black text-primary">
                            {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}<span class="text-sm align-top relative -top-1 ml-0.5">đ</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($purchaseOrder->note)
            <div class="admin-card p-4 bg-yellow-50/50">
                <h3 class="font-bold text-sm text-yellow-800 mb-2">Ghi chú phiếu:</h3>
                <p class="text-sm text-yellow-700">{{ $purchaseOrder->note }}</p>
            </div>
            @endif
        </div>
        
        <div class="lg:col-span-2">
            <div class="admin-card p-6 min-h-full">
                <h2 class="font-bold text-sm uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">inventory_2</span> Chi Tiết Sách Nhập ({{ count($purchaseOrder->items) }})
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Tên Sách</th>
                                <th>Số Lượng</th>
                                <th class="text-right">Đơn Giá Nhập</th>
                                <th class="text-right">Thành Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseOrder->items as $item)
                            <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/30">
                                <td class="py-3 px-3">
                                    <div class="font-bold text-gray-800 text-sm line-clamp-2 max-w-[200px]">{{ $item->book->title ?? 'Sách #'.$item->book_id }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-black bg-blue-100 text-blue-700 rounded-lg min-w-[36px]">
                                        {{ number_format($item->quantity) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right font-medium text-sm text-gray-600">
                                    {{ number_format($item->unit_price, 0, ',', '.') }}đ
                                </td>
                                <td class="py-3 px-3 text-right font-black text-sm text-primary">
                                    {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}đ
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-sm text-gray-400 italic">Không có chi tiết.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if(count($purchaseOrder->items) > 0)
                        <tfoot>
                            <tr>
                                <td colspan="3" class="py-3 px-3 text-right font-bold text-gray-500 uppercase text-xs">Cộng Thành Tiền Khớp Thực Tế:</td>
                                <td class="py-3 px-3 text-right font-black text-base text-gray-800">
                                    {{ number_format($purchaseOrder->items->sum(fn($item) => $item->quantity * $item->unit_price), 0, ',', '.') }}đ
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

