@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - THLD')

@section('content')
<style>
    .order-tab-active {
        border-bottom: 3px solid var(--primary);
        color: var(--primary);
        font-weight: 700;
    }
    .soft-shadow {
        box-shadow: 0 4px 12px 0 rgba(0,0,0,0.05);
    }
    .sidebar-item-active {
        @apply bg-red-50 text-primary font-bold;
    }
</style>

<main class="max-w-main mx-auto px-4 py-8">
    <div class="grid grid-cols-12 gap-6">
        <aside class="col-span-12 lg:col-span-3">
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                <div class="p-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                        <span class="material-symbols-outlined text-3xl">account_circle</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-charcoal font-medium">Tài khoản của</p>
                        <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                    </div>
                </div>
                <nav class="p-2">
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-charcoal hover:bg-gray-50 transition-colors" href="{{ route('account.dashboard') }}">
                        <span class="material-symbols-outlined text-xl">person</span>
                        <span>Thông tin tài khoản</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm sidebar-item-active transition-colors" href="{{ route('account.orders') }}">
                        <span class="material-symbols-outlined text-xl">history_edu</span>
                        <span>Đơn hàng của tôi</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-charcoal hover:bg-gray-50 transition-colors" href="#">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                        <span>Thông báo của tôi</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-charcoal hover:bg-gray-50 transition-colors" href="#">
                        <span class="material-symbols-outlined text-xl">location_on</span>
                        <span>Sổ địa chỉ</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-charcoal hover:bg-gray-50 transition-colors" href="#">
                        <span class="material-symbols-outlined text-xl">credit_card</span>
                        <span>Thông tin thanh toán</span>
                    </a>
                </nav>
            </div>
        </aside>
        <div class="col-span-12 lg:col-span-9 space-y-4">
            <h1 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Đơn hàng của tôi</h1>
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="flex text-sm text-charcoal font-medium overflow-x-auto">
                    <a href="{{ route('account.orders') }}" class="flex-1 py-4 px-6 text-center {{ !request('status') ? 'order-tab-active' : '' }} whitespace-nowrap">Tất cả</a>
                    <a href="{{ route('account.orders', ['status' => 'pending']) }}" class="flex-1 py-4 px-6 text-center {{ request('status') == 'pending' ? 'order-tab-active' : '' }} hover:text-primary transition-colors whitespace-nowrap">Chờ xác nhận</a>
                    <a href="{{ route('account.orders', ['status' => 'shipping']) }}" class="flex-1 py-4 px-6 text-center {{ request('status') == 'shipping' ? 'order-tab-active' : '' }} hover:text-primary transition-colors whitespace-nowrap">Đang giao</a>
                    <a href="{{ route('account.orders', ['status' => 'completed']) }}" class="flex-1 py-4 px-6 text-center {{ request('status') == 'completed' ? 'order-tab-active' : '' }} hover:text-primary transition-colors whitespace-nowrap">Hoàn thành</a>
                    <a href="{{ route('account.orders', ['status' => 'cancelled']) }}" class="flex-1 py-4 px-6 text-center {{ request('status') == 'cancelled' ? 'order-tab-active' : '' }} hover:text-primary transition-colors whitespace-nowrap">Đã hủy</a>
                </div>
            </div>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary" placeholder="Tìm đơn hàng theo Mã đơn hàng hoặc Tên sản phẩm" type="text" />
            </div>
            
            <div class="space-y-4">
                @forelse($orders as $order)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-50 flex flex-wrap justify-between items-center gap-4">
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-sm text-gray-800">Mã đơn hàng: #{{ $order->order_number }}</span>
                            <span class="w-px h-3 bg-gray-200"></span>
                            <span class="text-xs text-charcoal">Ngày mua: {{ $order->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @php
                                $statusIcon = match($order->status?->value) {
                                    'completed', 'delivered' => 'check_circle',
                                    'pending'   => 'pending',
                                    'shipping'  => 'local_shipping',
                                    'cancelled' => 'cancel',
                                    default     => 'info'
                                };
                                $statusColor = match($order->status?->value) {
                                    'completed', 'delivered' => 'text-green-600',
                                    'pending'   => 'text-orange-600',
                                    'shipping'  => 'text-blue-600',
                                    'cancelled' => 'text-gray-600',
                                    default     => 'text-slate-600'
                                };
                            @endphp
                            <span class="material-symbols-outlined {{ $statusColor }} text-lg">{{ $statusIcon }}</span>
                            <span class="text-xs font-bold {{ $statusColor }} uppercase">{{ $order->status?->value }}</span>
                        </div>
                    </div>
                    <div class="p-4 space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex gap-4">
                            <div class="w-20 h-20 flex-shrink-0 border border-gray-100 rounded p-1">
                                <img alt="{{ $item->book_title_snapshot }}" class="w-full h-full object-contain"
                                    src="{{ $item->book?->cover_image ? asset('storage/' . $item->book->cover_image) : 'https://placehold.co/80x80?text=No+Image' }}" />
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-800 line-clamp-2">{{ $item->book_title_snapshot }}</h4>
                                <p class="text-xs text-charcoal mt-1">Số lượng: x{{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold text-gray-800">{{ number_format($item->unit_price, 0, ',', '.') }} đ</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="p-4 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4 border-t border-gray-100">
                        <div class="text-sm text-charcoal">
                            Tổng tiền: <span class="text-lg font-black text-primary ml-1">{{ number_format($order->total, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('account.orders.show', $order->id) }}" class="px-6 py-2 border border-primary text-primary text-xs font-bold rounded-lg hover:bg-red-50 transition-colors uppercase tracking-wider">Xem chi tiết</a>
                            @if($order->status?->value === 'completed')
                                <button class="px-6 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-dark transition-all uppercase tracking-wider shadow-sm">Mua lại</button>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                    <span class="material-symbols-outlined text-6xl text-gray-200 mb-4 text-center">shopping_bag</span>
                    <p class="text-gray-500 font-medium">Bạn chưa có đơn hàng nào.</p>
                    <a href="{{ url('/') }}" class="mt-4 inline-block bg-primary text-white font-bold px-8 py-3 rounded-lg hover:bg-primary-dark transition-all">Mua sắm ngay</a>
                </div>
                @endforelse

                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

