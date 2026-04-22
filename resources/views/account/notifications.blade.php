@extends('layouts.app')

@section('title', 'Thông báo - THLD')

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

                    {{-- Notifications – active --}}
                    <div>
                        <a href="{{ route('account.notifications') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold bg-primary text-white shadow-[0_6px_20px_rgba(201,33,39,0.25)] transition-all duration-200">
                            <span class="material-symbols-outlined text-xl">notifications</span>
                            <span class="flex-1">Thông báo</span>
                            @if($unreadCount > 0)
                                <span class="bg-white text-primary text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center" data-unread-badge>
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                        <div class="ml-4 mt-1 space-y-0.5">
    <a href="{{ route('account.notifications') }}?type=order"
        class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
        Cập nhật đơn hàng
    </a>

    <a href="{{ route('account.notifications') }}?type=promotion"
        class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
        Khuyến mãi
    </a>

    <a href="{{ route('account.notifications') }}?type=system"
        class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
        Bảo mật
    </a>
                    </div>
                    </div>

                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">location_on</span>
                        <span>Sổ địa chỉ</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">confirmation_number</span>
                        <span>Kho Voucher</span>
                    </a>
                    <div class="pt-4 mt-4 border-t border-gray-100">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-notif" class="hidden">@csrf</form>
                        <a href="javascript:void(0)"
                            onclick="document.getElementById('logout-form-notif').submit();"
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
                    <span class="text-primary font-bold">Thông báo</span>
                </nav>

                {{-- Page Header --}}
                <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] px-7 py-5 mb-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Thông báo</h1>
                            <p class="text-sm text-gray-500 mt-0.5">
                                @if($unreadCount > 0)
                                    Bạn có <span class="font-semibold text-primary">{{ $unreadCount }}</span> thông báo chưa đọc.
                                @else
                                    Tất cả thông báo đã được đọc.
                                @endif
                            </p>
                        </div>
                        @if($unreadCount > 0)
                            <form action="{{ route('account.notifications.read-all') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-2 text-sm font-semibold text-primary hover:text-primary/80 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">done_all</span>
                                    Đánh dấu tất cả đã đọc
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Filter tabs --}}
                    @php $activeType = request('type', 'all'); @endphp
                    <div class="flex gap-1 mt-4 bg-gray-100 p-1 rounded-xl w-fit">
                        <a href="{{ route('account.notifications') }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $activeType === 'all' ? 'bg-white text-primary font-semibold shadow-sm' : 'text-gray-500 hover:text-primary' }}">
                            Tất cả
                        </a>
                        <a href="{{ route('account.notifications') }}?type=order"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $activeType === 'order' ? 'bg-white text-primary font-semibold shadow-sm' : 'text-gray-500 hover:text-primary' }}">
                            Đơn hàng
                        </a>
                        <a href="{{ route('account.notifications') }}?type=promotion"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $activeType === 'promotion' ? 'bg-white text-primary font-semibold shadow-sm' : 'text-gray-500 hover:text-primary' }}">
                            Khuyến mãi
                        </a>
                        <a href="{{ route('account.notifications') }}?type=system"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $activeType === 'system' ? 'bg-white text-primary font-semibold shadow-sm' : 'text-gray-500 hover:text-primary' }}">
                            Hệ thống
                        </a>
                    </div>
                </div>

                {{-- Notification List --}}
                {{-- type_config and time_ago come from Model Accessors on Notification --}}
                @forelse($notifications as $notif)
                    @php $config = $notif->type_config; @endphp
                    <div class="group relative bg-white rounded-[18px] border transition-all duration-200 overflow-hidden mb-3
                        {{ !$notif->is_read
                            ? 'border-primary/20 shadow-[0_2px_12px_rgba(201,33,39,0.08)]'
                            : 'border-gray-200 shadow-[0_2px_8px_rgba(0,0,0,0.04)] opacity-80' }}"
                        id="notif-{{ $notif->id }}">

                        @if(!$notif->is_read)
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-l-[18px]"></div>
                        @endif

                        <div class="flex items-start gap-4 px-6 py-5 {{ !$notif->is_read ? 'pl-7' : '' }}">
                            <div class="w-11 h-11 rounded-xl {{ $config['bg'] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-xl {{ $config['color'] }}">{{ $config['icon'] }}</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-[10px] font-bold uppercase tracking-wider {{ $config['color'] }}">
                                                {{ $config['label'] }}
                                            </span>
                                            @if(!$notif->is_read)
                                                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                            @endif
                                        </div>
                                        <h3 class="text-sm font-bold leading-snug {{ $notif->is_read ? 'text-gray-600' : 'text-gray-900' }}">
                                            {{ $notif->title }}
                                        </h3>
                                        @if($notif->content)
                                            <p class="text-xs text-gray-500 mt-1 leading-relaxed line-clamp-2">
                                                {{ $notif->content }}
                                            </p>
                                        @endif
                                        @if($notif->url)
                                            <div class="mt-3">
                                                <a href="{{ $notif->url }}"
                                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:text-primary/80 transition-colors border-b border-primary/30 hover:border-primary pb-0.5">
                                                    Xem chi tiết
                                                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                        {{-- time_ago is a Model Accessor --}}
                                        <span class="text-[11px] text-gray-400 font-medium whitespace-nowrap">
                                            {{ $notif->time_ago }}
                                        </span>
                                        @if(!$notif->is_read)
                                            <button
                                                onclick="markRead({{ $notif->id }}, this)"
                                                class="text-[10px] text-gray-400 hover:text-primary font-medium transition-colors opacity-0 group-hover:opacity-100"
                                                title="Đánh dấu đã đọc">
                                                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-12 text-center">
                        <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <span class="material-symbols-outlined text-primary text-4xl">notifications_off</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Chưa có thông báo nào</h3>
                        <p class="text-gray-500 text-sm max-w-sm mx-auto">
                            Các thông báo về đơn hàng, khuyến mãi và bảo mật sẽ xuất hiện tại đây.
                        </p>
                    </div>
                @endforelse

                @if($notifications->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $notifications->links() }}
                    </div>
                @endif

            </section>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function markRead(notifId, btn) {
        fetch(`/account/notifications/${notifId}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;

            const card = document.getElementById(`notif-${notifId}`);
            if (!card) return;

            card.classList.remove('border-primary/20', 'shadow-[0_2px_12px_rgba(201,33,39,0.08)]');
            card.classList.add('border-gray-200', 'shadow-[0_2px_8px_rgba(0,0,0,0.04)]', 'opacity-80');

            const bar = card.querySelector('.bg-primary.rounded-l-\\[18px\\]');
            if (bar) bar.remove();

            btn.closest('div').remove();

            const badge = document.querySelector('[data-unread-badge]');
            if (badge) {
                const next = Math.max(0, (parseInt(badge.textContent) || 0) - 1);
                badge.textContent = next > 9 ? '9+' : next;
                if (next === 0) badge.style.display = 'none';
            }
        })
        .catch(console.error);
    }
</script>
@endpush
@endsection
