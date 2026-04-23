@extends('layouts.app')

@section('title', 'Danh sách yêu thích - THLD')

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
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold bg-primary text-white shadow-[0_6px_20px_rgba(201,33,39,0.25)] transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">favorite</span>
                        <span>Sách yêu thích</span>
                    </a>
                    <a href="{{ route('account.notifications') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                        <span>Thông báo</span>
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
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-wishlist" class="hidden">@csrf</form>
                        <a href="javascript:void(0)"
                            onclick="document.getElementById('logout-form-wishlist').submit();"
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
                    <span class="text-primary font-bold">Danh sách yêu thích</span>
                </nav>

                {{-- Page Header --}}
                <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] px-7 py-5 mb-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Danh sách yêu thích</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Quản lý những tác phẩm bạn đã lưu để theo dõi và mua sau.</p>
                        </div>
                        <div class="flex bg-gray-100 p-1 rounded-xl gap-1 self-start sm:self-auto">
                            <button id="tab-all"
                                onclick="filterWishlist('all')"
                                class="wishlist-tab px-4 py-2 rounded-lg text-sm font-semibold transition-all bg-white text-primary shadow-sm">
                                Tất cả
                                <span class="ml-1 text-xs">({{ $books->total() }})</span>
                            </button>
                            <button id="tab-sale"
                                onclick="filterWishlist('sale')"
                                class="wishlist-tab px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:text-primary transition-all">
                                Đang giảm giá
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Book Grid --}}
                @if($books->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4" id="wishlist-grid">
                        @foreach($books as $item)
                            {{-- $book->has_discount, $book->is_out_of_stock, etc. come from Model Accessors --}}
                            @php $book = $item->book; @endphp
                            @if(!$book) @continue @endif

                            <div class="group bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_24px_rgba(201,33,39,0.12)] hover:border-primary/20 transition-all duration-300 overflow-hidden wishlist-card {{ $book->has_discount ? 'has-sale' : '' }}"
                                data-book-id="{{ $book->id }}">

                                {{-- Book Cover --}}
                                <div class="relative w-full aspect-[3/4] overflow-hidden bg-gray-50">
                                    <a href="{{ route('books.show', $book->slug) }}">
                                        <img src="{{ $book->cover_image_url }}"
                                            alt="{{ $book->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                            loading="lazy">
                                    </a>

                                    @if($book->is_out_of_stock)
                                        <span class="absolute top-3 left-3 bg-gray-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wide">
                                            Hết hàng
                                        </span>
                                    @elseif($book->has_discount)
                                        <span class="absolute top-3 left-3 bg-primary text-white text-[10px] font-bold px-2.5 py-1 rounded-lg">
                                            -{{ $book->discount_percentage }}%
                                        </span>
                                    @endif

                                    <button
                                        onclick="removeFromWishlist({{ $book->id }}, this)"
                                        class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm text-gray-400 hover:text-primary hover:bg-white flex items-center justify-center shadow-sm transition-all duration-200 hover:scale-110"
                                        title="Xóa khỏi yêu thích">
                                        <span class="material-symbols-outlined text-[18px]">close</span>
                                    </button>
                                </div>

                                {{-- Book Info --}}
                                <div class="p-4 flex flex-col gap-1.5">
                                    @if($book->category)
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                            {{ $book->category->name }}
                                        </p>
                                    @endif

                                    <a href="{{ route('books.show', $book->slug) }}"
                                        class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 hover:text-primary transition-colors">
                                        {{ $book->title }}
                                    </a>

                                    @if($book->authors->isNotEmpty())
                                        <p class="text-xs text-gray-500">{{ $book->authors->pluck('name')->join(', ') }}</p>
                                    @endif

                                    {{-- Prices come from Model Accessors: formatted_current_price, formatted_original_price --}}
                                    <div class="flex items-baseline gap-2 mt-1">
                                        <span class="text-primary text-lg font-bold">{{ $book->formatted_current_price }}</span>
                                        @if($book->has_discount)
                                            <span class="text-gray-400 text-xs line-through">{{ $book->formatted_original_price }}</span>
                                        @endif
                                    </div>

                                    @if($book->is_out_of_stock)
                                        <button disabled
                                            class="mt-2 w-full flex items-center justify-center gap-2 bg-gray-100 text-gray-400 cursor-not-allowed py-2.5 rounded-xl font-bold text-xs">
                                            <span class="material-symbols-outlined text-[16px]">notifications</span>
                                            Nhận thông báo
                                        </button>
                                    @else
                                        <button
                                            onclick="addToCart({{ $book->id }}, this)"
                                            class="mt-2 w-full flex items-center justify-center gap-2 bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all py-2.5 rounded-xl font-bold text-xs">
                                            <span class="material-symbols-outlined text-[16px]">shopping_cart</span>
                                            Thêm vào giỏ hàng
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($books->hasPages())
                        <div class="mt-6 flex justify-center">
                            {{ $books->links() }}
                        </div>
                    @endif

                @else
                    <div class="bg-white rounded-[18px] border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-12 text-center">
                        <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <span class="material-symbols-outlined text-primary text-4xl">favorite</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Danh sách yêu thích trống</h3>
                        <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                            Hãy khám phá và lưu những tựa sách bạn yêu thích để dễ dàng mua sau.
                        </p>
                        <a href="{{ route('books.search') }}"
                            class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-primary/90 hover:scale-105 transition-all shadow-[0_6px_20px_rgba(201,33,39,0.25)]">
                            <span class="material-symbols-outlined text-[18px]">explore</span>
                            Khám phá sách ngay
                        </a>
                    </div>
                @endif

                @if($books->count() > 0)
                    <div class="mt-6 bg-white rounded-[18px] border border-dashed border-primary/30 p-8 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex items-center gap-1 text-primary/60">
                                <span class="material-symbols-outlined text-2xl">auto_awesome</span>
                                <span class="material-symbols-outlined text-base">add</span>
                            </div>
                            <p class="text-gray-500 text-sm italic max-w-md">
                                "A room without books is like a body without a soul."<br>
                                <span class="font-semibold text-gray-700 not-italic">— Marcus Tullius Cicero</span>
                            </p>
                            <a href="{{ route('books.search') }}"
                                class="mt-2 inline-flex items-center gap-2 border-2 border-primary text-primary px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-primary hover:text-white transition-all duration-200 uppercase tracking-wide">
                                Tiếp tục khám phá
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @endif

            </section>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function filterWishlist(type) {
        const cards = document.querySelectorAll('.wishlist-card');
        const tabAll  = document.getElementById('tab-all');
        const tabSale = document.getElementById('tab-sale');

        [tabAll, tabSale].forEach(t => {
            t.classList.remove('bg-white', 'text-primary', 'shadow-sm');
            t.classList.add('text-gray-500');
        });
        const active = type === 'all' ? tabAll : tabSale;
        active.classList.add('bg-white', 'text-primary', 'shadow-sm');
        active.classList.remove('text-gray-500');

        cards.forEach(card => {
            const show = type === 'all' || (type === 'sale' && card.classList.contains('has-sale'));
            card.style.display = show ? '' : 'none';
        });
    }

    function removeFromWishlist(bookId, btn) {
        const card = btn.closest('.wishlist-card');
        card.style.transition = 'all 0.3s ease';
        card.style.opacity    = '0';
        card.style.transform  = 'scale(0.9)';

        fetch(`/account/wishlist/${bookId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success !== false) {
                setTimeout(() => card.remove(), 300);
            } else {
                card.style.opacity = '1';
                card.style.transform = '';
            }
        })
        .catch(() => { card.style.opacity = '1'; card.style.transform = ''; });
    }

    function addToCart(bookId, btn) {
        const original = btn.innerHTML;
        btn.disabled  = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span> Đang thêm...';

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ book_id: bookId, quantity: 1 })
        })
        .then(r => r.json())
        .then(() => {
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px]">check_circle</span> Đã thêm!';
            btn.classList.replace('bg-primary/10', 'bg-green-100');
            btn.classList.replace('text-primary', 'text-green-700');
            setTimeout(() => {
                btn.innerHTML = original;
                btn.disabled  = false;
                btn.classList.replace('bg-green-100', 'bg-primary/10');
                btn.classList.replace('text-green-700', 'text-primary');
            }, 2000);
        })
        .catch(() => { btn.innerHTML = original; btn.disabled = false; });
    }
</script>
@endpush
@endsection
