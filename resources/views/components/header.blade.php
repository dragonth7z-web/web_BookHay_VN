<header id="main-header"
    class="bg-white/90 backdrop-blur-2xl border-b-2 border-brand-primary/10 sticky top-0 z-50 transition-all duration-300 shadow-lg shadow-black/5 [&.header-hidden]:-translate-y-full">
    <div class="max-w-main mx-auto px-2 md:px-4 py-3 flex items-center justify-between gap-4 md:gap-8 relative">

        {{-- Mobile: Hamburger + Logo --}}
        <div class="flex items-center gap-3">
            {{-- Hamburger – only on mobile --}}
            <button
                class="lg:hidden flex items-center justify-center w-10 h-10 rounded-lg bg-gray-50 text-gray-700 hover:bg-brand-primary-50 hover:text-brand-primary transition-all shadow-sm"
                onclick="openMobileNav()" aria-label="Mở menu">
                <span class="material-symbols-outlined text-xl">menu</span>
            </button>

            <a class="flex-shrink-0" href="{{ route('home') ?? '#' }}">
                <img src="{{ asset('images/logos/Anh_dai_dien_Fanpage_Avatar_he_thong.png') }}" alt="THLD"
                    class="h-16 md:h-16 w-auto object-contain hover:scale-105 transition-transform duration-300"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                <div class="text-xl md:text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-[#f43f5e] tracking-tighter hover:scale-105 transition-transform duration-300"
                    style="font-family: var(--font-heading, 'Lora', serif); display:none">THLD</div>
            </a>

            {{-- Category dropdown – hidden on mobile --}}
            <div class="relative py-2 hidden lg:block group/dropdown dropdown-wrapper">
                <button
                    class="flex items-center gap-2 text-gray-700 hover:text-brand-primary transition-all font-bold text-sm bg-gray-50 hover:bg-brand-primary-50 px-4 py-2 rounded-[4px] border border-gray-200/80 dropdown-trigger"
                    data-target="mega-menu">
                    <span class="material-symbols-outlined text-xl">menu_book</span>
                    <span class="hidden md:block">DANH MỤC</span>
                </button>

                {{-- ── MEGA MENU: 2-column layout ── --}}
                <div id="mega-menu"
                    class="absolute top-full left-0 bg-white border border-gray-100 shadow-2xl z-[100] rounded-[10px] -mt-1 transition-all duration-300 opacity-0 invisible translate-y-2 dropdown-menu [&.dropdown-active]:opacity-100 [&.dropdown-active]:visible [&.dropdown-active]:translate-y-0 [&.dropdown-active]:scale-100 overflow-hidden"
                    style="min-width:800px; width:max-content; max-width:calc(100vw - 40px)">

                    <div class="flex" style="min-height:420px">

                        {{-- Left: category list — tự giãn theo tên dài nhất, không fix cứng width --}}
                        <div class="shrink-0 bg-gray-50 border-r border-gray-100 py-2 overflow-y-auto" style="width:max-content; min-width:260px; max-width:360px">
                            @foreach($megaCategories as $i => $cat)
                                <button type="button"
                                    class="mega-cat-btn w-full flex items-center gap-3 px-4 py-2.5 text-left transition-all text-sm font-semibold text-gray-700 hover:bg-white hover:text-brand-primary whitespace-nowrap"
                                    data-index="{{ $i }}">
                                    <span class="material-symbols-outlined text-[18px] text-gray-400 shrink-0">{{ $cat->icon ?: 'menu_book' }}</span>
                                    <span class="flex-1 text-left">{{ $cat->name }}</span>
                                    @if($cat->badge_text)
                                        <span class="text-[9px] font-black bg-rose-500 text-white px-1.5 py-0.5 rounded-full uppercase shrink-0">{{ $cat->badge_text }}</span>
                                    @elseif($cat->children->isNotEmpty())
                                        <span class="material-symbols-outlined text-[14px] text-gray-300 shrink-0">chevron_right</span>
                                    @endif
                                </button>
                            @endforeach

                            <a href="{{ route('books.search') }}"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-brand-primary hover:bg-white transition-colors border-t border-gray-100 mt-1 whitespace-nowrap">
                                <span class="material-symbols-outlined text-[18px] shrink-0">apps</span>
                                Xem tất cả danh mục
                            </a>
                        </div>

                        {{-- Right: subcategory panels — luôn đủ rộng --}}
                        <div class="relative overflow-hidden" style="min-width:520px; flex:1">
                            @foreach($megaCategories as $i => $cat)
                                <div class="mega-sub-panel absolute inset-0 p-6 opacity-0 pointer-events-none transition-opacity duration-150 overflow-y-auto" data-index="{{ $i }}">

                                    {{-- Panel header --}}
                                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100">
                                        <span class="material-symbols-outlined text-[20px] text-brand-primary">{{ $cat->icon ?: 'menu_book' }}</span>
                                        <span class="text-sm font-black text-gray-800 uppercase tracking-wide whitespace-nowrap">{{ $cat->name }}</span>
                                    </div>

                                    @if($cat->children->isNotEmpty())
                                        {{-- Dùng flex-wrap để mỗi item tự co theo độ dài tên, không bao giờ ngắt giữa từ --}}
                                        <div class="flex flex-wrap gap-x-2 gap-y-1">
                                            @foreach($cat->children as $child)
                                                <a href="{{ route('books.search', ['category' => $child->id]) }}"
                                                    class="inline-flex items-center gap-2 py-2 px-3 rounded-[4px] text-sm text-gray-600 hover:text-brand-primary hover:bg-red-50 transition-colors font-medium whitespace-nowrap">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 shrink-0"></span>
                                                    {{ $child->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                        <a href="{{ route('books.search', ['category' => $cat->id]) }}"
                                            class="inline-flex items-center gap-1 mt-5 text-xs font-bold text-brand-primary hover:underline">
                                            Xem tất cả {{ $cat->name }}
                                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                        </a>
                                    @else
                                        <div class="flex flex-col items-center justify-center h-48 text-gray-300">
                                            <span class="material-symbols-outlined text-5xl mb-3">{{ $cat->icon ?: 'menu_book' }}</span>
                                            <p class="text-sm font-medium text-gray-400">Khám phá {{ $cat->name }}</p>
                                            <a href="{{ route('books.search', ['category' => $cat->id]) }}"
                                                class="mt-3 text-xs font-bold text-brand-primary hover:underline">Xem sách →</a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Search bar – Pill shape --}}
        <div class="flex-grow max-w-2xl relative z-[1001]" id="search-wrapper">
            <div class="relative flex items-center w-full group/search">
                <input
                    class="w-full pl-6 pr-14 py-3.5 rounded-xl border border-gray-200/80 bg-gray-50/50 hover:bg-white focus:bg-white focus:outline-none focus:ring-8 focus:ring-brand-primary/5 focus:border-brand-primary/30 text-sm shadow-sm hover:shadow-md transition-all duration-500 search-input-main"
                    placeholder="Tìm kiếm tác phẩm, tác giả hoặc thể loại..." type="text" id="search-input"
                    autocomplete="off">
                <button id="search-submit-btn"
                    class="absolute right-2 w-10 h-10 bg-brand-primary text-white rounded-[4px] hover:bg-brand-primary-dark hover:scale-105 hover:shadow-brand transition-all flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">search</span>
                </button>
            </div>

            {{-- Search Dropdown Panel --}}
            <div id="search-dropdown"
                class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-[1002] hidden"
                style="max-height: 520px; overflow-y: auto;">

                {{-- Flash Sale Banner --}}
                <div id="sd-flash-banner" class="hidden">
                    <div class="mx-4 mt-4 mb-2 px-5 py-3 rounded-xl bg-gradient-to-r from-brand-primary to-rose-500 text-white font-bold text-sm cursor-pointer hover:opacity-90 transition-opacity flex items-center gap-2"
                        onclick="window.location.href='{{ route('flash-sale.index') }}'">
                        <span class="material-symbols-outlined text-lg">bolt</span>
                        <span id="sd-flash-name">Sale Giữa Tháng - Deal Bao La</span>
                    </div>
                </div>

                {{-- Hot Keywords --}}
                <div id="sd-keywords-section" class="px-4 pt-4 pb-2">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-black text-gray-800 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-brand-primary">trending_up</span>
                            Từ khóa hot
                        </h4>
                        <button onclick="window.__searchDropdown?.refreshDefault()" class="text-gray-400 hover:text-gray-600 transition-colors" title="Làm mới">
                            <span class="material-symbols-outlined text-[18px]">refresh</span>
                        </button>
                    </div>
                    <div id="sd-keywords-grid" class="grid grid-cols-3 gap-2">
                        {{-- Injected by JS --}}
                    </div>
                </div>

                <div class="border-t border-gray-100 mx-4 my-2"></div>

                {{-- Featured Categories --}}
                <div id="sd-categories-section" class="px-4 pb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-black text-gray-800 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-brand-primary">grid_view</span>
                            Danh mục nổi bật
                        </h4>
                        <button onclick="window.__searchDropdown?.refreshDefault()" class="text-gray-400 hover:text-gray-600 transition-colors" title="Làm mới">
                            <span class="material-symbols-outlined text-[18px]">refresh</span>
                        </button>
                    </div>
                    <div id="sd-categories-grid" class="grid grid-cols-4 gap-3">
                        {{-- Injected by JS --}}
                    </div>

                    {{-- Nút mã giảm giá --}}
                    <a href="{{ session('user_id') ? route('account.coupons') : route('pages.coupons') }}"
                        class="mt-4 flex items-center justify-center gap-2 w-full py-2.5 rounded-xl border border-dashed border-brand-primary/40 text-brand-primary hover:bg-brand-primary/5 transition-colors text-sm font-bold">
                        <span class="material-symbols-outlined text-[18px]">confirmation_number</span>
                        Xem tất cả mã giảm giá
                    </a>
                </div>

                {{-- Live Search Results --}}
                <div id="sd-results-section" class="hidden px-4 py-3">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Kết quả tìm kiếm</p>
                    <div id="sd-results-list" class="space-y-1"></div>
                    <a id="sd-view-all-link" href="{{ route('books.search') }}"
                        class="mt-3 flex items-center justify-center gap-1 text-xs font-bold text-brand-primary hover:underline py-2">
                        Xem tất cả kết quả
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>

                {{-- Loading --}}
                <div id="sd-loading" class="hidden px-4 py-6 text-center">
                    <div class="inline-block w-5 h-5 border-2 border-brand-primary border-t-transparent rounded-full animate-spin"></div>
                </div>

                {{-- No results --}}
                <div id="sd-empty" class="hidden px-4 py-6 text-center">
                    <span class="material-symbols-outlined text-3xl text-gray-300 block mb-2">search_off</span>
                    <p class="text-sm text-gray-400 font-medium">Không tìm thấy kết quả</p>
                </div>
            </div>
        </div>

        {{-- Right icons --}}
        <div class="flex items-center gap-6 md:gap-8">
            {{-- Notifications --}}
            <div
                class="hidden md:flex flex-col items-center cursor-pointer group/nav text-gray-700 hover:text-brand-primary transition-all hover:-translate-y-1 relative dropdown-wrapper">
                <div class="w-10 h-10 rounded-[6px] bg-gray-50 hover:bg-brand-primary-50 flex items-center justify-center transition-colors dropdown-trigger"
                    data-target="notif-menu">
                    <span class="material-symbols-outlined text-2xl">notifications</span>
                </div>
                <span
                    class="text-[9px] font-bold mt-1 uppercase tracking-wider text-gray-500 group-hover/nav:text-brand-primary transition-colors">Thông
                    báo</span>

                <div id="notif-menu"
                    class="absolute right-0 top-full pt-4 -mt-2 w-[400px] z-[100] transition-all duration-300 opacity-0 invisible translate-y-2 origin-top scale-95 dropdown-menu [&.dropdown-active]:opacity-100 [&.dropdown-active]:visible [&.dropdown-active]:translate-y-0 [&.dropdown-active]:scale-100">
                    <div
                        class="bg-white/80 backdrop-blur-2xl border border-white/40 shadow-dropdown rounded-2xl overflow-hidden shadow-2xl">
                        <div class="p-4 border-b border-gray-100/50 flex justify-between items-center bg-white/50">
                            <h4 class="font-bold text-sm">Thông báo</h4>
                            <span
                                class="text-[10px] text-brand-primary font-bold cursor-pointer hover:underline uppercase">Đánh
                                dấu đã đọc</span>
                        </div>
                        <div class="max-h-80 overflow-y-auto p-2">
                            <div class="max-h-80 overflow-y-auto p-2">
                                <div class="p-4 text-center">
                                    <div
                                        class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span
                                            class="material-symbols-outlined text-gray-300 text-2xl">notifications_off</span>
                                    </div>
                                    <p class="text-xs text-gray-500 font-medium">Hiện tại bạn không có thông báo mới nào
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Wishlist --}}
            <a href="#"
                class="hidden md:flex flex-col items-center text-gray-700 hover:text-brand-primary transition-all hover:-translate-y-1">
                <div
                    class="w-10 h-10 rounded-[6px] bg-gray-50 hover:bg-brand-primary-50 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-2xl">favorite</span>
                </div>
                <span
                    class="text-[9px] font-bold mt-1 uppercase tracking-wider text-gray-500 hover:text-brand-primary transition-colors">Yêu
                    thích</span>
            </a>

            {{-- Cart with badge --}}
            <div
                class="flex flex-col items-center cursor-pointer group/nav text-gray-700 hover:text-brand-primary transition-all relative hover:-translate-y-1 dropdown-wrapper">
                <div class="relative w-10 h-10 rounded-[6px] bg-gray-50 hover:bg-brand-primary-50 flex items-center justify-center transition-colors dropdown-trigger"
                    data-target="cart-menu">
                    <span class="material-symbols-outlined text-2xl app-cart-icon">shopping_bag</span>
                    @php $cartCount = session('cart_count', 0); @endphp
                    @if($cartCount > 0)
                        <span
                            class="absolute -top-1 -right-1 bg-brand-primary text-white text-[10px] font-black w-5 h-5 rounded-[4px] flex items-center justify-center shadow-md animate-bounce">{{ $cartCount }}</span>
                    @else
                        <span
                            class="absolute -top-1 -right-1 bg-brand-primary text-white text-[10px] font-black w-5 h-5 rounded-[4px] flex items-center justify-center shadow-md cart-count-badge"
                            style="display:none">0</span>
                    @endif
                </div>
                <span
                    class="text-[9px] font-bold mt-1 uppercase tracking-wider text-gray-500 group-hover/nav:text-brand-primary transition-colors">Giỏ
                    hàng</span>

                <div id="cart-menu"
                    class="absolute right-0 top-full pt-4 -mt-2 w-[450px] z-[100] transition-all duration-300 opacity-0 invisible translate-y-2 origin-top scale-95 dropdown-menu [&.dropdown-active]:opacity-100 [&.dropdown-active]:visible [&.dropdown-active]:translate-y-0 [&.dropdown-active]:scale-100">
                    <div
                        class="bg-white/80 backdrop-blur-2xl border border-white/40 shadow-dropdown rounded-2xl overflow-hidden shadow-2xl">
                        <div class="p-4 border-b border-gray-100/50 flex justify-between items-center bg-white/50">
                            <h4 class="font-bold text-sm">Giỏ hàng (@php echo $cartCount @endphp)</h4>
                            <a href="{{ route('cart.index') ?? '#' }}"
                                class="text-[10px] text-brand-primary font-bold hover:underline uppercase">Xem tất
                                cả</a>
                        </div>
                        <div class="p-5 text-center">
                            @if($cartCount > 0)
                                <p class="text-xs text-gray-600 mb-4 font-medium">Bạn đang có {{ $cartCount }} sản phẩm
                                    trong giỏ</p>
                                <a href="{{ route('cart.index') ?? '#' }}"
                                    class="block w-full bg-brand-primary text-white text-center text-sm font-bold py-2.5 rounded-xl hover:shadow-lg transition-all">Vào
                                    Giỏ Hàng</a>
                            @else
                                <div
                                    class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <span class="material-symbols-outlined text-gray-300 text-2xl">shopping_basket</span>
                                </div>
                                <p class="text-xs text-gray-500 font-medium mb-4">Giỏ hàng của bạn đang trống</p>
                                <a href="{{ route('books.search') ?? '#' }}"
                                    class="block w-full bg-gray-100 text-gray-700 text-center text-sm font-bold py-2.5 rounded-xl hover:bg-gray-200 transition-all">Tiếp
                                    Tục Mua Sắm</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Account --}}
            <div class="relative group/nav dropdown-wrapper">
                <div class="flex flex-col items-center cursor-pointer text-gray-700 hover:text-brand-primary transition-all hover:-translate-y-1 dropdown-trigger"
                    data-target="user-menu">
                    <div
                        class="w-10 h-10 rounded-[6px] bg-gray-50 hover:bg-brand-primary-50 flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-2xl">person</span>
                    </div>
                    <span
                        class="text-[9px] font-bold mt-1 uppercase tracking-wider text-gray-500 group-hover/nav:text-brand-primary transition-colors hidden md:block">Tài
                        khoản</span>
                </div>
                <div id="user-menu"
                    class="absolute right-0 top-full pt-4 -mt-2 w-[350px] z-[100] transition-all duration-300 opacity-0 invisible translate-y-2 origin-top scale-95 dropdown-menu [&.dropdown-active]:opacity-100 [&.dropdown-active]:visible [&.dropdown-active]:translate-y-0 [&.dropdown-active]:scale-100">
                    <div
                        class="bg-white/80 backdrop-blur-2xl border border-white/40 shadow-dropdown rounded-2xl overflow-hidden">

                        @if(session('user_id'))
                        {{-- Đã đăng nhập --}}
                        <div class="p-5 border-b border-gray-100/50 bg-gradient-to-br from-brand-primary/5 to-rose-50/50">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-brand-primary to-rose-500 flex items-center justify-center shadow-md flex-shrink-0">
                                    <span class="text-white font-black text-base">{{ strtoupper(substr(session('user_name', 'U'), 0, 1)) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ session('user_name') }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ session('user_email') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('account.profile') }}"
                                class="px-4 py-3 flex items-center gap-3 hover:bg-brand-primary-50 text-gray-700 hover:text-brand-primary rounded-xl transition-colors">
                                <span class="material-symbols-outlined text-xl">manage_accounts</span>
                                <p class="text-sm font-semibold">Hồ sơ của tôi</p>
                            </a>
                            <a href="{{ route('account.orders') }}"
                                class="px-4 py-3 flex items-center gap-3 hover:bg-brand-primary-50 text-gray-700 hover:text-brand-primary rounded-xl transition-colors">
                                <span class="material-symbols-outlined text-xl">receipt_long</span>
                                <p class="text-sm font-semibold">Đơn hàng của tôi</p>
                            </a>
                            <a href="{{ route('membership.index') }}"
                                class="px-4 py-3 flex items-center gap-3 hover:bg-brand-primary-50 text-gray-700 hover:text-brand-primary rounded-xl cursor-pointer transition-colors">
                                <span class="material-symbols-outlined text-xl">stars</span>
                                <div>
                                    <p class="text-sm font-bold">Thành Viên VIP</p>
                                    <p class="text-[10px] font-medium opacity-80">Tích điểm đổi ngàn quà tặng</p>
                                </div>
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-3 flex items-center gap-3 hover:bg-rose-50 text-gray-500 hover:text-rose-600 rounded-xl transition-colors text-left">
                                    <span class="material-symbols-outlined text-xl">logout</span>
                                    <p class="text-sm font-semibold">Đăng Xuất</p>
                                </button>
                            </form>
                        </div>
                        @else
                        {{-- Chưa đăng nhập --}}
                        <div class="p-5 border-b border-gray-100/50 bg-white/50">
                            <p class="text-xs font-medium text-gray-500 mb-4 text-center">Chào mừng đến với hệ thống</p>
                            <a class="block w-full bg-gradient-to-r from-brand-primary to-rose-500 text-white text-center text-sm font-bold py-2.5 rounded-xl mb-2.5 hover:shadow-lg hover:scale-105 transition-all"
                                href="{{ route('login') }}">Đăng Nhập</a>
                            <a class="block w-full bg-white border border-gray-200 text-gray-700 text-center text-sm font-bold py-2.5 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all"
                                href="{{ route('register') }}">Tạo Tài Khoản</a>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('membership.index') }}"
                                class="px-4 py-3 flex items-center gap-3 hover:bg-brand-primary-50 text-gray-700 hover:text-brand-primary rounded-xl cursor-pointer transition-colors">
                                <span class="material-symbols-outlined text-xl">stars</span>
                                <div>
                                    <p class="text-sm font-bold">Thành Viên VIP</p>
                                    <p class="text-[10px] font-medium opacity-80">Tích điểm đổi ngàn quà tặng</p>
                                </div>
                            </a>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Language Selector --}}
            <div class="relative hidden lg:block dropdown-wrapper">
                <div class="flex items-center gap-2 border border-gray-200 rounded-[4px] px-3 py-2 cursor-pointer hover:bg-white hover:shadow-md transition-all bg-gray-50/50 dropdown-trigger"
                    data-target="lang-menu">
                    <div class="w-6 h-4 rounded-sm overflow-hidden shadow-sm shrink-0 border border-gray-200/50">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2" class="w-full h-full object-cover">
                            <path fill="#da251d" d="M0 0h3v2H0z" />
                            <path fill="#ff0" d="m1.5.5.17.51h.54l-.44.32.17.51-.44-.32-.44.32.17-.51-.44-.32h.54z" />
                        </svg>
                    </div>
                    <span class="text-xs font-black text-gray-700 uppercase">VN</span>
                    <span class="material-symbols-outlined text-gray-400 text-sm">expand_more</span>
                </div>
                <div id="lang-menu"
                    class="absolute right-0 top-full pt-4 -mt-2 w-[260px] z-[100] transition-all duration-300 opacity-0 invisible translate-y-2 origin-top scale-95 dropdown-menu [&.dropdown-active]:opacity-100 [&.dropdown-active]:visible [&.dropdown-active]:translate-y-0 [&.dropdown-active]:scale-100">
                    <div
                        class="bg-white/80 backdrop-blur-2xl border border-white/40 shadow-dropdown rounded-2xl overflow-hidden p-2">
                        <div
                            class="flex items-center gap-3 p-3 rounded-xl bg-brand-primary/10 text-brand-primary cursor-pointer">
                            <div
                                class="w-6 h-4 rounded-sm overflow-hidden shrink-0 border border-gray-200/50 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2"
                                    class="w-full h-full object-cover">
                                    <path fill="#da251d" d="M0 0h3v2H0z" />
                                    <path fill="#ff0"
                                        d="m1.5.5.17.51h.54l-.44.32.17.51-.44-.32-.44.32.17-.51-.44-.32h.54z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold">Tiếng Việt (VN)</span>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 text-gray-700 cursor-pointer transition-colors">
                            <div
                                class="w-6 h-4 rounded-sm overflow-hidden shrink-0 border border-gray-200/50 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 741 390"
                                    class="w-full h-full object-cover">
                                    <path fill="#fff" d="M0 0h741v390H0z" />
                                    <path d="M0 30h741m0 60H0m0 60h741m0 60H0m0 60h741m0 60H0" stroke="#b22234"
                                        stroke-width="30" />
                                    <path fill="#3c3b6e" d="M0 0h296.4v210H0z" />
                                    <g fill="#fff">
                                        <g id="a">
                                            <g id="b">
                                                <g id="c">
                                                    <g id="d">
                                                        <circle r="6.1" cx="24.7" cy="17.5" />
                                                        <circle r="6.1" cx="74.1" cy="17.5" />
                                                        <circle r="6.1" cx="123.5" cy="17.5" />
                                                        <circle r="6.1" cx="172.9" cy="17.5" />
                                                        <circle r="6.1" cx="222.3" cy="17.5" />
                                                        <circle r="6.1" cx="271.7" cy="17.5" />
                                                    </g>
                                                    <circle r="6.1" cx="49.4" cy="35" />
                                                    <circle r="6.1" cx="98.8" cy="35" />
                                                    <circle r="6.1" cx="148.2" cy="35" />
                                                    <circle r="6.1" cx="197.6" cy="35" />
                                                    <circle r="6.1" cx="247" cy="35" />
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <span class="text-xs font-bold">Tiếng Anh (US)</span>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 text-gray-700 cursor-pointer transition-colors group/lang-item">
                            <div
                                class="w-6 h-4 rounded-sm overflow-hidden shrink-0 border border-gray-200/50 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 20"
                                    class="w-full h-full object-cover">
                                    <rect width="30" height="20" fill="#ee1c25" />
                                    <path fill="#ff0"
                                        d="M5 2l1.1 3.5h3.7L6.8 7.7l1.1 3.5L5 9.1l-2.9 2.1 1.1-3.5-3-2.2h3.7L5 2z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold">Tiếng Trung (CN)</span>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 text-gray-700 cursor-pointer transition-colors">
                            <div
                                class="w-6 h-4 rounded-sm overflow-hidden shrink-0 border border-gray-200/50 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2"
                                    class="w-full h-full object-cover">
                                    <path fill="#fff" d="M0 0h3v2H0z" />
                                    <circle cx="1.5" cy="1" r=".6" fill="#bc002d" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold">Tiếng Nhật (JP)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- header.js is bundled in resources/js/app.js --}}