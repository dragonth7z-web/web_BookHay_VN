@extends('layouts.auth')

@section('title', 'Quên mật khẩu - THLD Bookstore')

@section('content')
<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50 overflow-x-hidden font-sans selection:bg-brand-primary/10 selection:text-brand-primary">

    {{-- ═══ LEFT PANEL: Branding & Steps ═══ --}}
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
                <span class="material-symbols-outlined text-white text-[32px]">lock_reset</span>
            </div>
            
            <h2 class="text-4xl xl:text-5xl font-black text-white leading-tight mb-4 font-heading tracking-tight">
                Khôi phục <br> tài khoản <span class="text-brand-primary">THLD.</span>
            </h2>
            <p class="text-white/50 text-lg leading-relaxed max-w-sm mb-10 font-medium">
                Đừng lo lắng! Chúng tôi sẽ giúp bạn lấy lại quyền truy cập vào tài khoản một cách nhanh chóng và an toàn.
            </p>

            {{-- Steps --}}
            <div class="space-y-8 relative">
                <div class="absolute left-5 top-5 bottom-5 w-0.5 bg-white/5"></div>
                
                <div class="relative flex items-center gap-6 group">
                   <div class="w-10 h-10 rounded-full bg-brand-primary/20 border border-brand-primary/30 flex items-center justify-center text-brand-primary font-black z-10 group-hover:scale-110 transition-transform">1</div>
                   <p class="text-white/60 text-sm font-bold">Nhập địa chỉ email đã đăng ký</p>
                </div>
                
                <div class="relative flex items-center gap-6 group">
                   <div class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/40 font-black z-10 group-hover:scale-110 transition-transform">2</div>
                   <p class="text-white/60 text-sm font-bold">Kiểm tra hộp thư và nhấn vào liên kết</p>
                </div>

                <div class="relative flex items-center gap-6 group">
                   <div class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/40 font-black z-10 group-hover:scale-110 transition-transform">3</div>
                   <p class="text-white/60 text-sm font-bold">Tạo mật khẩu mới và đăng nhập</p>
                </div>
            </div>
        </div>

        {{-- Floating Decorations --}}
        <div class="absolute top-20 right-10 text-4xl opacity-20 animate-float-book">🔐</div>
        <div class="absolute bottom-20 left-10 text-4xl opacity-20 animate-float-book [animation-delay:2s]">📧</div>
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

            <div class="flex flex-col items-center text-center mb-10">
                <div class="w-16 h-16 rounded-full bg-brand-primary/10 flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-brand-primary text-3xl">mail_lock</span>
                </div>
                <h1 class="text-3xl font-black text-slate-950 mb-2 font-heading tracking-tight">Quên mật khẩu?</h1>
                <p class="text-slate-500 font-medium max-w-xs">Nhập email của bạn và chúng tôi sẽ gửi liên kết đặt lại mật khẩu.</p>
            </div>

            {{-- Alerts --}}
            @if(session('status'))
                <div class="p-4 rounded-xl bg-green-50 border border-green-100 flex items-center gap-4 mb-6 animate-slide-in-left">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-green-600">mark_email_read</span>
                    </div>
                    <div>
                        <p class="text-sm font-black text-green-700">Email đã được gửi!</p>
                        <p class="text-xs text-green-600 font-medium">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-100 flex items-center gap-3 mb-6 animate-slide-in-left">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    <p class="text-sm font-bold text-red-600">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6" id="forgotForm">
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

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex gap-3">
                    <span class="material-symbols-outlined text-slate-400 text-[20px]">info</span>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                        Kiểm tra cả thư mục <strong class="text-slate-700">Spam / Junk</strong> nếu không thấy email trong vòng 2–3 phút.
                    </p>
                </div>

                <button type="submit" class="w-full py-4 bg-gradient-to-r from-brand-primary to-rose-600 text-white font-black rounded-2xl shadow-xl shadow-brand-primary/30 hover:shadow-2xl hover:shadow-brand-primary/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group/btn" id="submitBtn">
                    <span class="btn-text">Gửi liên kết khôi phục</span>
                    <span class="material-symbols-outlined transition-transform group-hover/btn:translate-x-1">send</span>
                    <div class="btn-loader hidden">
                         <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </button>
            </form>

            <div class="flex items-center justify-center gap-6 mt-10">
                <a href="{{ route('login') }}" class="flex items-center gap-1.5 text-sm font-black text-slate-600 hover:text-brand-primary transition-colors hover:underline underline-offset-4">
                    <span class="material-symbols-outlined !text-[18px]">arrow_back</span>
                    Quay lại đăng nhập
                </a>
                <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                <a href="{{ route('register') }}" class="text-sm font-black text-slate-600 hover:text-brand-primary transition-colors hover:underline underline-offset-4">Tạo tài khoản mới</a>
            </div>

        </div>

    </div>

</div>

@push('scripts')
    @vite('resources/js/auth/auth.js')
    <script>
        handleAuthFormSubmit('forgotForm', 'submitBtn', 'Đang gửi...');
    </script>
@endpush
@endsection



