@extends('layouts.auth')

@section('title', 'Đăng ký tài khoản - THLD Bookstore')

@section('content')
<div class="flex flex-col lg:flex-row-reverse min-h-screen bg-slate-50 overflow-x-hidden font-sans selection:bg-brand-primary/10 selection:text-brand-primary">

    {{-- ═══ RIGHT PANEL: Branding & Benefits ═══ --}}
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
                Tham gia <br> cộng đồng <span class="text-brand-primary">THLD.</span>
            </h2>
            <p class="text-white/50 text-lg leading-relaxed max-w-sm mb-10 font-medium">
                Đăng ký ngay để nhận ưu đãi độc quyền và khám phá kho tàng tri thức vô tận.
            </p>

            {{-- Benefits List --}}
            <div class="space-y-4 mb-10">
                @php
                    $benefits = [
                        ['icon' => 'local_offer', 'title' => 'Ưu đãi thành viên', 'desc' => 'Giảm giá đến 50% cho đơn hàng đầu tiên'],
                        ['icon' => 'local_shipping', 'title' => 'Miễn phí vận chuyển', 'desc' => 'Đơn hàng từ 200.000đ được miễn phí ship'],
                        ['icon' => 'stars', 'title' => 'Tích điểm thưởng', 'desc' => 'Mỗi đơn hàng tích lũy điểm đổi quà hấp dẫn'],
                        ['icon' => 'bookmark', 'title' => 'Tủ sách cá nhân', 'desc' => 'Lưu danh sách yêu thích, theo dõi đơn hàng'],
                    ];
                @endphp

                @foreach($benefits as $benefit)
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-brand-primary/20 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-brand-primary text-xl">{{ $benefit['icon'] }}</span>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-sm">{{ $benefit['title'] }}</h4>
                            <p class="text-white/40 text-xs mt-0.5">{{ $benefit['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Floating Decorations --}}
        <div class="absolute top-20 left-10 text-4xl opacity-20 animate-float-book">📚</div>
        <div class="absolute bottom-20 right-10 text-4xl opacity-20 animate-float-book [animation-delay:2s]">🌟</div>
    </div>

    {{-- ═══ LEFT PANEL: Auth Form ═══ --}}
    <div class="flex-1 flex flex-col items-center justify-center p-8 lg:p-12 xl:p-24 bg-white relative">
        
        {{-- Decorative Blob behind the form on mobile --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-primary/5 rounded-full blur-[100px] lg:hidden"></div>

        <div class="w-full max-w-[500px] relative z-10">
            
            {{-- Mobile Logo --}}
            <div class="flex lg:hidden items-center justify-center gap-3 mb-10 group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-primary to-orange-500 flex items-center justify-center shadow-lg shadow-brand-primary/20 transition-transform group-hover:scale-110">
                    <span class="material-symbols-outlined text-white">auto_stories</span>
                </div>
                <span class="text-2xl font-black tracking-tighter text-slate-950 font-heading">THLD BOOKSTORE</span>
            </div>

            <div class="mb-10">
                <h1 class="text-3xl font-black text-slate-950 mb-2 font-heading tracking-tight">Tạo tài khoản</h1>
                <p class="text-slate-500 font-medium">
                    Đã có tài khoản?
                    <a href="{{ route('login') }}" class="text-brand-primary font-black hover:underline underline-offset-4">Đăng nhập ngay</a>
                </p>
            </div>

            {{-- Alerts --}}
            @if(session('error') || $errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-100 flex items-center gap-3 mb-6 animate-slide-in-left">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    <p class="text-sm font-bold text-red-600">{{ session('error') ?? $errors->first() }}</p>
                </div>
            @endif

            <div id="errorBox" class="hidden p-4 rounded-xl bg-red-50 border border-red-200 mb-6">
              <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-red-500">error</span>
                <p class="text-sm font-bold text-red-600">Vui lòng kiểm tra lại thông tin:</p>
              </div>
              <ul class="list-disc list-inside space-y-1">
              </ul>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5" id="registerForm">
                @csrf

                {{-- Họ tên Field --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1" for="ho_ten">Họ và tên</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl transition-colors group-focus-within:text-brand-primary">person</span>
                        <input type="text" name="ho_ten" id="ho_ten" required autofocus value="{{ old('ho_ten') }}"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-slate-900 font-medium transition-all outline-none focus:border-brand-primary/30 focus:bg-white focus:ring-4 focus:ring-brand-primary/5 placeholder:text-slate-300"
                            placeholder="Nguyễn Văn A">
                    </div>
                    <span id="error-ho_ten" class="text-xs font-bold text-red-500 pl-1 mt-0.5 block"></span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Email Field --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1" for="email">Địa chỉ Email</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl transition-colors group-focus-within:text-brand-primary">mail</span>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-slate-900 font-medium transition-all outline-none focus:border-brand-primary/30 focus:bg-white focus:ring-4 focus:ring-brand-primary/5 placeholder:text-slate-300"
                                placeholder="example@thld.com">
                        </div>
                        <span id="error-email" class="text-xs font-bold text-red-500 pl-1 mt-0.5 block"></span>
                    </div>

                    {{-- Phone Field --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1" for="so_dien_thoai">Số điện thoại</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl transition-colors group-focus-within:text-brand-primary">phone</span>
                            <input type="tel" name="so_dien_thoai" id="so_dien_thoai" value="{{ old('so_dien_thoai') }}"
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-slate-900 font-medium transition-all outline-none focus:border-brand-primary/30 focus:bg-white focus:ring-4 focus:ring-brand-primary/5 placeholder:text-slate-300"
                                placeholder="091x xxx xxx">
                        </div>
                        <span id="error-so_dien_thoai" class="text-xs font-bold text-red-500 pl-1 mt-0.5 block"></span>
                    </div>
                </div>

                {{-- Password Fields --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1" for="password">Mật khẩu</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl transition-colors group-focus-within:text-brand-primary">lock</span>
                            <input type="password" name="password" id="password" required
                                class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-slate-900 font-medium transition-all outline-none focus:border-brand-primary/30 focus:bg-white focus:ring-4 focus:ring-brand-primary/5 placeholder:text-slate-300 [&::-ms-reveal]:hidden [&::-ms-clear]:hidden"
                                placeholder="••••••••">
                            <button type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 p-1 text-slate-300 hover:text-slate-500" onclick="togglePassword('password', this)" tabindex="-1">
                                <span class="material-symbols-outlined !text-[20px]">visibility</span>
                            </button>
                        </div>
                        <span id="error-password" class="text-xs font-bold text-red-500 pl-1 mt-0.5 block"></span>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1" for="password_confirmation">Xác nhận</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl transition-colors group-focus-within:text-brand-primary">lock_reset</span>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-slate-900 font-medium transition-all outline-none focus:border-brand-primary/30 focus:bg-white focus:ring-4 focus:ring-brand-primary/5 placeholder:text-slate-300 [&::-ms-reveal]:hidden [&::-ms-clear]:hidden"
                                placeholder="••••••••">
                            <button type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 p-1 text-slate-300 hover:text-slate-500" onclick="togglePassword('password_confirmation', this)" tabindex="-1">
                                <span class="material-symbols-outlined !text-[20px]">visibility</span>
                            </button>
                        </div>
                        <span id="error-password_confirmation" class="text-xs font-bold text-red-500 pl-1 mt-0.5 block"></span>
                    </div>
                </div>

                {{-- Password strength --}}
                <div class="hidden space-y-2 px-1" id="pwStrength">
                    <div class="flex gap-1.5">
                        <div class="flex-1 h-1.5 rounded-full bg-slate-100 transition-colors" id="bar1"></div>
                        <div class="flex-1 h-1.5 rounded-full bg-slate-100 transition-colors" id="bar2"></div>
                        <div class="flex-1 h-1.5 rounded-full bg-slate-100 transition-colors" id="bar3"></div>
                        <div class="flex-1 h-1.5 rounded-full bg-slate-100 transition-colors" id="bar4"></div>
                    </div>
                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Mức độ bảo mật: <span id="pwLabel" class="text-brand-primary">YẾU</span></p>
                </div>

                <div id="pwHints" class="hidden space-y-1 px-1 mt-1">
                  <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Gợi ý để mật khẩu mạnh hơn:</p>
                  <div id="hint-upper" class="text-xs text-slate-400">○ Có chữ hoa (A-Z)</div>
                  <div id="hint-lower" class="text-xs text-slate-400">○ Có chữ thường (a-z)</div>
                  <div id="hint-digit" class="text-xs text-slate-400">○ Có chữ số (0-9)</div>
                  <div id="hint-special" class="text-xs text-slate-400">○ Có ký tự đặc biệt (!@#$...)</div>
                </div>

                <label class="flex items-start gap-3 cursor-pointer group py-2">
                    <input type="checkbox" id="agreeTerms" class="hidden peer" required>
                    <div class="w-5 h-5 rounded-md border-2 border-slate-200 peer-checked:bg-brand-primary peer-checked:border-brand-primary flex items-center justify-center transition-all group-hover:border-brand-primary/50 mt-0.5">
                        <span class="material-symbols-outlined text-white text-sm scale-0 peer-checked:scale-100 transition-transform">check</span>
                    </div>
                    <span class="text-xs font-bold text-slate-500 leading-relaxed">
                        Tôi đồng ý với <a href="{{ route('pages.terms') }}" class="text-brand-primary hover:underline">Điều khoản dịch vụ</a> và <a href="{{ route('pages.privacy') }}" class="text-brand-primary hover:underline">Chính sách bảo mật</a> của THLD.
                    </span>
                </label>

                <button type="submit" class="w-full py-4 bg-gradient-to-r from-brand-primary to-rose-600 text-white font-black rounded-2xl shadow-xl shadow-brand-primary/30 hover:shadow-2xl hover:shadow-brand-primary/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group/btn opacity-50 cursor-not-allowed pointer-events-none" id="submitBtn" disabled>
                    <span class="btn-text">Bắt đầu hành trình ngay</span>
                    <span class="material-symbols-outlined transition-transform group-hover/btn:translate-x-1">person_add</span>
                    <div class="btn-loader hidden">
                         <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </button>
            </form>

        </div>

    </div>

</div>

@push('scripts')
    @vite('resources/js/auth/auth.js')
    <script>
        new RegisterValidator('registerForm').init();
        initPasswordStrength('password', 'pwStrength', ['bar1', 'bar2', 'bar3', 'bar4'], 'pwLabel');

        // ── Kiểm tra điều kiện để bật nút submit ──
        (function () {
            const btn      = document.getElementById('submitBtn');
            const fName    = document.getElementById('ho_ten');
            const fEmail   = document.getElementById('email');
            const fPhone   = document.getElementById('so_dien_thoai');
            const fPwd     = document.getElementById('password');
            const fPwdCfm  = document.getElementById('password_confirmation');
            const checkbox = document.getElementById('agreeTerms');

            const reEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const rePhone = /^(0[3|5|7|8|9])[0-9]{8}$/;

            function isValid() {
                const nameOk  = fName?.value.trim().length >= 2;
                const emailOk = reEmail.test(fEmail?.value.trim());
                const phoneOk = rePhone.test(fPhone?.value.trim());
                const pwdOk   = fPwd?.value.length >= 6;
                const cfmOk   = fPwdCfm?.value === fPwd?.value && fPwdCfm?.value !== '';
                const agreed  = checkbox?.checked;
                return nameOk && emailOk && phoneOk && pwdOk && cfmOk && agreed;
            }

            function updateBtn() {
                if (isValid()) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                } else {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                }
            }

            // Lắng nghe input trên tất cả các field
            [fName, fEmail, fPhone, fPwd, fPwdCfm].forEach(el => {
                el?.addEventListener('input', updateBtn);
            });

            // Checkbox hidden + peer — lắng nghe trên label để bắt click vào div custom
            checkbox?.closest('label')?.addEventListener('click', () => setTimeout(updateBtn, 0));
            checkbox?.addEventListener('change', updateBtn);

            updateBtn();
        })();
    </script>
@endpush
@endsection


