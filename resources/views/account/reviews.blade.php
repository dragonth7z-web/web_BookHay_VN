@extends('layouts.app')

@section('title', 'Đánh giá của tôi - THLD')

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
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold bg-primary text-white shadow-[0_6px_20px_rgba(201,33,39,0.25)] transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">star</span>
                        <span>Đánh giá của tôi</span>
                    </a>
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
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-reviews" class="hidden">@csrf</form>
                        <a href="javascript:void(0)"
                            onclick="document.getElementById('logout-form-reviews').submit();"
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
                    <span class="text-primary font-bold">Đánh giá của tôi</span>
                </nav>

                {{-- Page Header --}}
                <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] px-7 py-5 mb-5">
                    <h1 class="text-2xl font-bold text-gray-900">Đánh giá của tôi</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Quản lý và xem lại tất cả các nhận xét sản phẩm bạn đã mua.</p>

                    {{-- Tabs --}}
                    <div class="flex gap-1 mt-4 border-b border-gray-100">
                        <button class="px-5 py-3 text-sm font-bold text-primary border-b-2 border-primary -mb-px">
                            Đã đánh giá ({{ $reviews->total() }})
                        </button>
                        <button class="px-5 py-3 text-sm font-medium text-gray-500 hover:text-primary transition-colors">
                            Chờ đánh giá
                        </button>
                    </div>
                </div>

                {{-- Review List --}}
                <div class="space-y-4">
                    @forelse($reviews as $review)
                        <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_8px_rgba(0,0,0,0.04)] p-5 hover:shadow-[0_4px_16px_rgba(0,0,0,0.08)] transition-all duration-200">
                            <div class="flex flex-col sm:flex-row gap-5">
                                {{-- Book cover --}}
                                <div class="w-20 h-28 flex-shrink-0 rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                                    <img src="{{ $review->book?->cover_image_url ?? 'https://placehold.co/80x112/f3f4f6/9ca3af?text=No+Image' }}"
                                        alt="{{ $review->book?->title }}"
                                        class="w-full h-full object-cover">
                                </div>

                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex flex-wrap justify-between items-start gap-2">
                                            <div>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">
                                                    Ngày đánh giá: {{ $review->created_at?->format('d/m/Y') }}
                                                </p>
                                                <h3 class="text-sm font-bold text-gray-900">
                                                    {{ $review->book?->title ?? '—' }}
                                                </h3>
                                            </div>
                                            {{-- Star rating --}}
                                            <div class="flex items-center gap-0.5 text-primary">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="material-symbols-outlined text-sm {{ $i <= ($review->rating ?? 0) ? 'font-fill-1' : '' }}">star</span>
                                                @endfor
                                            </div>
                                        </div>
                                        @if($review->comment)
                                            <p class="mt-2 text-sm text-gray-600 line-clamp-2 italic">
                                                "{{ $review->comment }}"
                                            </p>
                                        @endif
                                    </div>

                                    <div class="mt-4 flex justify-end gap-2">
                                        <button class="flex items-center gap-1.5 px-4 py-2 border border-gray-200 text-gray-600 text-xs font-bold rounded-xl hover:bg-gray-50 transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                            Xóa
                                        </button>
                                        <button class="flex items-center gap-1.5 px-4 py-2 bg-primary/10 text-primary text-xs font-bold rounded-xl hover:bg-primary/20 transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">edit</span>
                                            Chỉnh sửa
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-12 text-center">
                            <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-5">
                                <span class="material-symbols-outlined text-primary text-4xl">rate_review</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Chưa có đánh giá nào</h3>
                            <p class="text-gray-500 text-sm max-w-sm mx-auto">
                                Hãy mua và đọc sách, sau đó chia sẻ cảm nhận của bạn với cộng đồng.
                            </p>
                        </div>
                    @endforelse

                    @if($reviews->hasPages())
                        <div class="mt-4 flex justify-center">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>

            </section>
        </div>
    </div>
</div>
@endsection
