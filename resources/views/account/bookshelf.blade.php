@extends('layouts.app')

@section('title', 'Tủ sách cá nhân - THLD')

@section('content')
<style>
    .tab-active {
        border-bottom: 3px solid var(--primary);
        color: var(--primary);
        font-weight: 700;
    }
    .soft-shadow {
        box-shadow: 0 4px 12px 0 rgba(0,0,0,0.05);
    }
    .reading-card {
        @apply bg-white border border-gray-100 rounded-xl p-4 transition-all duration-300 flex flex-col h-full relative;
    }
    .reading-card:hover {
        @apply shadow-xl border-gray-200 -translate-y-1;
    }
    .progress-bar-container {
        @apply w-full h-2 bg-gray-100 rounded-full overflow-hidden;
    }
    .progress-bar-fill {
        @apply h-full bg-primary transition-all duration-500;
    }
</style>

<main class="max-w-main mx-auto px-4 py-8">
    <nav class="flex items-center gap-2 text-xs text-charcoal mb-6 font-medium">
        <a class="hover:text-primary" href="{{ route('home') }}">Trang chủ</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <a class="hover:text-primary" href="{{ route('account.dashboard') }}">Tài khoản</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <span class="text-gray-800 font-bold">Tủ sách cá nhân</span>
    </nav>
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800 mb-2">Tủ Sách Cá Nhân</h1>
            <p class="text-sm text-charcoal font-medium">Chào Minh Hoàng, bạn đang có <span class="text-primary font-bold">12 cuốn sách</span> trong danh sách đọc.</p>
        </div>
        <div class="flex gap-3">
            <button class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all">
                <span class="material-symbols-outlined text-lg">filter_alt</span> Lọc &amp; Sắp xếp
            </button>
            <button class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-primary-dark transition-all shadow-md">
                <span class="material-symbols-outlined text-lg">add</span> Thêm sách mới
            </button>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="flex border-b border-gray-100 overflow-x-auto">
            <button class="flex-1 min-w-[120px] py-4 text-center tab-active flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-xl">auto_stories</span>
                <span class="text-sm uppercase tracking-wide">Đang đọc</span>
                <span class="bg-red-50 text-primary text-[10px] px-1.5 py-0.5 rounded-full font-bold">3</span>
            </button>
            <button class="flex-1 min-w-[120px] py-4 text-center text-charcoal font-semibold hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 border-b-2 border-transparent">
                <span class="material-symbols-outlined text-xl">bookmark_add</span>
                <span class="text-sm uppercase tracking-wide">Muốn đọc</span>
                <span class="bg-gray-100 text-charcoal text-[10px] px-1.5 py-0.5 rounded-full font-bold">7</span>
            </button>
            <button class="flex-1 min-w-[120px] py-4 text-center text-charcoal font-semibold hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 border-b-2 border-transparent">
                <span class="material-symbols-outlined text-xl">check_circle</span>
                <span class="text-sm uppercase tracking-wide">Đã đọc</span>
                <span class="bg-gray-100 text-charcoal text-[10px] px-1.5 py-0.5 rounded-full font-bold">24</span>
            </button>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Book Card 1 -->
            <div class="reading-card group">
                <div class="flex gap-4 mb-4">
                    <div class="w-24 h-36 flex-shrink-0 rounded-lg shadow-md overflow-hidden bg-gray-50 border border-gray-100">
                        <img alt="Atomic Habits" class="w-full h-full object-cover group-hover:scale-105 transition-transform" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDs0yRnYobp_sN47Sta1VsjQbtGWYFMgiKZWO4-HpAtv32qNefUPv3e_pbBeOmHyZKox3miVVzQvaWnQ9DIUBepO9OqWGfO3DjFdcZp96XcU8v7UEukcaEpvP1k5YmHvYBGVWsDptTMZwnXDJ9XK96hZigh-YD0YwIIuYglKPzPGusEHJ5I5kw7T8zBkNgFkOrZKDvXMRhFpHbn9sbY_fwwFjum-tOTrj4chlPM0dQrrU9tw9519q36D9rTrARCYKRG3d3rRkdHIhk" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-800 line-clamp-2 leading-tight mb-1 group-hover:text-primary transition-colors">
                            Atomic Habits: Thay Đổi Tí Hon Hiệu Quả Bất Ngờ</h3>
                        <p class="text-[11px] text-charcoal font-bold mb-3">James Clear</p>
                        <div class="flex items-center text-yellow-400 gap-0.5 mb-2">
                            <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <span class="material-symbols-outlined text-sm">star</span>
                            <span class="text-[10px] text-charcoal font-bold ml-1">4.0</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-2 mt-auto">
                    <div class="flex justify-between items-center text-[11px] font-bold text-charcoal">
                        <span>Tiến độ đọc: 75%</span>
                        <span class="text-gray-400">225/300 trang</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: 75%;"></div>
                    </div>
                    <div class="flex gap-2 pt-3">
                        <button class="flex-1 bg-primary text-white py-2 rounded-lg text-[11px] font-bold hover:bg-primary-dark transition-all">Cập nhật</button>
                        <button class="w-10 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-charcoal hover:bg-gray-50">
                            <span class="material-symbols-outlined text-lg">more_horiz</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Book Card 2 -->
            <div class="reading-card group">
                <div class="flex gap-4 mb-4">
                    <div class="w-24 h-36 flex-shrink-0 rounded-lg shadow-md overflow-hidden bg-gray-50 border border-gray-100">
                        <img alt="Psychology of Money" class="w-full h-full object-cover group-hover:scale-105 transition-transform" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD3DaPjQ5C6WavM-mNWY5R9hbXJop44WJnewO-GqcjTbFtc3RtBCVebcmJZ99Hg6R9VdaRXVCGquFWN2P9-acLS6MFqneRSmfT0u8aD6ghi6PrWIi0yP173EF-Pcfs22EMEEmMSp7kUSk2vZ6JmOPC6zxRG26XxlBYur27biM_fg7MVlMDsCcTE6OFYdvViFp0lnVzjjtaYKGhjJ6oiOGg6ADTVALww4KfC_qvl4Oi10f7w4Dt226mkaO027QqpjgWh5007Lu2Z5Pw" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-800 line-clamp-2 leading-tight mb-1 group-hover:text-primary transition-colors">
                            The Psychology of Money - Tâm Lý Học Về Tiền</h3>
                        <p class="text-[11px] text-charcoal font-bold mb-3">Morgan Housel</p>
                        <div class="flex items-center text-yellow-400 gap-0.5 mb-2">
                            <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <span class="text-[10px] text-charcoal font-bold ml-1">5.0</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-2 mt-auto">
                    <div class="flex justify-between items-center text-[11px] font-bold text-charcoal">
                        <span>Tiến độ đọc: 15%</span>
                        <span class="text-gray-400">32/210 trang</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: 15%;"></div>
                    </div>
                    <div class="flex gap-2 pt-3">
                        <button class="flex-1 bg-primary text-white py-2 rounded-lg text-[11px] font-bold hover:bg-primary-dark transition-all">Cập nhật</button>
                        <button class="w-10 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-charcoal hover:bg-gray-50">
                            <span class="material-symbols-outlined text-lg">more_horiz</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Book Card 3 -->
            <div class="reading-card group">
                <div class="flex gap-4 mb-4">
                    <div class="w-24 h-36 flex-shrink-0 rounded-lg shadow-md overflow-hidden bg-gray-50 border border-gray-100">
                        <img alt="Start with Why" class="w-full h-full object-cover group-hover:scale-105 transition-transform" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDSEuXjIxNXHQfChNr9Aj9NUehYNh2VFuHax_1razTraDI9ml_4SU14gURUfI10Kh-1EGDqRHd5FrHg8QE76-IZH_rkxJQKUeANYGCwouuZLq6KJX8x4wqvJCsASSW3hDVkjTtzLsWIg5wamBlBFvDHwTXkANt9_4lwu2ufWxPLD-NBOfW63ATVbhw1UZ2-vkxwlNc_XJJc__19ga0emPwujecdGnNDtF02U94AOIqimh9yWiuHE7ukO9mGVJJeJzIDlC69M33L7dE" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-800 line-clamp-2 leading-tight mb-1 group-hover:text-primary transition-colors">
                            Bắt Đầu Với Câu Hỏi Tại Sao</h3>
                        <p class="text-[11px] text-charcoal font-bold mb-3">Simon Sinek</p>
                        <div class="flex items-center text-yellow-400 gap-0.5 mb-2">
                            <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <span class="material-symbols-outlined text-sm">star</span>
                            <span class="material-symbols-outlined text-sm">star</span>
                            <span class="text-[10px] text-charcoal font-bold ml-1">3.0</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-2 mt-auto">
                    <div class="flex justify-between items-center text-[11px] font-bold text-charcoal">
                        <span>Tiến độ đọc: 45%</span>
                        <span class="text-gray-400">120/265 trang</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: 45%;"></div>
                    </div>
                    <div class="flex gap-2 pt-3">
                        <button class="flex-1 bg-primary text-white py-2 rounded-lg text-[11px] font-bold hover:bg-primary-dark transition-all">Cập nhật</button>
                        <button class="w-10 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-charcoal hover:bg-gray-50">
                            <span class="material-symbols-outlined text-lg">more_horiz</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Add New Book -->
            <div class="border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center p-8 cursor-pointer hover:border-primary hover:bg-red-50/30 transition-all group">
                <span class="material-symbols-outlined text-4xl text-gray-300 group-hover:text-primary mb-3">library_add</span>
                <p class="text-sm font-bold text-gray-400 group-hover:text-primary">Thêm sách đang đọc</p>
            </div>
            
        </div>
        <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-center">
            <button class="text-charcoal font-bold text-sm flex items-center gap-2 hover:text-primary transition-colors">
                Xem thêm <span class="material-symbols-outlined text-lg">expand_more</span>
            </button>
        </div>
    </div>
</main>
@endsection

