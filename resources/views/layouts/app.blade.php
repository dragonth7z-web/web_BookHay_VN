{{-- Layout chính cho giao diện THLD --}}
<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'THLD - Hệ thống Nhà sách chuyên nghiệp')</title>

    {{-- Font & icon --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- 
        Typography System: "The Classic Publisher"
        - Lora (Serif): Tiêu đề — gợi cảm giác trang sách, mực in truyền thống
        - Be Vietnam Pro: Body — tối ưu tiếng Việt, sạch sẽ, dễ đọc
        - Inter: Số, giá tiền, UI nhỏ — precision & clarity
    --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap"
        rel="stylesheet">


    {{-- Global App Config --}}
    <script>
        window.APP_CONFIG = {
            baseUrl: '{{ url("/") }}',
            freeship: {{ config('shop.freeship_threshold', 500000) }}
        };
    </script>

    {{-- CSS / JS được build bởi Vite (đã cấu hình Tailwind) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Stack cho CSS theo trang --}}
    @stack('styles')

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logos/favicon_thld.png') }}">
</head>

<body
    class="bg-[var(--color-paper,#F9F7F2)] text-[var(--color-ink,#1A1410)] transition-colors duration-300 relative min-h-screen selection:bg-primary/20 selection:text-primary">

    {{-- Standard Background --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-[-1] bg-gray-50"></div>

    {{-- Reading Progress Bar --}}
    <div id="reading-progress"></div>

    {{-- Mobile Nav Removed --}}

    {{-- Command Palette Search --}}
    @include('components.search-palette')

    {{-- Live Social Proof Toast --}}
    @include('components.social-proof')

    {{-- Thanh topbar nhỏ phía trên --}}
    @include('components.topbar')

    {{-- Header / Thanh điều hướng chính --}}
    @include('components.header')

    {{-- Category Navbar (Fahasa-style horizontal menu) --}}
    {{-- @include('components.category-navbar') --}}

    <main class="max-w-main mx-auto px-2 pt-4 pb-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Sticky Mobile CTA Bar --}}
    <div class="fixed bottom-0 left-0 right-0 translate-y-full transition-transform duration-400 ease-[cubic-bezier(0.25,1,0.5,1)] md:hidden h-16 px-4 bg-white border-t border-slate-200 shadow-lg flex items-center gap-3 pb-[env(safe-area-inset-bottom,0px)] [&.visible]:translate-y-0"
        id="sticky-cta-bar" role="complementary" aria-label="Hành động nhanh">
        <a href="{{ route('books.search') }}"
            class="flex-1 h-11 rounded-xl bg-brand-primary text-white flex items-center justify-center gap-2 font-bold text-sm no-underline cursor-pointer border-0 shadow-brand hover:scale-105 active:scale-95 transition-all">
            <span class="material-symbols-outlined">shopping_bag</span>
            Mua Ngay
        </a>
        <a href="#flash-sale"
            class="flex-1 h-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center gap-2 font-bold text-sm no-underline cursor-pointer border-0 hover:bg-slate-200 transition-all">
            <span class="material-symbols-outlined">bolt</span>
            Xem Flash Sale
        </a>
    </div>

    {{-- Chatbot Button (replacing Back-to-top) --}}
    <div id="chatbot-trigger" class="chatbot-trigger group shadow-brand" title="Chat với chúng tôi" onclick="alert('Đang kết nối với tư vấn viên THLD...')">
        <div class="chatbot-pulse"></div>
        <span class="material-symbols-outlined text-2xl relative z-10">smart_toy</span>
    </div>

    {{-- JS Global — Vite bundle --}}
    @vite('resources/js/app.js')

    {{-- Stack cho JS theo trang --}}
    @stack('scripts')

</body>

</html>