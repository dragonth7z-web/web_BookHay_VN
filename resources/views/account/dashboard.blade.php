@extends('layouts.app')

@section('title', 'Tài khoản cá nhân - THLD')

@section('content')
    <!-- Custom Styles cho trang Tài khoản -->
    <style>
        .soft-shadow {
            box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.05);
        }

        .account-sidebar-item {
            @apply flex items-center gap-3 py-3.5 px-4 rounded-lg text-sm font-medium text-gray-700 hover:text-primary hover:bg-red-50 transition-all cursor-pointer;
        }

        .account-sidebar-item.active {
            @apply bg-red-50 text-primary font-bold border-r-4 border-primary;
        }

        .dashboard-card {
            @apply bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden;
        }
    </style>

    <main class="max-w-main mx-auto px-4 py-8">
        <nav class="flex text-xs font-medium text-charcoal mb-6 gap-2 items-center">
            <a class="hover:text-primary" href="{{ url('/') }}">Trang chủ</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-gray-400">Tài khoản cá nhân</span>
        </nav>
        <div class="grid grid-cols-12 gap-8">
            <!-- Sidebar Tài khoản -->
            <aside class="col-span-12 lg:col-span-3">
                <div class="dashboard-card p-4">
                    <div class="flex items-center gap-4 mb-6 p-2 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-3xl">account_circle</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-charcoal uppercase">Tài khoản của</p>
                            <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                        </div>
                    </div>
                    <nav class="space-y-1">
                        <a class="account-sidebar-item active" href="#">
                            <span class="material-symbols-outlined">person</span> Thông tin cá nhân
                        </a>
                        <a class="account-sidebar-item" href="#">
                            <span class="material-symbols-outlined">list_alt</span> Đơn hàng của tôi
                        </a>
                        <a class="account-sidebar-item" href="#">
                            <span class="material-symbols-outlined">favorite</span> Sách yêu thích
                        </a>
                        <a class="account-sidebar-item" href="#">
                            <span class="material-symbols-outlined">stars</span> Điểm tích lũy
                        </a>
                        <a class="account-sidebar-item" href="#">
                            <span class="material-symbols-outlined">notifications</span> Thông báo của tôi
                        </a>
                        <a class="account-sidebar-item" href="#">
                            <span class="material-symbols-outlined">location_on</span> Sổ địa chỉ
                        </a>
                        <div class="border-t border-gray-100 my-2"></div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                        <a class="account-sidebar-item text-red-600 hover:bg-red-50" href="javascript:void(0)" onclick="document.getElementById('logout-form').submit();">
                            <span class="material-symbols-outlined">logout</span> Đăng xuất
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Nội dung chính Dashboard -->
            <div class="col-span-12 lg:col-span-9 space-y-6">
                <!-- Banner Điểm tích lũy -->
                <div
                    class="dashboard-card bg-gradient-to-r from-primary to-primary-dark p-8 relative overflow-hidden group">
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                        <div class="text-white text-center md:text-left">
                            <h2 class="text-2xl font-black mb-2">Chào mừng trở lại,
                                {{ explode(' ', $user->name)[count(explode(' ', $user->name)) - 1] }}!</h2>
                            <p class="text-white/80 text-sm font-medium">Hôm nay là một ngày tuyệt vời để tiếp tục hành
                                trình tri thức của bạn.</p>
                        </div>
                        <div
                            class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 flex flex-col items-center min-w-[200px]">
                            <span
                                class="material-symbols-outlined text-secondary text-4xl mb-1 drop-shadow-md">workspace_premium</span>
                            <p class="text-white text-xs font-bold uppercase tracking-widest opacity-80">Điểm tích lũy</p>
                            <p class="text-white text-3xl font-black">{{ number_format($user->loyalty_points, 0, ',', '.') }}
                                <span class="text-sm font-bold opacity-80 uppercase">Xu</span></p>
                            <button
                                class="mt-4 bg-secondary text-white text-[10px] font-bold px-4 py-1.5 rounded-full hover:bg-white hover:text-secondary transition-all uppercase">Đổi
                                quà ngay</button>
                        </div>
                    </div>
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full"></div>
                    <div class="absolute -bottom-20 -left-10 w-60 h-60 bg-black/5 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tiến độ đọc sách -->
                    <div class="dashboard-card p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">auto_stories</span> TIẾN ĐỘ ĐỌC SÁCH
                            </h3>
                            <a class="text-primary text-[10px] font-bold hover:underline" href="#">CHI TIẾT</a>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="w-20 h-28 bg-gray-100 rounded shadow-sm flex-shrink-0 overflow-hidden border border-gray-100">
                                <img alt="Reading Book" class="w-full h-full object-contain p-1 bg-white"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBZSy91n-29JdShSct_BNELci64thiyZw4xdLr-eya8pb7gIsSsPlL4hTdV8LuvLGbzlyPpzYNVFVtiC-tTInAT7sowc_kAOG8Yl2G5LvcFiI5HvXjPhokBL2FuqOu-BUzd5UXpa4yAp_MbqctUqetuW2VbfwN3PC8uC-93SR0XHXFC0p4nN50vAB63FL8VuZKGPtnvcXpo63X0WUUnm8vLvzxXLMhuxsVCIe0oMkCP6uwfhFO0USb9yIXEM3xn8Wz_nEdQDStejow" />
                            </div>
                            <div class="flex-1 flex flex-col justify-center">
                                <h4 class="text-sm font-bold text-gray-800 line-clamp-1 mb-1">Cây Cam Ngọt Của Tôi</h4>
                                <p class="text-[10px] text-charcoal font-semibold mb-3 italic">"Mọi đứa trẻ đều xứng đáng
                                    được hạnh phúc"</p>
                                <div class="relative w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="absolute top-0 left-0 h-full bg-green-500" style="width: 65%"></div>
                                </div>
                                <div class="flex justify-between mt-1.5">
                                    <span class="text-[10px] text-gray-500 font-bold">Đã đọc 65%</span>
                                    <span class="text-[10px] text-charcoal font-bold">120/184 trang</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thống kê -->
                    <div class="dashboard-card p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">analytics</span> THỐNG KÊ GẦN ĐÂY
                            </h3>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 text-center">
                                <p class="text-[10px] font-bold text-blue-600 uppercase mb-1">Tổng đơn</p>
                                <p class="text-xl font-black text-blue-800">{{ $orderStats['total'] }} <span
                                        class="text-[10px] font-bold">đơn</span></p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-xl border border-purple-100 text-center">
                                <p class="text-[10px] font-bold text-purple-600 uppercase mb-1">Hoàn thành</p>
                                <p class="text-xl font-black text-purple-800">{{ $orderStats['completed'] }} <span
                                        class="text-[10px] font-bold">đơn</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Đơn hàng gần đây -->
                <div class="dashboard-card">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2 uppercase tracking-tight">
                            <span class="material-symbols-outlined text-primary">shopping_bag</span> Đơn hàng gần đây
                        </h3>
                        <a class="text-primary text-[11px] font-bold flex items-center gap-1 hover:underline" href="#">
                            XEM TẤT CẢ <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[600px]">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-6 py-4 text-[11px] font-black text-charcoal uppercase tracking-wider">Mã
                                        đơn hàng</th>
                                    <th class="px-6 py-4 text-[11px] font-black text-charcoal uppercase tracking-wider">Ngày
                                        mua</th>
                                    <th class="px-6 py-4 text-[11px] font-black text-charcoal uppercase tracking-wider">Sản
                                        phẩm</th>
                                    <th
                                        class="px-6 py-4 text-[11px] font-black text-charcoal uppercase tracking-wider text-right">
                                        Tổng tiền</th>
                                    <th
                                        class="px-6 py-4 text-[11px] font-black text-charcoal uppercase tracking-wider text-center">
                                        Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentOrders as $order)
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <td class="px-6 py-4 text-sm font-bold text-primary">#{{ $order->order_number }}</td>
                                        <td class="px-6 py-4 text-xs font-semibold text-charcoal">
                                            {{ $order->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-xs font-medium text-gray-800">
                                            @php
                                                $firstItem = $order->items->first();
                                                $remainingCount = $order->items->count() - 1;
                                            @endphp
                                            @if($firstItem)
                                                {{ $firstItem->book->title ?? $firstItem->book_title_snapshot }}
                                                @if($remainingCount > 0)
                                                    + {{ $remainingCount }} sản phẩm khác...
                                                @endif
                                            @else
                                                Trống
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-black text-gray-800 text-right">
                                            {{ number_format($order->total, 0, ',', '.') }} đ</td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $statusClass = match ($order->status?->value) {
                                                    'completed', 'delivered' => 'bg-green-100 text-green-700 border-green-200',
                                                    'pending'   => 'bg-orange-100 text-orange-700 border-orange-200',
                                                    'shipping'  => 'bg-blue-100 text-blue-700 border-blue-200',
                                                    'cancelled' => 'bg-gray-100 text-gray-600 border-gray-200',
                                                    default     => 'bg-slate-100 text-slate-600 border-slate-200'
                                                };
                                            @endphp
                                            <span class="{{ $statusClass }} text-[10px] font-bold px-3 py-1 rounded-full border uppercase">
                                                {{ $order->status?->value }}
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

                <!-- Box Gợi ý -->
                <div class="dashboard-card p-6 bg-orange-50/30 border-orange-100">
                    <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                        <div
                            class="w-16 h-16 rounded-full bg-secondary/10 flex items-center justify-center text-secondary flex-shrink-0">
                            <span class="material-symbols-outlined text-4xl">lightbulb</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm mb-1 uppercase tracking-tight">Cập nhật sở thích đọc
                                sách?</h4>
                            <p class="text-xs text-charcoal font-medium">Để THLD gợi ý cho bạn những tựa sách phù hợp hơn
                                với phong cách cá nhân.</p>
                        </div>
                        <button
                            class="bg-secondary text-white font-bold px-6 py-2.5 rounded-lg text-xs hover:opacity-90 transition-all uppercase whitespace-nowrap mt-2 sm:mt-0">Cập
                            nhật ngay</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
