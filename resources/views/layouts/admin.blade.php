{{-- Admin Layout - THLD Admin Dashboard --}}
<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <script>
      if (localStorage.getItem('admin-theme') === 'dark') {
        document.documentElement.classList.add('dark');
      }
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - THLD Admin</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

    {{-- Vite built CSS/JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-[var(--admin-bg,#f5f7fa)] text-gray-900 font-sans min-h-screen relative overflow-x-hidden selection:bg-brand-primary/10 selection:text-brand-primary dark:bg-slate-950 dark:text-gray-100">

    {{-- Aurora Background Ornaments --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[120px] -left-[80px] w-[500px] h-[500px] rounded-full bg-radial from-brand-primary/10 to-transparent animate-aurora-float blur-3xl opacity-60"></div>
        <div class="absolute top-1/3 -right-[100px] w-[400px] h-[400px] rounded-full bg-radial from-violet-500/10 to-transparent animate-aurora-float blur-3xl opacity-60 [animation-delay:2s]"></div>
        <div class="absolute -bottom-[80px] left-1/3 w-[450px] h-[450px] rounded-full bg-radial from-sky-500/10 to-transparent animate-aurora-float blur-3xl opacity-60 [animation-delay:4s]"></div>
    </div>

    {{-- Admin Container --}}
    <div class="relative flex min-h-screen z-10 group/sidebar" id="admin-container">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        {{-- Mobile Sidebar Overlay --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity duration-300 opacity-0 cursor-pointer"></div>

        {{-- Main Content Area --}}
        <main class="flex-1 overflow-auto flex flex-col min-w-0 transition-all duration-300 ease-in-out">

            {{-- Top Header --}}
            @include('admin.partials.topbar')

            {{-- Page Content --}}
            <div class="flex-1 p-6 relative">
                <div class="max-w-[1400px] mx-auto w-full space-y-8">
                    @yield('content')
                </div>

                {{-- Admin Footer --}}
                <footer class="flex flex-col md:flex-row items-center justify-between py-8 gap-4 border-t border-gray-100 mt-12 max-w-[1400px] mx-auto">
                    <div class="text-[10px] text-gray-400 font-black tracking-widest uppercase opacity-70">
                        © 2026 THLD ULTIMATE MANAGEMENT SYSTEM
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5 px-3 py-1 bg-white/50 backdrop-blur-sm border border-gray-100 rounded-full shadow-sm hover:shadow-md transition-all">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            <span class="text-[10px] font-bold text-gray-500">Hệ thống: Ổn định</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 bg-white/50 backdrop-blur-sm border border-gray-100 rounded-full shadow-sm">
                            <span class="text-[10px] font-bold text-gray-500">Database: 18ms</span>
                        </div>
                    </div>
                </footer>
            </div>
        </main>
    </div>

    @stack('scripts')
    
    {{-- Core Admin Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/admin/admin-layout.js') }}" defer></script>
</body>
</html>

