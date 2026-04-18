{{-- Admin Sidebar Component --}}
<aside id="admin-sidebar" 
    class="fixed lg:sticky top-0 left-0 z-50 lg:z-20 h-screen bg-white/90 backdrop-blur-2xl border-right border-brand-primary/10 shadow-2xl transition-all duration-300 ease-in-out flex flex-col flex-shrink-0
    w-[var(--admin-sidebar-width,260px)] group-[.is-collapsed]/sidebar:w-[var(--admin-sidebar-collapsed-width,72px)]
    -translate-x-full lg:translate-x-0 [&.mobile-open]:translate-x-0
    dark:bg-slate-900/90 dark:border-slate-800">

    {{-- Logo / Header --}}
    <div class="h-16 flex items-center justify-between px-6 border-b border-brand-primary/5 bg-gradient-to-br from-brand-primary/5 to-transparent overflow-hidden">
        <div class="flex items-center gap-3 transition-opacity duration-300 group-[.is-collapsed]/sidebar:opacity-0 group-[.is-collapsed]/sidebar:invisible">
            <img src="{{ asset('images/logos/thanh_dieu_huong.png') }}" alt="THLD Logo" class="h-8 w-auto min-w-[32px]">
            <span class="text-gray-400 text-[10px] font-black mt-2 tracking-tighter">ADMIN</span>
        </div>
        <div class="absolute left-6 hidden group-[.is-collapsed]/sidebar:flex items-center justify-center w-8 transition-opacity duration-300">
             <img src="{{ asset('images/logos/favicon_thld.png') }}" alt="THLD Logo" class="h-6 w-auto">
        </div>
        <button id="close-sidebar-btn" class="lg:hidden p-1 text-gray-400 hover:text-brand-primary transition-colors">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-6 px-3 custom-scrollbar space-y-1">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="group/item relative flex items-center gap-3 p-3 rounded-xl transition-all duration-200 hover:bg-brand-primary/5 hover:translate-x-1
           {{ request()->routeIs('admin.dashboard') ? 'bg-brand-primary/10 text-brand-primary font-bold dark:bg-brand-primary/10' : 'text-gray-500 font-medium' }}">
            <span class="material-symbols-outlined text-[22px] transition-transform group-hover/item:scale-110">dashboard</span>
            <span class="text-[14px] whitespace-nowrap transition-all duration-300 group-[.is-collapsed]/sidebar:opacity-0 group-[.is-collapsed]/sidebar:invisible">Dashboard</span>
            @if(request()->routeIs('admin.dashboard'))
                <div class="absolute left-0 top-1/4 bottom-1/4 w-[3px] bg-brand-primary rounded-r-full"></div>
            @endif
        </a>

        @php
            $sections = [
                ['label' => 'Quản lý cơ bản', 'items' => [
                    ['route' => 'admin.books.index', 'icon' => 'menu_book', 'text' => 'Sách & Sản phẩm', 'badge' => null],
                    ['route' => 'admin.orders.index', 'icon' => 'shopping_cart', 'text' => 'Đơn hàng', 'badge' => '12'],
                    ['route' => 'admin.users.index', 'icon' => 'people', 'text' => 'Khách hàng', 'badge' => '+5'],
                    ['route' => 'admin.purchase-orders.index', 'icon' => 'inventory_2', 'text' => 'Kho & Nhập hàng', 'badge' => '3'],
                ]],
                ['label' => 'Quản lý nội dung', 'items' => [
                    ['route' => 'admin.categories.index', 'icon' => 'category', 'text' => 'Danh mục'],
                    ['route' => 'admin.authors.index', 'icon' => 'person_edit', 'text' => 'Tác giả'],
                    ['route' => 'admin.publishers.index', 'icon' => 'business', 'text' => 'Nhà Xuất Bản'],
                    ['route' => 'admin.banner.index', 'icon' => 'image', 'text' => 'Banner & Nội dung'],
                    ['route' => 'admin.collections.index', 'icon' => 'collections', 'text' => 'Bộ sưu tập'],
                ]],
                ['label' => 'Hệ thống', 'items' => [
                    ['route' => 'admin.roles.index', 'icon' => 'badge', 'text' => 'Nhân sự'],
                    ['route' => 'admin.settings.index', 'icon' => 'settings', 'text' => 'Cài đặt'],
                    ['route' => 'admin.system-logs.index', 'icon' => 'terminal', 'text' => 'Nhật ký', 'badge' => 'LIVE'],
                ]]
            ];
        @endphp

        @foreach($sections as $section)
            <div class="pt-4 pb-2 px-3 flex items-center gap-2 group-[.is-collapsed]/sidebar:justify-center">
                <div class="flex-1 h-px bg-brand-primary/5 group-[.is-collapsed]/sidebar:hidden"></div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest whitespace-nowrap group-[.is-collapsed]/sidebar:hidden">{{ $section['label'] }}</span>
                <div class="flex-1 h-px bg-brand-primary/5"></div>
            </div>

            @foreach($section['items'] as $item)
                @php $active = request()->routeIs(str_replace('.index', '.*', $item['route'])); @endphp
                <a href="{{ route($item['route']) }}"
                   class="group/item relative flex items-center gap-3 p-3 rounded-xl transition-all duration-200 hover:bg-brand-primary/5 hover:translate-x-1
                   {{ $active ? 'bg-brand-primary/10 text-brand-primary font-bold dark:bg-brand-primary/10' : 'text-gray-500 font-medium' }}">
                    <span class="material-symbols-outlined text-[22px] transition-transform group-hover/item:scale-110">{{ $item['icon'] }}</span>
                    <span class="text-[14px] whitespace-nowrap transition-all duration-300 group-[.is-collapsed]/sidebar:opacity-0 group-[.is-collapsed]/sidebar:invisible">{{ $item['text'] }}</span>
                    
                    @if(isset($item['badge']))
                        <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-black bg-brand-primary/10 text-brand-primary group-[.is-collapsed]/sidebar:absolute group-[.is-collapsed]/sidebar:top-2 group-[.is-collapsed]/sidebar:right-2 group-[.is-collapsed]/sidebar:scale-75">
                            {{ $item['badge'] }}
                        </span>
                    @endif

                    @if($active)
                        <div class="absolute left-0 top-1/4 bottom-1/4 w-[3px] bg-brand-primary rounded-r-full"></div>
                    @endif
                </a>
            @endforeach
        @endforeach

    </nav>

    {{-- User Profile Footer --}}
    <div class="p-4 border-t border-brand-primary/10 bg-gradient-to-tr from-brand-primary/5 to-transparent overflow-hidden">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-white transition-all group/user relative">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-primary to-rose-600 flex items-center justify-center text-white font-black text-sm flex-shrink-0 shadow-lg shadow-brand-primary/20 transition-transform group-hover/user:scale-110">
                AD
            </div>
            <div class="flex-1 min-w-0 transition-opacity duration-300 group-[.is-collapsed]/sidebar:opacity-0 group-[.is-collapsed]/sidebar:invisible">
                <div class="flex items-center gap-1.5">
                    <p class="text-sm font-bold text-gray-800 truncate">Admin THLD</p>
                    <span class="text-[8px] bg-brand-primary text-white px-1.5 py-0.5 rounded font-black shadow-sm uppercase">Pro</span>
                </div>
                <p class="text-[10px] text-gray-500 truncate flex items-center gap-1 mt-0.5">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                    Hệ thống v4.2.0
                </p>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <button onclick="document.getElementById('logout-form').submit();" class="text-gray-400 hover:text-brand-primary transition-colors group-hover/user:translate-x-1 group-[.is-collapsed]/sidebar:absolute group-[.is-collapsed]/sidebar:inset-0 group-[.is-collapsed]/sidebar:opacity-0">
                <span class="material-symbols-outlined text-[20px]">logout</span>
            </button>
        </div>
    </div>

</aside>

