@extends('layouts.auth')

@section('title', 'Đăng nhập - THLD Bookstore')

@section('content')
<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50 overflow-x-hidden font-sans selection:bg-brand-primary/10 selection:text-brand-primary">

    {{-- ═══ LEFT PANEL: Branding & Inspiration ═══ --}}
    <div class="relative hidden lg:flex lg:w-[42%] flex-col p-12 overflow-hidden bg-[#0f172a]">
        {{-- High-end background --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-radial from-brand-primary/30 via-transparent to-transparent opacity-40 animate-aurora-float"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-950 via-slate-950 to-rose-950/30"></div>
            {{-- Noise overlay --}}
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\"0 0 256 256\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cfilter id=\"noise\"%3E%3CfeTurbulence type=\"fractalNoise\" baseFrequency=\"0.9\" numOctaves=\"4\" stitchTiles=\"stitch\"/%3E%3C/filter%3E%3Crect width=\"100%25\" height=\"100%25\" filter=\"url(%23noise)\"/%3E%3C/svg%3E');"></div>
        </div>

        {{-- Back to home --}}
        <a href="{{ route('home') }}" class="relative z-10 flex items-center gap-2 text-white/60 hover:text-white transition-all group font-bold text-sm">
            <span class="material-symbols-outlined transition-transform group-hover:-translate-x-1">arrow_back</span>
            <span>Quay lại trang chủ</span>
        </a>

        {{-- Center content --}}
        <div class="relative z-10 flex-1 flex flex-col justify-center">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-primary to-orange-500 flex items-center justify-center shadow-2xl shadow-brand-primary/40 mb-8 animate-bounce-premium">
                <span class="material-symbols-outlined text-white text-[32px]">auto_stories</span>
            </div>
            
            <h2 class="text-4xl xl:text-5xl font-black text-white leading-tight mb-4 font-heading tracking-tight">
                Chào mừng bạn <br> trở lại với <span class="text-brand-primary">THLD.</span>
            </h2>
            <p class="text-white/50 text-lg leading-relaxed max-w-sm mb-10 font-medium">
                Hàng ngàn tựa sách hay và ưu đãi độc quyền đang chờ bạn khám phá. Đăng nhập để tiếp tục hành trình tri thức.
            </p>

            {{-- Stats --}}
            <div class="flex items-center gap-6 mb-12">
                <div class="flex flex-col">
                    <span class="text-2xl font-black text-white">50K+</span>
                    <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Đầu sách</span>
                </div>
                <div class="h-8 w-px bg-white/10"></div>
                <div class="flex flex-col">
                    <span class="text-2xl font-black text-white">10K+</span>
                    <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Độc giả</span>
                </div>
                <div class="h-8 w-px bg-white/10"></div>
                <div class="flex flex-col">
                    <span class="text-2xl font-black text-white">4.9★</span>
                    <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Đánh giá</span>
                </div>
            </div>

            {{-- Quote / Testimonial --}}
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 relative overflow-hidden group">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-brand-primary/10 rounded-full blur-2xl group-hover:bg-brand-primary/20 transition-all"></div>
                <span class="text-brand-primary text-5xl font-serif leading-none absolute top-4 left-4 opacity-50">“</span>
                <div class="pl-8">
                    <p class="text-white/80 italic text-sm leading-loose mb-2">
                        Niềm tin của bạn – Ẩn số của chúng tôi. Bởi đằng sau niềm tin, là những điều chưa kể.
                    </p>
                    <p class="text-[10px] font-black text-brand-primary uppercase tracking-widest">THLD Book Editorial</p>
                </div>
            </div>
        </div>

        {{-- Floating Decorations --}}
        <div class="absolute top-20 right-10 text-4xl opacity-20 animate-float-book">📚</div>
        <div class="absolute bottom-20 left-10 text-4xl opacity-20 animate-float-book [animation-delay:2s]">📖</div>
    </div>

    {{-- ═══ RIGHT PANEL: Auth Form ═══ --}}
    <div class="flex-1 flex flex-col items-center justify-center p-8 lg:p-12 xl:p-24 bg-white relative">
        
        {{-- Decorative Blob behind the form on mobile --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-primary/5 rounded-full blur-[100px] lg:hidden"></div>

        <div class="w-full max-w-[440px] relative z-10">
            
            {{-- Mobile Logo --}}
            <div class="flex lg:hidden items-center justify-center gap-3 mb-10 group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-primary to-orange-500 flex items-center justify-center shadow-lg shadow-brand-primary/20 transition-transform group-hover:scale-110">
                    <span class="material-symbols-outlined text-white">auto_stories</span>
                </div>
                <span class="text-2xl font-black tracking-tighter text-slate-950 font-heading">THLD BOOKSTORE</span>
            </div>

            <div class="mb-10">
                <h1 class="text-3xl font-black text-slate-950 mb-2 font-heading tracking-tight">Đăng nhập</h1>
                <p class="text-slate-500 font-medium">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="text-brand-primary font-black hover:underline underline-offset-4">Đăng ký miễn phí</a>
                </p>
            </div>

            {{-- Alerts --}}
            @if(session('error') || $errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-100 flex items-center gap-3 mb-6 animate-slide-in-left">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    <p class="text-sm font-bold text-red-600">{{ session('error') ?? $errors->first() }}</p>
                </div>
            @endif

            @if(session('success'))
                <div class="p-4 rounded-xl bg-green-50 border border-green-100 flex items-center gap-3 mb-6 animate-slide-in-left">
                    <span class="material-symbols-outlined text-green-500">check_circle</span>
                    <p class="text-sm font-bold text-green-600">{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5" id="loginForm">
                @csrf

                {{-- Email Field --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1" for="email">Địa chỉ Email</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl transition-colors group-focus-within:text-brand-primary">mail</span>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-slate-900 font-medium transition-all outline-none focus:border-brand-primary/30 focus:bg-white focus:ring-4 focus:ring-brand-primary/5 placeholder:text-slate-300"
                            placeholder="bookstore@thld.com"
                            value="{{ old('email') }}"
                            required autofocus
                        >
                    </div>
                </div>

                {{-- Password Field --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1" for="password">Mật khẩu</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl transition-colors group-focus-within:text-brand-primary">lock</span>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-slate-900 font-medium transition-all outline-none focus:border-brand-primary/30 focus:bg-white focus:ring-4 focus:ring-brand-primary/5 placeholder:text-slate-300"
                            placeholder="••••••••"
                            required
                        >
                        <button type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 p-1 text-slate-300 hover:text-slate-500 transition-colors" onclick="togglePassword('password', this)" tabindex="-1">
                            <span class="material-symbols-outlined text-xl">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between py-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" id="remember" class="hidden peer">
                        <div class="w-5 h-5 rounded-md border-2 border-slate-200 peer-checked:bg-brand-primary peer-checked:border-brand-primary flex items-center justify-center transition-all group-hover:border-brand-primary/50">
                            <span class="material-symbols-outlined text-white text-sm scale-0 peer-checked:scale-100 transition-transform">check</span>
                        </div>
                        <span class="text-sm font-bold text-slate-500 group-hover:text-slate-700 transition-colors">Nhớ đăng nhập</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-black text-brand-primary hover:underline underline-offset-4">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="w-full py-4 bg-gradient-to-r from-brand-primary to-rose-600 text-white font-black rounded-2xl shadow-xl shadow-brand-primary/30 hover:shadow-2xl hover:shadow-brand-primary/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group/btn" id="submitBtn">
                    <span class="btn-text">Đăng nhập tài khoản</span>
                    <span class="material-symbols-outlined transition-transform group-hover/btn:translate-x-1">arrow_forward</span>
                    <div class="btn-loader hidden">
                         <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </button>
            </form>

            <div class="relative my-10">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
                <div class="relative flex justify-center text-xs uppercase tracking-widest"><span class="bg-white px-4 text-slate-400 font-black">Hoặc đăng nhập với</span></div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button type="button" class="flex items-center justify-center gap-3 py-3 border-2 border-slate-100 rounded-2xl hover:bg-slate-50 transition-all font-bold text-slate-600 group">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5 group-hover:scale-110 transition-transform" alt="Google">
                    <span>Google</span>
                </button>
                <button type="button" class="flex items-center justify-center gap-3 py-3 border-2 border-slate-100 rounded-2xl hover:bg-slate-50 transition-all font-bold text-slate-600 group">
                    <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-5 h-5 group-hover:scale-110 transition-transform" alt="Facebook">
                    <span>Facebook</span>
                </button>
            </div>

        </div>

        {{-- Footer-like info --}}
        <p class="absolute bottom-8 left-1/2 -translate-x-1/2 text-center text-xs text-slate-400 font-bold uppercase tracking-widest w-full">
            © 2026 THLD Bookstore &bull; Secured Platform
        </p>

    </div>

</div>

@push('scripts')
    @vite('resources/js/auth/auth.js')
    <script>
        handleAuthFormSubmit('loginForm', 'submitBtn', 'Đang đăng nhập...');
    </script>
@endpush
@endsection


