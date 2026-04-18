@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ================================================================ --}}
{{-- 1. STAT CARDS (4 cards) --}}
{{-- ================================================================ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

    {{-- Card 1: Doanh thu hôm nay --}}
    <div id="card-revenue" class="stat-card group hover:border-red-200 transition-colors">
        <div class="flex justify-between items-start">
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Doanh thu hôm nay</p>
                <h3 class="text-xl font-black text-gray-800 mt-1 leading-tight">{{ $stats['revenue_today'] }}</h3>
                <p class="text-[10px] text-gray-400 font-medium">
                    So với hôm qua:
                    <span class="font-semibold {{ $stats['revenue_pct'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $stats['revenue_pct'] >= 0 ? '+' : '' }}{{ $stats['revenue_pct'] }}%
                    </span>
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-primary group-hover:bg-red-100 transition-colors">
                <span class="material-symbols-outlined">payments</span>
            </div>
        </div>
        <div class="mt-3">
            <div class="h-10 -mx-1">
                <canvas id="sparklineRevenue"></canvas>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-gray-50 flex items-center justify-between text-[10px]">
            <span class="flex items-center gap-1 font-semibold {{ $stats['revenue_pct'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <span class="material-symbols-outlined text-sm">{{ $stats['revenue_pct'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
                {{ $stats['revenue_pct'] >= 0 ? '+' : '' }}{{ $stats['revenue_pct'] }}% hôm qua
            </span>
            <div class="flex items-center gap-3">
                <span class="text-gray-400 font-medium">{{ number_format($stats['revenue_month'] / 1000000, 1) }}M ₫ / tháng</span>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-1 font-bold text-primary hover:underline">
                    Xem chi tiết
                    <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Card 2: Đơn hàng mới --}}
    <div id="card-orders" class="stat-card group hover:border-red-200 transition-colors">
        <div class="flex justify-between items-start">
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Đơn hàng mới</p>
                <h3 class="text-xl font-black text-gray-800 mt-1">{{ $stats['new_orders'] }}</h3>
                <p class="text-[10px] text-gray-400 font-medium">
                    So với hôm qua:
                    <span class="font-semibold {{ $stats['orders_diff'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $stats['orders_diff'] >= 0 ? '+' : '' }}{{ $stats['orders_diff'] }} đơn
                    </span>
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-primary group-hover:bg-red-100 transition-colors">
                <span class="material-symbols-outlined">shopping_bag</span>
            </div>
        </div>
        <div class="mt-3">
            <div class="h-10 -mx-1">
                <canvas id="sparklineOrders"></canvas>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-gray-50 flex items-center justify-between text-[10px]">
            <span class="flex items-center gap-1 font-semibold {{ $stats['orders_diff'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <span class="material-symbols-outlined text-sm">{{ $stats['orders_diff'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
                {{ $stats['orders_diff'] >= 0 ? '+' : '' }}{{ $stats['orders_diff'] }} đơn hôm qua
            </span>
            <div class="flex items-center gap-3">
                <span class="text-gray-400 font-medium">{{ $stats['pending_orders'] }} đơn chờ xử lý</span>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-1 font-bold text-primary hover:underline">
                    Xem chi tiết
                    <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Card 3: Tỷ lệ chuyển đổi --}}
    <div id="card-conversion" class="stat-card group hover:border-purple-200 transition-colors">
        <div class="flex justify-between items-start">
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tỷ lệ chuyển đổi</p>
                <h3 class="text-xl font-black text-gray-800 mt-1">{{ $stats['conversion_rate'] }}%</h3>
                <p class="text-[10px] text-gray-400 font-medium">
                    Đơn hàng / Lượt đăng nhập (Tháng này)
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-primary group-hover:bg-red-100 transition-colors">
                <span class="material-symbols-outlined">ads_click</span>
            </div>
        </div>
        <div class="mt-3">
            <div class="h-10 -mx-1">
                <canvas id="sparklineConversion"></canvas>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-gray-50 flex items-center justify-between text-[10px]">
            <span class="flex items-center gap-1 font-semibold {{ $stats['conversion_rate'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <span class="material-symbols-outlined text-sm">{{ $stats['conversion_rate'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
                <span id="conversionTrendLabel">{{ $stats['conversion_rate'] }}% tháng này</span>
            </span>
            <div class="flex items-center gap-2">
                <span id="conversionAlertBadge" class="{{ $stats['conversion_rate'] < 3 ? 'inline-flex' : 'hidden' }} items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-red-50 text-red-700 border border-red-200">
                    <span class="material-symbols-outlined text-[12px]">warning</span>
                    Cảnh báo < 3%
                </span>
            </div>
        </div>
    </div>

    {{-- Card 4: Cảnh báo tồn kho --}}
    <div class="stat-card border-red-200/60 cursor-pointer group" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(254,242,242,0.85) 100%);">
        <div class="absolute top-0 right-0 w-24 h-24 rounded-full -mr-8 -mt-8 opacity-40 pointer-events-none" style="background: radial-gradient(circle, rgba(201,33,39,0.2) 0%, transparent 70%);"></div>
        <div class="flex justify-between items-start mb-3 relative z-10">
            <div>
                <p class="text-[10px] font-bold text-primary uppercase tracking-widest">Tồn kho cảnh báo</p>
                <h3 class="text-xl font-black text-gray-800 mt-0.5">{{ count($lowStockBooks) }} sản phẩm</h3>
            </div>
            <span class="bg-primary text-white text-[9px] px-2 py-1 rounded-lg font-black animate-pulse-primary">● NGUY CẤP</span>
        </div>
        <div class="space-y-1.5 mb-3 relative z-10">
            @forelse($lowStockBooks as $book)
                @php
                    if($book->stock <= 2) { $color = 'primary'; $label = 'Nguy cấp'; }
                    elseif($book->stock <= 5) { $color = 'orange'; $label = 'Trung bình'; }
                    else { $color = 'yellow'; $label = 'Thấp'; }
                @endphp
                <div class="flex items-center gap-2 text-[11px]">
                    <span class="w-2 h-2 rounded-full bg-{{ $color }}-600 flex-shrink-0"></span>
                    <span class="flex-1 text-gray-700 truncate font-medium">{{ $book->title }}</span>
                    <span class="text-{{ $color }}-700 font-black">{{ $book->stock }} cuốn</span>
                    <span class="text-[9px] bg-{{ $color }}-100 text-{{ $color }}-700 px-1.5 rounded font-bold">{{ $label }}</span>
                </div>
            @empty
                <div class="text-[11px] text-gray-500 italic">Hệ thống tồn kho đang ở mức an toàn.</div>
            @endforelse
        </div>
        <a href="{{ route('admin.purchase-orders.create') }}"
           class="w-full py-2 bg-primary text-white text-[11px] font-black rounded-xl hover:bg-primary-700 transition-all flex items-center justify-center gap-2 shadow-sm shadow-primary-200 relative z-10">
            <span class="material-symbols-outlined text-sm">add_box</span> TẠO PHIẾU NHẬP
        </a>
    </div>

</div>

{{-- ================================================================ --}}
{{-- 2. QUICK ACTIONS --}}
{{-- ================================================================ --}}
<section class="space-y-3">
    <div class="flex items-center justify-between">
        <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest" style="font-family: 'Montserrat', sans-serif;">Trung tâm điều khiển nhanh</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('admin.books.create') }}" class="quick-action-btn h-full flex-col py-5">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center mb-2">
                <span class="material-symbols-outlined text-primary text-[24px]">add_circle</span>
            </div>
            <span class="text-xs font-bold">Thêm sách mới</span>
        </a>
        <a href="{{ route('admin.notifications.create') }}" class="quick-action-btn h-full flex-col py-5">
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center mb-2">
                <span class="material-symbols-outlined text-blue-600 text-[24px]">campaign</span>
            </div>
            <span class="text-xs font-bold">Gửi thông báo</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="quick-action-btn h-full flex-col py-5">
            <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center mb-2">
                <span class="material-symbols-outlined text-amber-600 text-[24px]">inventory</span>
            </div>
            <span class="text-xs font-bold">Xử lý đơn hàng</span>
        </a>
        <a href="{{ route('admin.coupons.create') }}" class="quick-action-btn h-full flex-col py-5">
            <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center mb-2">
                <span class="material-symbols-outlined text-purple-600 text-[24px]">confirmation_number</span>
            </div>
            <span class="text-xs font-bold">Tạo Voucher</span>
        </a>

        {{-- Row 2 --}}
        <a href="{{ route('admin.purchase-orders.create') }}" class="quick-action-btn h-full flex-col py-5">
            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center mb-2">
                <span class="material-symbols-outlined text-emerald-600 text-[24px]">input</span>
            </div>
            <span class="text-xs font-bold">Nhập hàng mới</span>
        </a>
        <a href="{{ route('admin.banner.create') }}" class="quick-action-btn h-full flex-col py-5">
            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center mb-2">
                <span class="material-symbols-outlined text-indigo-600 text-[24px]">add_photo_alternate</span>
            </div>
            <span class="text-xs font-bold">Tài liệu/Banner</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="quick-action-btn h-full flex-col py-5">
            <div class="w-10 h-10 rounded-full bg-sky-50 flex items-center justify-center mb-2">
                <span class="material-symbols-outlined text-sky-600 text-[24px]">group</span>
            </div>
            <span class="text-xs font-bold">Ds Khách hàng</span>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="quick-action-btn h-full flex-col py-5">
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mb-2">
                <span class="material-symbols-outlined text-gray-600 text-[24px]">settings</span>
            </div>
            <span class="text-xs font-bold">Cài đặt Web</span>
        </a>
    </div>
</section>

{{-- ================================================================ --}}
{{-- 3. MINI METRIC BAR (5 items) – ALL FROM DB --}}
{{-- ================================================================ --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">

    <div class="admin-card px-4 py-3 hover:shadow-md transition-all">
        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block">Doanh thu tháng</span>
        <div class="flex items-end gap-1 mt-0.5">
            <span class="text-sm font-black text-gray-800">{{ number_format($stats['revenue_month'] / 1000000, 1) }}M ₫</span>
            <span class="text-[9px] font-bold {{ $stats['revenue_month_pct'] >= 0 ? 'text-green-600' : 'text-red-600' }} mb-0.5">
                {{ $stats['revenue_month_pct'] >= 0 ? '+' : '' }}{{ $stats['revenue_month_pct'] }}%
            </span>
        </div>
        <p class="text-[9px] text-gray-400 mt-0.5">vs tháng trước: {{ number_format($stats['revenue_last_month'] / 1000000, 1) }}M ₫</p>
    </div>

    <div class="admin-card px-4 py-3 hover:shadow-md transition-all">
        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block">Tỷ lệ đơn hủy</span>
        <div class="flex items-end gap-1 mt-0.5">
            <span class="text-sm font-black text-gray-800">{{ $stats['cancel_rate'] }}%</span>
            @php $cancelDiff = round($stats['cancel_rate'] - $stats['cancel_rate_last'], 1); @endphp
            <span class="text-[9px] font-bold {{ $cancelDiff <= 0 ? 'text-green-600' : 'text-red-600' }} mb-0.5">
                {{ $cancelDiff >= 0 ? '+' : '' }}{{ $cancelDiff }}%
            </span>
        </div>
        <p class="text-[9px] text-gray-400 mt-0.5">vs tháng trước: {{ $stats['cancel_rate_last'] }}%</p>
    </div>

    <div class="admin-card px-4 py-3 hover:shadow-md transition-all">
        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block">Biên lợi nhuận</span>
        <div class="flex items-end gap-1 mt-0.5">
            <span class="text-sm font-black text-gray-800">{{ $stats['profit_margin'] }}%</span>
            <span class="text-[9px] font-bold text-green-600 mb-0.5">Từ DB</span>
        </div>
        <p class="text-[9px] text-gray-400 mt-0.5">TB (giá bán − giá nhập) / giá bán</p>
    </div>

    <div class="admin-card px-4 py-3 hover:shadow-md transition-all">
        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-wider block">Giá trị TB (AOV)</span>
        <div class="flex items-end gap-1 mt-0.5">
            <span class="text-sm font-black text-gray-900">{{ number_format($stats['aov'] / 1000, 0) }}K ₫</span>
            <span class="text-[9px] font-bold {{ $stats['aov_diff'] >= 0 ? 'text-green-600' : 'text-red-600' }} mb-0.5">
                {{ $stats['aov_diff'] >= 0 ? '+' : '' }}{{ number_format($stats['aov_diff'] / 1000, 0) }}K
            </span>
        </div>
        <p class="text-[9px] text-gray-400 mt-0.5">vs tháng trước: {{ number_format($stats['aov_last'] / 1000, 0) }}K ₫</p>
    </div>

    <div class="admin-card px-4 py-3 hover:shadow-md transition-all">
        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-wider block">Trọn đời KH (CLV)</span>
        <div class="flex items-end gap-1 mt-0.5">
            <span class="text-sm font-black text-gray-900">{{ number_format($stats['clv'] / 1000000, 1) }}M ₫</span>
            <span class="text-[9px] font-bold text-blue-600 mb-0.5">Từ DB</span>
        </div>
        <p class="text-[9px] text-gray-400 mt-0.5">TB tổng chi tiêu mỗi khách</p>
    </div>

</div>

{{-- ================================================================ --}}
{{-- 4. CHARTS & ANALYTICS --}}
{{-- ================================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- 4a. Bar Chart + Line Chart overlay --}}
    <div class="lg:col-span-2 admin-card p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="font-bold text-gray-800 flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                    Doanh thu & Xu hướng
                    <span class="material-symbols-outlined text-gray-400 text-base cursor-help">info</span>
                </h3>
                <div class="flex flex-wrap items-center gap-4 mt-1.5">
                    <div class="flex items-center gap-1.5 text-[11px] font-bold text-primary">
                        <span class="w-3 h-3 bg-primary rounded-sm inline-block"></span> 
                        @if($period === 'year') Năm nay @elseif($period === 'month') Tháng này @else Tuần này @endif
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-bold text-gray-400">
                        <span class="w-6 h-0 border-t-2 border-dashed border-gray-400 inline-block"></span> 
                        @if($period === 'year') Năm trước @elseif($period === 'month') Tháng trước @else Tuần trước @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                {{-- Period Selector --}}
                <div class="relative">
                    <select onchange="window.location.href='?period=' + this.value" 
                            class="appearance-none bg-gray-50 border border-gray-100 text-gray-700 text-[11px] font-bold rounded-xl py-1.5 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all cursor-pointer">
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>7 Ngày qua</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>30 Ngày qua</option>
                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>12 Tháng qua</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                        <span class="material-symbols-outlined text-[16px]">expand_more</span>
                    </div>
                </div>

                <div class="flex items-center bg-gray-50 rounded-lg p-1 border border-gray-100">
                    <button class="p-1 hover:text-primary transition-colors" title="Xuất Excel">
                        <span class="material-symbols-outlined text-[18px]">table_view</span>
                    </button>
                    <button class="p-1 hover:text-primary transition-colors" title="Xuất PDF">
                        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="h-72 w-full relative mt-4">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- 4b. Analytics Panel --}}
    <div class="admin-card p-6 flex flex-col space-y-5">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-gray-800" style="font-family: 'Montserrat', sans-serif;">Phân tích nâng cao</h3>
        </div>

        {{-- Conversion Metrics 2x2 – Real Data --}}
        <div class="grid grid-cols-2 gap-2">
            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 hover:bg-white hover:shadow-sm transition-all">
                <p class="text-[9px] font-bold text-gray-500 uppercase mb-1">Conv. Rate</p>
                <p class="text-sm font-black text-gray-800">{{ $stats['conversion_rate'] }}%</p>
                <span class="text-[9px] text-green-600 font-bold">Từ DB ↑</span>
            </div>
            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 hover:bg-white hover:shadow-sm transition-all">
                <p class="text-[9px] font-bold text-gray-500 uppercase mb-1">Tỷ lệ hủy</p>
                <p class="text-sm font-black text-gray-800">{{ $stats['cancel_rate'] }}%</p>
                <span class="text-[9px] {{ $stats['cancel_rate'] <= 5 ? 'text-green-600' : 'text-red-600' }} font-bold">
                    {{ $stats['cancel_rate'] <= 5 ? 'Tốt ↓' : 'Cao ↑' }}
                </span>
            </div>
            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 hover:bg-white hover:shadow-sm transition-all">
                <p class="text-[9px] font-bold text-gray-500 uppercase mb-1">Biên LN</p>
                <p class="text-sm font-black text-gray-800">{{ $stats['profit_margin'] }}%</p>
                <span class="text-[9px] text-green-600 font-bold">Từ DB</span>
            </div>
            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 hover:bg-white hover:shadow-sm">
                <p class="text-[9px] font-bold text-gray-500 uppercase mb-1">AOV</p>
                <p class="text-sm font-black text-gray-800">{{ number_format($stats['aov'] / 1000, 0) }}K</p>
                <span class="text-[9px] {{ $stats['aov_diff'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-bold">
                    {{ $stats['aov_diff'] >= 0 ? '+' : '' }}{{ number_format($stats['aov_diff'] / 1000, 0) }}K
                </span>
            </div>
        </div>

        {{-- Nguyên nhân hủy đơn – FROM DB --}}
        <div>
            <p class="text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">Nguyên nhân hủy đơn (Tháng)</p>
            <div class="space-y-2">
                @forelse($cancelReasons as $reason)
                <div class="flex items-center gap-2">
                    <span class="text-[10px] text-gray-600 font-medium w-24 flex-shrink-0 truncate">{{ $reason['label'] }}</span>
                    <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                        <div class="cancel-reason-bar {{ $reason['color'] }} h-1.5 rounded-full transition-all duration-1000 ease-out" style="width: 0%" data-width="{{ $reason['pct'] }}"></div>
                    </div>
                    <span class="text-[10px] font-bold text-gray-500 w-7 text-right">{{ $reason['pct'] }}%</span>
                </div>
                @empty
                <p class="text-[10px] text-gray-400 italic">Không có đơn hủy trong tháng này.</p>
                @endforelse
            </div>
        </div>

        {{-- Phân khúc khách hàng – FROM DB --}}
        <div class="pt-2 border-t border-gray-50">
            <p class="text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">Phân khúc khách hàng</p>
            <div class="space-y-2">
                <div class="flex items-center justify-between p-2 rounded-lg bg-yellow-50 border border-yellow-100">
                    <div class="flex items-center gap-2">
                        <span class="text-sm">👑</span>
                        <div>
                            <p class="text-[10px] font-bold text-gray-700">VIP (Chi ≥ 2M)</p>
                            <p class="text-[9px] text-gray-500">CLV > 2M ₫</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-black text-yellow-700">{{ $vipCustomers }} KH</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-lg bg-blue-50 border border-blue-100">
                    <div class="flex items-center gap-2">
                        <span class="text-sm">🔄</span>
                        <div>
                            <p class="text-[10px] font-bold text-gray-700">Quay lại</p>
                            <p class="text-[9px] text-gray-500">Mua ≥ 2 lần</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-black text-blue-700">{{ $returningCustomers }} KH</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-lg bg-green-50 border border-green-100">
                    <div class="flex items-center gap-2">
                        <span class="text-sm">🌱</span>
                        <div>
                            <p class="text-[10px] font-bold text-gray-700">Khách mới</p>
                            <p class="text-[9px] text-gray-500">Tháng này</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-black text-green-700">+{{ $newCustomersMonth }} KH</span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ================================================================ --}}
{{-- 5. NEW PROFESSIONAL CHARTS (4 charts in 2x2 grid) --}}
{{-- ================================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- 5a. Top 10 Sách Bán Chạy (Horizontal Bar) --}}
    <div class="admin-card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                <span class="material-symbols-outlined text-primary">local_fire_department</span>
                Top Sách Bán Chạy
            </h3>
            <span class="text-[9px] bg-primary/10 text-primary px-2 py-0.5 rounded-full font-bold">REAL-TIME</span>
        </div>
        <div class="h-72 w-full">
            <canvas id="topBooksChart"></canvas>
        </div>
    </div>

    {{-- 5b. Cơ Cấu Doanh Thu Theo Danh Mục (Doughnut) --}}
    <div class="admin-card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                <span class="material-symbols-outlined text-indigo-500">donut_large</span>
                Doanh thu theo Danh mục
            </h3>
            <span class="text-[9px] bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-full font-bold">PHÂN TÍCH</span>
        </div>
        <div class="h-72 w-full flex items-center justify-center">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    {{-- 5c. Phân loại Khách hàng (Doughnut) --}}
    <div class="admin-card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                <span class="material-symbols-outlined text-emerald-500">groups</span>
                Phân loại Khách hàng
            </h3>
            <span class="text-[9px] bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded-full font-bold">TỔNG: {{ $totalCustomers }}</span>
        </div>
        <div class="h-72 w-full flex items-center justify-center">
            <canvas id="customerChart"></canvas>
        </div>
    </div>

    {{-- 5d. Trạng thái Kho hàng (Doughnut) --}}
    <div class="admin-card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                <span class="material-symbols-outlined text-amber-500">warehouse</span>
                Trạng thái Kho hàng
            </h3>
            <span class="text-[9px] bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full font-bold">TỔNG: {{ $stockInStock + $stockLow + $stockOut }}</span>
        </div>
        <div class="h-72 w-full flex items-center justify-center">
            <canvas id="stockChart"></canvas>
        </div>
    </div>

</div>

{{-- ================================================================ --}}
{{-- 6. RECENT ORDERS TABLE + ACTIVITY FEED --}}
{{-- ================================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-4">

    {{-- 6a. Recent Orders Table --}}
    <div class="lg:col-span-2 admin-card overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-gray-800" style="font-family: 'Montserrat', sans-serif;">Đơn hàng gần đây</h3>
                <a class="text-xs text-primary font-bold hover:underline flex items-center gap-1" href="{{ route('admin.orders.index') }}">
                    Xem tất cả <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
            </div>
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <button class="px-3 py-1 bg-primary text-white text-[11px] font-bold rounded-full whitespace-nowrap">Tất cả</button>
                <button class="px-3 py-1 bg-yellow-50 text-yellow-700 border border-yellow-200 text-[11px] font-bold rounded-full hover:bg-yellow-100 transition-colors whitespace-nowrap">Chờ xử lý</button>
                <button class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 text-[11px] font-bold rounded-full hover:bg-blue-100 transition-colors whitespace-nowrap">Đang giao</button>
                <button class="px-3 py-1 bg-green-50 text-green-700 border border-green-200 text-[11px] font-bold rounded-full hover:bg-green-100 transition-colors whitespace-nowrap">Hoàn thành</button>
                <button class="px-3 py-1 bg-slate-50 text-slate-700 border border-slate-200 text-[11px] font-bold rounded-full hover:bg-slate-100 transition-colors whitespace-nowrap">Đã hủy</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] text-left">
                <thead>
                    <tr class="bg-gray-50/80 text-[9px] text-gray-500 uppercase font-bold tracking-wider border-b border-gray-100">
                        <th class="px-5 py-3">Mã đơn</th>
                        <th class="px-5 py-3">Khách hàng</th>
                        <th class="px-5 py-3">Trạng thái</th>
                        <th class="px-5 py-3 text-right">Tổng tiền</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50/80 transition-colors cursor-pointer">
                            <td class="px-5 py-3 font-bold text-gray-800 text-xs">{{ $order->order_number ?? '#ORD-'.$order->id }}</td>
                            <td class="px-5 py-3 text-xs text-gray-700">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $trClass = match($order->status?->value) {
                                        'pending', 'confirmed' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'packing', 'shipping', 'delivered' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'completed' => 'bg-green-50 text-green-700 border-green-200',
                                        'cancelled', 'returned' => 'bg-slate-50 text-slate-700 border-slate-200',
                                        default => 'bg-gray-50 text-gray-600 border-gray-200',
                                    };
                                    $trIcon = match($order->status?->value) {
                                        'pending', 'confirmed' => 'schedule',
                                        'packing', 'shipping', 'delivered' => 'local_shipping',
                                        'completed' => 'check_circle',
                                        'cancelled', 'returned' => 'cancel',
                                        default => 'info',
                                    };
                                @endphp
                                <span class="{{ $trClass }} text-[10px] font-bold px-2 py-0.5 rounded-full border flex items-center gap-1 w-fit">
                                    <span class="material-symbols-outlined text-[13px]">{{ $trIcon }}</span>
                                    {{ $order->status?->label() ?? 'Không rõ' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 font-black text-right text-gray-900 text-xs">{{ number_format($order->total ?? 0, 0, ',', '.') }} ₫</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-6 text-center text-sm text-gray-400 italic">Chưa có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 6b. Nhật ký hệ thống (Black Box) --}}
    <div class="admin-card p-5 flex flex-col">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
                <h3 class="font-bold text-gray-800" style="font-family: 'Montserrat', sans-serif;">Nhật ký hệ thống</h3>
                <span class="text-[8px] bg-primary/10 text-primary px-1.5 py-0.5 rounded-full font-black uppercase">Black Box</span>
            </div>
            <span class="flex items-center gap-1 text-[10px] text-green-600 font-bold">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse-green"></span>Trực tiếp
            </span>
        </div>
        <div class="flex-1 space-y-3 overflow-y-auto max-h-[350px] pr-1">
            @forelse($systemLogs as $idx => $log)
            <div class="flex gap-3 relative {{ $idx < count($systemLogs) - 1 ? 'before:absolute before:left-[9px] before:top-6 before:w-0.5 before:h-[calc(100%)] before:bg-gray-100' : '' }}">
                <div class="w-5 h-5 rounded-full {{ $log->type_color }} ring-2 ring-white z-10 flex-shrink-0 mt-0.5 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-white text-[10px]" style="font-variation-settings: 'FILL' 1">{{ $log->type_icon }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="inline-flex items-center px-1.5 py-0 rounded text-[8px] font-bold border {{ $log->level_color }}">
                            {{ ucfirst($log->level) }}
                        </span>
                        <span class="text-[9px] font-mono text-gray-400 bg-gray-50 px-1 rounded">{{ $log->action }}</span>
                    </div>
                    <p class="text-[11px] font-semibold text-gray-800 leading-snug truncate" title="{{ $log->description }}">{{ $log->description }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <p class="text-[9px] text-gray-400 font-medium">{{ $log->created_at->diffForHumans() }}</p>
                        <span class="text-[9px] text-gray-300">•</span>
                        <p class="text-[9px] text-gray-400 font-medium">{{ $log->user_name ?? 'Hệ thống' }}</p>
                        @if($log->ip_address)
                            <span class="text-[9px] text-gray-300">•</span>
                            <p class="text-[8px] font-mono text-gray-300">{{ $log->ip_address }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <span class="material-symbols-outlined text-3xl text-gray-300">folder_open</span>
                <p class="text-[11px] text-gray-400 mt-2 font-medium">Chưa có bản ghi nào.</p>
                <p class="text-[9px] text-gray-300">Hệ thống sẽ tự động ghi log khi có hoạt động.</p>
            </div>
            @endforelse
        </div>
        <a href="{{ route('admin.system-logs.index') }}" class="w-full mt-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-[10px] font-bold text-gray-600 hover:bg-primary hover:text-white hover:border-primary transition-all uppercase tracking-widest flex items-center justify-center gap-1">
            <span class="material-symbols-outlined text-[14px]">terminal</span>
            Xem toàn bộ nhật ký
        </a>
    </div>

</div>

@push('scripts')
<script>
window.dashboardConfig = {
    period: "{{ $period }}",
    chartLabels: {!! json_encode($chartData['labels']) !!},
    chartLastPeriod: {!! json_encode($chartData['lastPeriod']) !!},
    chartThisPeriod: {!! json_encode($chartData['thisPeriod']) !!},
    sparkRevenue: {!! json_encode($sparkRevenue) !!},
    sparkOrders: {!! json_encode($sparkOrders) !!},
    conversionRate: {{ $stats['conversion_rate'] }},
    topBooksLabels: {!! json_encode($topBooks->pluck('title')->map(fn($n) => mb_strlen($n) > 25 ? mb_substr($n, 0, 25).'…' : $n)) !!},
    topBooksData: {!! json_encode($topBooks->pluck('total_revenue')->map(fn($v) => round($v / 1000000, 1))) !!},
    categoryLabels: {!! json_encode($categoryRevenue->pluck('ten_danh_muc')) !!},
    categoryData: {!! json_encode($categoryRevenue->pluck('revenue')->map(fn($v) => round($v / 1000000, 1))) !!},
    customerStats: {
        vip: {{ $vipCustomers }},
        returning: {{ $returningCustomers }},
        new: {{ $newCustomersMonth }},
        others: {{ max(0, $totalCustomers - $vipCustomers - $returningCustomers - $newCustomersMonth) }}
    },
    stockStats: {
        inStock: {{ $stockInStock }},
        low: {{ $stockLow }},
        out: {{ $stockOut }}
    }
};
</script>
<script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endpush

@endsection

