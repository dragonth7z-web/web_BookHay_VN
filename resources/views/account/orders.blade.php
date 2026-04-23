@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - THLD')

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
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold bg-primary text-white shadow-[0_6px_20px_rgba(201,33,39,0.25)] transition-all duration-200">
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
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-orders" class="hidden">@csrf</form>
                        <a href="javascript:void(0)"
                            onclick="document.getElementById('logout-form-orders').submit();"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-red-500 font-medium transition-all duration-200 hover:bg-red-50 hover:text-red-600">
                            <span class="material-symbols-outlined text-xl">logout</span>
                            <span>Đăng xuất</span>
                        </a>
                    </div>
                </nav>
            </aside>

            {{-- ── Main Content ── --}}
            <section class="flex-1 min-w-0">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 mb-5 text-sm">
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-primary font-medium transition-colors">Trang chủ</a>
                    <span class="material-symbols-outlined text-gray-400 text-base">chevron_right</span>
                    <a href="{{ route('account.profile') }}" class="text-gray-500 hover:text-primary font-medium transition-colors">Tài khoản</a>
                    <span class="material-symbols-outlined text-gray-400 text-base">chevron_right</span>
                    <span class="text-primary font-bold">Đơn hàng của tôi</span>
                </nav>

                {{-- Page Header + Status Tabs --}}
                <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] mb-5 overflow-hidden">
                    <div class="px-7 py-5 border-b border-gray-100">
                        <h1 class="text-2xl font-bold text-gray-900">Đơn hàng của tôi</h1>
                        <p class="text-sm text-gray-500 mt-0.5">Theo dõi và quản lý tất cả đơn hàng của bạn.</p>
                    </div>

                    {{-- Status filter tabs --}}
                    <div class="flex overflow-x-auto text-sm font-medium border-b border-gray-100">
                        @php
                            $tabs = [
                                ''          => 'Tất cả',
                                'pending'   => 'Chờ xác nhận',
                                'shipping'  => 'Đang giao',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã hủy',
                            ];
                        @endphp
                        @foreach($tabs as $value => $label)
                            <a href="{{ route('account.orders', $value ? ['status' => $value] : []) }}"
                                class="flex-shrink-0 px-6 py-4 text-center transition-colors whitespace-nowrap
                                    {{ request('status', '') === $value
                                        ? 'border-b-2 border-primary text-primary font-bold'
                                        : 'text-gray-500 hover:text-primary' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Search --}}
                    <div class="px-5 py-4">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
                            <input
                                class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                placeholder="Tìm đơn hàng theo Mã đơn hàng hoặc Tên sản phẩm"
                                type="text" />
                        </div>
                    </div>
                </div>

                {{-- Order List --}}
                <div class="space-y-4">
                    @forelse($orders as $order)
                        @php
                            $statusConfig = match($order->status?->value) {
                                'completed', 'delivered' => ['icon' => 'check_circle', 'color' => 'text-green-600',  'bg' => 'bg-green-50',  'border' => 'border-green-200',  'label' => 'Hoàn thành'],
                                'pending'                => ['icon' => 'schedule',     'color' => 'text-orange-600', 'bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'label' => 'Chờ xác nhận'],
                                'shipping'               => ['icon' => 'local_shipping','color' => 'text-blue-600',  'bg' => 'bg-blue-50',   'border' => 'border-blue-200',   'label' => 'Đang giao'],
                                'cancelled'              => ['icon' => 'cancel',        'color' => 'text-gray-500',  'bg' => 'bg-gray-100',  'border' => 'border-gray-200',   'label' => 'Đã hủy'],
                                default                  => ['icon' => 'info',          'color' => 'text-slate-500', 'bg' => 'bg-slate-50',  'border' => 'border-slate-200',  'label' => $order->status?->value ?? '—'],
                            };
                        @endphp

                        <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_8px_rgba(0,0,0,0.04)] overflow-hidden hover:shadow-[0_4px_16px_rgba(0,0,0,0.08)] transition-all duration-200">

                            {{-- Order Header --}}
                            <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 border-b border-gray-100">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <span class="font-bold text-sm text-gray-900">
                                        Mã đơn hàng: <span class="text-primary">#{{ $order->order_number }}</span>
                                    </span>
                                    <span class="w-px h-4 bg-gray-200 hidden sm:block"></span>
                                    <span class="text-xs text-gray-500">
                                        Ngày mua: {{ $order->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $statusConfig['bg'] }} {{ $statusConfig['color'] }} {{ $statusConfig['border'] }}">
                                    <span class="material-symbols-outlined text-[14px]">{{ $statusConfig['icon'] }}</span>
                                    {{ $statusConfig['label'] }}
                                </span>
                            </div>

                            {{-- Order Items --}}
                            <div class="px-6 py-4 space-y-4">
                                @foreach($order->items as $item)
                                    <div class="flex gap-4">
                                        <div class="w-16 h-20 flex-shrink-0 rounded-xl border border-gray-100 bg-gray-50 overflow-hidden flex items-center justify-center p-1">
                                            <img
                                                src="{{ $item->book?->cover_image_url ?? 'https://placehold.co/64x80/f3f4f6/9ca3af?text=No+Image' }}"
                                                alt="{{ $item->book_title_snapshot }}"
                                                class="w-full h-full object-contain">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 line-clamp-2">
                                                {{ $item->book_title_snapshot }}
                                            </h4>
                                            <p class="text-xs text-gray-500 mt-1">Số lượng: x{{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <span class="text-sm font-bold text-gray-800">
                                                {{ number_format($item->unit_price, 0, ',', '.') }} đ
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Order Footer --}}
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-6 py-4 bg-gray-50/60 border-t border-gray-100">
                                <p class="text-sm text-gray-600">
                                    Tổng tiền:
                                    <span class="text-lg font-black text-primary ml-1">
                                        {{ number_format($order->total, 0, ',', '.') }} đ
                                    </span>
                                </p>
                                <div class="flex gap-3">
                                    <a href="{{ route('account.orders.show', $order->id) }}"
                                        class="px-5 py-2 border-2 border-primary text-primary text-xs font-bold rounded-xl hover:bg-red-50 transition-colors uppercase tracking-wide">
                                        Xem chi tiết
                                    </a>
                                    @if(in_array($order->status?->value, ['completed', 'delivered']))
                                        <button
                                            class="px-5 py-2 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] uppercase tracking-wide">
                                            Mua lại
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-12 text-center">
                            <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-5">
                                <span class="material-symbols-outlined text-primary text-4xl">shopping_bag</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Chưa có đơn hàng nào</h3>
                            <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                                Hãy khám phá và đặt mua những tựa sách bạn yêu thích.
                            </p>
                            <a href="{{ route('books.search') }}"
                                class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-primary/90 hover:scale-105 transition-all shadow-[0_6px_20px_rgba(201,33,39,0.25)]">
                                <span class="material-symbols-outlined text-[18px]">explore</span>
                                Mua sắm ngay
                            </a>
                        </div>
                    @endforelse

                    @if($orders->hasPages())
                        <div class="mt-4 flex justify-center">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>

            </section>
        </div>
    </div>
</div>
@endsection
