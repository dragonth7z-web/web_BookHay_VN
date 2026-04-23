@extends('layouts.app')

@section('title', 'Tổng quan tài khoản - THLD')

@section('content')
<div class="min-h-screen bg-[#F0F2F5] py-8">
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex flex-col lg:flex-row gap-6 min-h-[600px]">

            {{-- ── Sidebar ── --}}
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="flex items-center gap-4 mb-6 px-2">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary bg-gray-100">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=C92127&color=fff" alt="Avatar" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tài khoản của</p>
                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                    </div>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('account.profile') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">person</span>
                        <span>Thông tin cá nhân</span>
                    </a>
                    <a href="{{ route('account.orders') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">package_2</span>
                        <span>Đơn hàng của tôi</span>
                    </a>
                    <a href="{{ route('account.wishlist') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">favorite</span>
                        <span>Sách yêu thích</span>
                    </a>
                    <a href="{{ route('account.notifications') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                        <span>Thông báo</span>
                    </a>
                    <a href="{{ route('account.addresses') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">location_on</span>
                        <span>Sổ địa chỉ</span>
                    </a>
                    <a href="{{ route('account.coupons') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">confirmation_number</span>
                        <span>Kho Voucher</span>
                    </a>
                    <div class="pt-4 mt-4 border-t border-gray-100">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-dashboard" class="hidden">@csrf</form>
                        <a href="javascript:void(0)"
                            onclick="document.getElementById('logout-form-dashboard').submit();"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-red-500 font-medium transition-all duration-200 hover:bg-red-50 hover:text-red-600">
                            <span class="material-symbols-outlined text-xl">logout</span>
                            <span>Đăng xuất</span>
                        </a>
                    </div>
                </nav>
            </aside>

            {{-- ── Main Content ── --}}
            <section class="flex-1 min-w-0 space-y-5">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-sm">
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-primary font-medium transition-colors">Trang chủ</a>
                    <span class="material-symbols-outlined text-gray-400 text-base">chevron_right</span>
                    <span class="text-primary font-bold">Tổng quan tài khoản</span>
                </nav>

                {{-- ── Welcome Banner ── --}}
                {{-- $user->first_name and $user->formatted_loyalty_points come from User Model Accessors --}}
                <div class="bg-gradient-to-r from-primary to-primary/80 rounded-[18px] p-8 relative overflow-hidden">
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                        <div class="text-white text-center md:text-left">
                            <h2 class="text-2xl font-black mb-2">
                                Chào mừng trở lại, {{ $user->first_name }}!
                            </h2>
                            <p class="text-white/80 text-sm font-medium">
                                Hôm nay là một ngày tuyệt vời để tiếp tục hành trình tri thức của bạn.
                            </p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 flex flex-col items-center min-w-[200px]">
                            <span class="material-symbols-outlined text-yellow-300 text-4xl mb-1">workspace_premium</span>
                            <p class="text-white text-xs font-bold uppercase tracking-widest opacity-80">Điểm tích lũy</p>
                            <p class="text-white text-3xl font-black">
                                {{ $user->formatted_loyalty_points }}
                                <span class="text-sm font-bold opacity-80 uppercase">Xu</span>
                            </p>
                            <button class="mt-4 bg-white/20 text-white text-[10px] font-bold px-4 py-1.5 rounded-full hover:bg-white hover:text-primary transition-all uppercase">
                                Đổi quà ngay
                            </button>
                        </div>
                    </div>
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>
                    <div class="absolute -bottom-20 -left-10 w-60 h-60 bg-black/5 rounded-full pointer-events-none"></div>
                </div>

                {{-- ── Stats + Reading Progress ── --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Order Stats --}}
                    <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-xl">analytics</span>
                                Thống kê đơn hàng
                            </h3>
                            <a href="{{ route('account.orders') }}" class="text-primary text-[10px] font-bold hover:underline uppercase">
                                Xem tất cả
                            </a>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 text-center">
                                <p class="text-[10px] font-bold text-blue-600 uppercase mb-1">Tổng đơn</p>
                                <p class="text-2xl font-black text-blue-800">
                                    {{ $orderStats['total'] }}
                                    <span class="text-[10px] font-bold">đơn</span>
                                </p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-xl border border-green-100 text-center">
                                <p class="text-[10px] font-bold text-green-600 uppercase mb-1">Hoàn thành</p>
                                <p class="text-2xl font-black text-green-800">
                                    {{ $orderStats['completed'] }}
                                    <span class="text-[10px] font-bold">đơn</span>
                                </p>
                            </div>
                            <div class="bg-orange-50 p-4 rounded-xl border border-orange-100 text-center">
                                <p class="text-[10px] font-bold text-orange-600 uppercase mb-1">Chờ xác nhận</p>
                                <p class="text-2xl font-black text-orange-800">
                                    {{ $orderStats['pending'] }}
                                    <span class="text-[10px] font-bold">đơn</span>
                                </p>
                            </div>
                            <div class="bg-sky-50 p-4 rounded-xl border border-sky-100 text-center">
                                <p class="text-[10px] font-bold text-sky-600 uppercase mb-1">Đang giao</p>
                                <p class="text-2xl font-black text-sky-800">
                                    {{ $orderStats['shipping'] }}
                                    <span class="text-[10px] font-bold">đơn</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Currently Reading --}}
                    {{-- $currentlyReading comes from DashboardService → DashboardRepository --}}
                    <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-xl">auto_stories</span>
                                Đang đọc
                            </h3>
                            <a href="{{ route('account.bookshelf') }}" class="text-primary text-[10px] font-bold hover:underline uppercase">
                                Tủ sách
                            </a>
                        </div>

                        @if($currentlyReading && $currentlyReading->book)
                            @php
                                $book = $currentlyReading->book;
                                $pct  = ($currentlyReading->current_page && $currentlyReading->total_pages)
                                    ? min(100, round($currentlyReading->current_page / $currentlyReading->total_pages * 100))
                                    : 0;
                            @endphp
                            <div class="flex gap-4">
                                <div class="w-16 h-22 flex-shrink-0 rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                                    <img src="{{ $book->cover_image_url }}"
                                        alt="{{ $book->title }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 flex flex-col justify-center">
                                    <h4 class="text-sm font-bold text-gray-800 line-clamp-2 mb-1">{{ $book->title }}</h4>
                                    @if($book->authors->isNotEmpty())
                                        <p class="text-xs text-gray-500 mb-3">{{ $book->authors->pluck('name')->first() }}</p>
                                    @endif
                                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-green-500 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <div class="flex justify-between mt-1.5">
                                        <span class="text-[10px] text-gray-500 font-bold">Đã đọc {{ $pct }}%</span>
                                        @if($currentlyReading->current_page && $currentlyReading->total_pages)
                                            <span class="text-[10px] text-gray-500 font-bold">
                                                {{ $currentlyReading->current_page }}/{{ $currentlyReading->total_pages }} trang
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-32 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-4xl mb-2">menu_book</span>
                                <p class="text-sm text-gray-400 font-medium">Chưa có sách đang đọc</p>
                                <a href="{{ route('books.search') }}" class="mt-2 text-xs font-bold text-primary hover:underline">
                                    Khám phá sách ngay
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ── Recent Orders ── --}}
                {{-- order_summary, status_badge_class, status_label, formatted_total come from Order Model Accessors --}}
                <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-xl">shopping_bag</span>
                            Đơn hàng gần đây
                        </h3>
                        <a href="{{ route('account.orders') }}"
                            class="text-primary text-[11px] font-bold flex items-center gap-1 hover:underline uppercase">
                            Xem tất cả
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[600px]">
                            <thead>
                                <tr class="bg-gray-50/60">
                                    <th class="px-6 py-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">Mã đơn hàng</th>
                                    <th class="px-6 py-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">Ngày mua</th>
                                    <th class="px-6 py-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                    <th class="px-6 py-4 text-[11px] font-black text-gray-500 uppercase tracking-wider text-right">Tổng tiền</th>
                                    <th class="px-6 py-4 text-[11px] font-black text-gray-500 uppercase tracking-wider text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentOrders as $order)
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <td class="px-6 py-4 text-sm font-bold text-primary">
                                            <a href="{{ route('account.orders.show', $order->id) }}" class="hover:underline">
                                                #{{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-semibold text-gray-600">
                                            {{ $order->created_at->format('d/m/Y') }}
                                        </td>
                                        {{-- order_summary is a Model Accessor --}}
                                        <td class="px-6 py-4 text-xs font-medium text-gray-800 max-w-[200px] truncate">
                                            {{ $order->order_summary }}
                                        </td>
                                        {{-- formatted_total is a Model Accessor --}}
                                        <td class="px-6 py-4 text-sm font-black text-gray-800 text-right">
                                            {{ $order->formatted_total }}
                                        </td>
                                        {{-- status_badge_class and status_label are Model Accessors --}}
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-[10px] font-bold px-3 py-1 rounded-full border uppercase {{ $order->status_badge_class }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm font-medium italic">
                                            Bạn chưa có đơn hàng nào gần đây.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ── Suggestion Banner ── --}}
                <div class="bg-white rounded-[18px] border border-orange-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 bg-orange-50/30">
                    <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                        <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center text-orange-500 flex-shrink-0">
                            <span class="material-symbols-outlined text-3xl">lightbulb</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm mb-1 uppercase tracking-tight">
                                Cập nhật sở thích đọc sách?
                            </h4>
                            <p class="text-xs text-gray-500 font-medium">
                                Để THLD gợi ý cho bạn những tựa sách phù hợp hơn với phong cách cá nhân.
                            </p>
                        </div>
                        <a href="{{ route('account.profile') }}"
                            class="bg-orange-500 text-white font-bold px-6 py-2.5 rounded-xl text-xs hover:bg-orange-600 transition-all uppercase whitespace-nowrap mt-2 sm:mt-0">
                            Cập nhật ngay
                        </a>
                    </div>
                </div>

            </section>
        </div>
    </div>
</div>
@endsection
