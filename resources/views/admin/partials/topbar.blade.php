{{-- Admin Topbar Component --}}
<header class="h-[var(--admin-topbar-height,64px)] flex items-center justify-between px-6 flex-shrink-0 z-50 sticky top-0 bg-white/80 backdrop-blur-xl border-b border-brand-primary/5 shadow-sm transition-all duration-300">

    {{-- Left: Page Title + Greeting --}}
    <div class="flex items-center gap-3 lg:gap-4">
        {{-- Mobile Hamburger --}}
        <button id="mobile-menu-btn" class="lg:hidden p-1.5 text-gray-500 hover:text-brand-primary hover:bg-brand-primary/5 rounded-lg transition-colors border border-transparent hover:border-brand-primary/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-[24px]">menu</span>
        </button>
        <h1 class="text-xl font-black text-gray-800 hidden sm:block font-heading tracking-tight">
            @yield('page-title', 'Dashboard')
        </h1>
        <span class="h-6 w-[1px] bg-gray-200"></span>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-gray-500">
                Chào buổi sáng, <span class="text-brand-primary font-black text-base">{{ auth()->user()->name ?? 'Admin' }}!</span>
            </p>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                {{ now()->format('d/m/Y H:i') }} &bull; <span class="text-green-600">Online</span>
            </p>
        </div>
    </div>

    {{-- Right: Live Stats + Search + Notifications --}}
    <div class="flex items-center gap-6">

        {{-- Live Visitors --}}
        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full border border-green-200/50 bg-green-50/50 backdrop-blur-sm">
            <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse shadow-sm shadow-green-500/50"></span>
            <span class="text-[11px] font-black text-green-700">124 ĐANG TRUY CẬP</span>
        </div>

        <div class="flex items-center gap-2 sm:gap-4 border-l border-gray-100 pl-3 sm:pl-6">

            {{-- Advanced Search Bar --}}
            <div class="relative hidden lg:flex items-center h-[38px] group">
                <div class="relative h-full flex">
                    <select class="appearance-none bg-gray-50/50 backdrop-blur-sm border border-gray-200 border-r-0 text-gray-600 text-[11px] font-black py-0 pl-3 pr-6 rounded-l-xl hover:bg-white transition-all focus:outline-none focus:ring-0 cursor-pointer h-full uppercase tracking-tighter">
                        <option>Tất cả</option>
                        <option>Đơn hàng</option>
                        <option>Khách hàng</option>
                        <option>Sản phẩm</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-1.5 top-1/2 -translate-y-1/2 text-gray-400 text-[14px] pointer-events-none">expand_more</span>
                    
                    <div class="relative flex-1">
                        <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                        <input type="text"
                               class="pl-9 pr-4 h-full border border-gray-200 rounded-r-xl text-sm focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary/50 w-64 bg-gray-50/50 backdrop-blur-sm transition-all group-hover:border-brand-primary/30 outline-none placeholder:text-gray-400 placeholder:font-medium"
                               placeholder="Tìm kiếm thông minh...">
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2">
                {{-- Notifications --}}
                <button class="relative p-2 text-gray-500 hover:text-brand-primary transition-all bg-gray-50/80 rounded-xl border border-transparent hover:border-brand-primary/10 hover:bg-white hover:shadow-sm">
                    <span class="material-symbols-outlined !text-[20px]">notifications</span>
                    <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 bg-brand-primary text-white text-[9px] font-black rounded-full border-2 border-white flex items-center justify-center shadow-lg shadow-brand-primary/30">8</span>
                </button>
            </div>

        </div>
    </div>
</header>

