@extends('layouts.app')

@section('title', 'Chợ Thu Cũ - THLD')

@section('content')

{{-- ── Hero Banner ── --}}
<div class="relative w-full overflow-hidden rounded-2xl mb-8" style="min-height: 340px;">
    <div class="absolute inset-0 bg-cover bg-center"
        style="background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=1400&q=80')">
        <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/50 to-transparent"></div>
    </div>

    <div class="relative z-10 flex flex-col justify-center h-full px-8 md:px-16 py-16">
        <div class="inline-flex items-center gap-2 bg-blue-500/20 border border-blue-400/40 text-blue-200 text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-4 w-fit">
            <span class="material-symbols-outlined text-[14px]">published_with_changes</span>
            Chợ Thu Cũ
        </div>
        <h1 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4 max-w-2xl"
            style="font-family: var(--font-heading, 'Lora', serif)">
            Chợ Thu Cũ:<br>
            <span class="text-blue-300">Give Stories a New Chapter</span>
        </h1>
        <p class="text-white/70 text-base md:text-lg max-w-xl mb-8 leading-relaxed">
            Mua bán sách đã qua sử dụng — tiết kiệm hơn, thân thiện với môi trường hơn. Mỗi cuốn sách đều được kiểm duyệt chất lượng bởi đội ngũ THLD.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('books.search') }}"
                class="inline-flex items-center gap-2 bg-primary text-white font-bold px-6 py-3 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_16px_rgba(201,33,39,0.4)]">
                <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                Mua sách cũ
            </a>
            <a href="#sell-section"
                class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/30 text-white font-bold px-6 py-3 rounded-xl hover:bg-white/20 transition-all">
                <span class="material-symbols-outlined text-[18px]">sell</span>
                Bán sách của bạn
            </a>
        </div>
    </div>
</div>

{{-- ── Stats Bar — data from SecondHandMarketService → Repository ── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @forelse($marketStats as $stat)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ $stat['bg'] }} flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined {{ $stat['color'] }} text-2xl">{{ $stat['icon'] }}</span>
            </div>
            <div>
                <p class="text-xl font-black text-gray-900">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-500 font-medium">{{ $stat['label'] }}</p>
            </div>
        </div>
    @empty
        <div class="col-span-4 text-center text-gray-400 text-sm py-4">Đang tải thống kê...</div>
    @endforelse
</div>

{{-- ── Main Content: Filter + Books ── --}}
<div class="flex flex-col lg:flex-row gap-6 mb-10">

    {{-- Sidebar Filter — categories from SecondHandMarketService → Repository ── --}}
    <aside class="w-full lg:w-56 flex-shrink-0">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 sticky top-24">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Lọc danh mục</p>

            <nav class="space-y-1">
                @forelse($filterCategories as $filter)
                    <button
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                            {{ $filter['active']
                                ? 'bg-primary text-white shadow-[0_4px_12px_rgba(201,33,39,0.25)]'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">{{ $filter['icon'] }}</span>
                            {{ $filter['label'] }}
                        </span>
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </button>
                @empty
                    <p class="text-xs text-gray-400 text-center py-2">Chưa có danh mục</p>
                @endforelse
            </nav>

            {{-- Quality Guarantee box --}}
            <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-blue-600 text-[18px]">verified_user</span>
                    <p class="text-xs font-bold text-blue-800">Đảm bảo chất lượng</p>
                </div>
                <p class="text-[11px] text-blue-600 leading-relaxed">
                    Mỗi cuốn sách được kiểm tra kỹ lưỡng về tình trạng vật lý và nội dung trước khi niêm yết.
                </p>
            </div>
        </div>
    </aside>

    {{-- Book Grid --}}
    <section class="flex-1 min-w-0">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Bộ sưu tập được tuyển chọn</h2>
                <p class="text-sm text-gray-500 mt-0.5">Được kiểm duyệt và xác nhận chất lượng</p>
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 hover:border-primary hover:text-primary transition-all">
                    Mới nhất
                </button>
                <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 hover:border-primary hover:text-primary transition-all">
                    Giá: Thấp → Cao
                </button>
            </div>
        </div>

        {{-- Books — condition_badge is a Model Accessor on Book --}}
        @forelse($featuredBooks as $book)
            @if($loop->first)
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            @endif

            <div class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-[0_8px_24px_rgba(0,0,0,0.10)] hover:border-primary/20 transition-all duration-300 overflow-hidden">

                {{-- Cover --}}
                <div class="relative aspect-[3/4] overflow-hidden bg-gray-50">
                    <a href="{{ route('books.show', $book->slug) }}">
                        <img src="{{ $book->cover_image_url }}"
                            alt="{{ $book->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy">
                    </a>
                    {{-- condition_badge is a Model Accessor --}}
                    <span class="absolute top-2 left-2 {{ $book->condition_badge['class'] }} text-white text-[9px] font-black px-2 py-0.5 rounded-md uppercase tracking-wide">
                        {{ $book->condition_badge['label'] }}
                    </span>
                    <button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-sm hover:bg-white transition-all">
                        <span class="material-symbols-outlined text-gray-400 hover:text-primary text-[16px]">favorite</span>
                    </button>
                </div>

                {{-- Info --}}
                <div class="p-3">
                    @if($book->category)
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">
                            {{ $book->category->name }}
                        </p>
                    @endif
                    <a href="{{ route('books.show', $book->slug) }}"
                        class="text-sm font-bold text-gray-900 line-clamp-2 leading-snug hover:text-primary transition-colors block mb-1">
                        {{ $book->title }}
                    </a>
                    @if($book->authors->isNotEmpty())
                        <p class="text-xs text-gray-500 mb-2">{{ $book->authors->first()->name }}</p>
                    @endif

                    {{-- formatted_current_price, has_discount, formatted_original_price are Model Accessors --}}
                    <div class="flex items-baseline gap-1.5 mb-2">
                        <span class="text-primary font-black text-base">{{ $book->formatted_current_price }}</span>
                        @if($book->has_discount)
                            <span class="text-gray-400 text-xs line-through">{{ $book->formatted_original_price }}</span>
                        @endif
                    </div>

                    <a href="{{ route('books.show', $book->slug) }}"
                        class="block w-full text-center text-xs font-bold text-primary border border-primary/30 hover:bg-primary hover:text-white py-2 rounded-xl transition-all">
                        Xem chi tiết
                    </a>
                </div>
            </div>

            @if($loop->last)
                </div>
            @endif

        @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-blue-500 text-3xl">published_with_changes</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Đang cập nhật kho sách</h3>
                <p class="text-gray-500 text-sm">Chúng tôi đang tuyển chọn những cuốn sách tốt nhất cho bạn.</p>
            </div>
        @endforelse

        @if($featuredBooks->isNotEmpty())
            <div class="mt-6 text-center">
                <a href="{{ route('books.search') }}"
                    class="inline-flex items-center gap-2 border-2 border-gray-200 text-gray-600 font-bold px-8 py-3 rounded-xl hover:border-primary hover:text-primary transition-all text-sm uppercase tracking-wide">
                    <span class="material-symbols-outlined text-[18px]">expand_more</span>
                    Xem thêm sách cũ
                </a>
            </div>
        @endif
    </section>
</div>

{{-- ── Sell Your Books Section ── --}}
<div id="sell-section" class="bg-gray-900 rounded-3xl overflow-hidden mb-8">
    <div class="flex flex-col md:flex-row items-center gap-0">
        <div class="flex-1 px-8 md:px-12 py-10 md:py-14">
            <h2 class="text-3xl md:text-4xl font-black text-white leading-tight mb-4"
                style="font-family: var(--font-heading, 'Lora', serif)">
                Empty your shelves,<br>
                <span class="text-blue-400">fill your soul.</span>
            </h2>
            <p class="text-gray-400 text-sm leading-relaxed mb-6 max-w-md">
                Chương trình "Chợ Thu Cũ" cho phép bạn đổi những cuốn sách đã đọc lấy tín dụng cửa hàng hoặc tiền mặt. Hãy giúp những câu chuyện tiếp tục sống.
            </p>
            <ul class="space-y-3 mb-8">
                @foreach([
                    'Định giá tức thì cho các đầu sách phổ biến',
                    'Miễn phí lấy hàng tận nơi cho đơn từ 10 cuốn',
                    'Cộng thêm 15% giá trị nếu nhận tín dụng cửa hàng',
                ] as $benefit)
                    <li class="flex items-center gap-3 text-sm text-gray-300">
                        <span class="w-5 h-5 rounded-full bg-blue-500/20 border border-blue-500/40 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-blue-400 text-[12px]">check</span>
                        </span>
                        {{ $benefit }}
                    </li>
                @endforeach
            </ul>
            <a href="{{ route('pages.contact') }}"
                class="inline-flex items-center gap-2 bg-primary text-white font-bold px-7 py-3.5 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_16px_rgba(201,33,39,0.4)]">
                <span class="material-symbols-outlined text-[18px]">sell</span>
                Bán sách của bạn
            </a>
        </div>

        <div class="w-full md:w-80 lg:w-96 h-64 md:h-auto flex-shrink-0">
            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=600&q=80"
                alt="Stack of books"
                class="w-full h-full object-cover opacity-70">
        </div>
    </div>
</div>

{{-- ── Coming Soon Banner ── --}}
<div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute top-4 left-8 w-32 h-32 rounded-full bg-white"></div>
        <div class="absolute bottom-4 right-8 w-24 h-24 rounded-full bg-white"></div>
    </div>
    <div class="relative z-10">
        <span class="inline-flex items-center gap-2 bg-white/20 text-white text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-4">
            <span class="material-symbols-outlined text-[14px]">schedule</span>
            Sắp ra mắt đầy đủ
        </span>
        <h3 class="text-2xl font-black text-white mb-2">Nền tảng giao dịch P2P đang được phát triển</h3>
        <p class="text-blue-200 text-sm max-w-lg mx-auto mb-6">
            Chúng tôi đang xây dựng nền tảng cho phép người dùng tự đăng bán sách cũ trực tiếp với nhau. Đăng ký để nhận thông báo sớm nhất.
        </p>
        <form class="flex gap-2 max-w-sm mx-auto" onsubmit="return false">
            <input type="email" placeholder="Email của bạn..."
                class="flex-1 px-4 py-2.5 rounded-xl text-sm outline-none bg-white/10 border border-white/30 text-white placeholder-blue-200 focus:bg-white/20 transition-all">
            <button type="submit"
                class="px-5 py-2.5 bg-white text-blue-700 font-bold text-sm rounded-xl hover:bg-blue-50 transition-all">
                Đăng ký
            </button>
        </form>
    </div>
</div>

@endsection
