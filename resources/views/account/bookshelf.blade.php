@extends('layouts.app')

@section('title', 'Tủ sách cá nhân - THLD')

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
                    <a href="{{ route('account.bookshelf') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold bg-primary text-white shadow-[0_6px_20px_rgba(201,33,39,0.25)] transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">auto_stories</span>
                        <span>Tủ sách cá nhân</span>
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
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-bookshelf" class="hidden">@csrf</form>
                        <a href="javascript:void(0)"
                            onclick="document.getElementById('logout-form-bookshelf').submit();"
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
                    <span class="text-primary font-bold">Tủ sách cá nhân</span>
                </nav>

                {{-- Page Header --}}
                <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] px-7 py-5 mb-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Tủ sách cá nhân</h1>
                            <p class="text-sm text-gray-500 mt-0.5">
                                Bạn đang có <span class="font-semibold text-primary">{{ $readingLists->total() }}</span> cuốn sách trong danh sách đọc.
                            </p>
                        </div>
                        <a href="{{ route('books.search') }}"
                            class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)]">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            Thêm sách mới
                        </a>
                    </div>

                    {{-- Reading status tabs --}}
                    <div class="flex gap-1 mt-4 bg-gray-100 p-1 rounded-xl w-fit">
                        <button class="px-4 py-2 rounded-lg text-sm font-semibold bg-white text-primary shadow-sm transition-all">
                            Tất cả
                        </button>
                        <button class="px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:text-primary transition-all">
                            Đang đọc
                        </button>
                        <button class="px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:text-primary transition-all">
                            Muốn đọc
                        </button>
                        <button class="px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:text-primary transition-all">
                            Đã đọc
                        </button>
                    </div>
                </div>

                {{-- Book Grid --}}
                @if($readingLists->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach($readingLists as $item)
                            @php $book = $item->book; @endphp
                            @if(!$book) @continue @endif

                            <div class="group bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_24px_rgba(201,33,39,0.10)] hover:border-primary/20 transition-all duration-300 p-4 flex gap-4">
                                {{-- Cover --}}
                                <div class="w-20 h-28 flex-shrink-0 rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                                    <img src="{{ $book->cover_image_url }}"
                                        alt="{{ $book->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        loading="lazy">
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        @if($book->category)
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                {{ $book->category->name }}
                                            </p>
                                        @endif
                                        <a href="{{ route('books.show', $book->slug) }}"
                                            class="text-sm font-bold text-gray-900 line-clamp-2 leading-snug hover:text-primary transition-colors">
                                            {{ $book->title }}
                                        </a>
                                        @if($book->authors->isNotEmpty())
                                            <p class="text-xs text-gray-500 mt-1">{{ $book->authors->pluck('name')->join(', ') }}</p>
                                        @endif
                                    </div>

                                    {{-- Progress bar --}}
                                    @if($item->current_page && $item->total_pages)
                                        @php $pct = min(100, round($item->current_page / $item->total_pages * 100)); @endphp
                                        <div class="mt-3">
                                            <div class="flex justify-between text-[10px] font-bold text-gray-500 mb-1">
                                                <span>{{ $pct }}%</span>
                                                <span>{{ $item->current_page }}/{{ $item->total_pages }} trang</span>
                                            </div>
                                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-primary rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                            </div>
                                        </div>
                                    @endif

                                    <button class="mt-3 w-full py-2 bg-primary/10 text-primary text-xs font-bold rounded-xl hover:bg-primary hover:text-white transition-all">
                                        Cập nhật tiến độ
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($readingLists->hasPages())
                        <div class="mt-6 flex justify-center">
                            {{ $readingLists->links() }}
                        </div>
                    @endif

                @else
                    <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-12 text-center">
                        <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <span class="material-symbols-outlined text-primary text-4xl">auto_stories</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tủ sách đang trống</h3>
                        <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                            Thêm sách vào tủ để theo dõi tiến độ đọc của bạn.
                        </p>
                        <a href="{{ route('books.search') }}"
                            class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-primary/90 hover:scale-105 transition-all shadow-[0_6px_20px_rgba(201,33,39,0.25)]">
                            <span class="material-symbols-outlined text-[18px]">explore</span>
                            Khám phá sách ngay
                        </a>
                    </div>
                @endif

            </section>
        </div>
    </div>
</div>
@endsection
