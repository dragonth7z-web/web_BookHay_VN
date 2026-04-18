@extends('layouts.app')

@section('title', 'Thông tin cá nhân - THLD')

@section('content')
<div class="min-h-screen bg-[#F0F2F5] py-8">
    <div class="max-w-7xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6 min-h-[600px]">

            {{-- Sidebar --}}
            <aside class="w-full lg:w-64 flex-shrink-0">
                {{-- Profile info --}}
                <div class="flex items-center gap-4 mb-6 px-2">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary bg-gray-100">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=C92127&color=fff" alt="Avatar" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tài khoản của</p>
                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="space-y-1">
                    <a href="{{ route('account.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold bg-primary text-white shadow-[0_6px_20px_rgba(201,33,39,0.25)] transition-all duration-200">
                        <span class="material-symbols-outlined text-xl">person</span>
                        <span>Thông tin cá nhân</span>
                    </a>
                    <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">package_2</span>
                        <span>Đơn hàng của tôi</span>
                    </a>
                    <a href="{{ route('account.wishlist') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">favorite</span>
                        <span>Sách yêu thích</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">location_on</span>
                        <span>Sổ địa chỉ</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 font-medium transition-all duration-200 hover:bg-gray-50 hover:text-primary">
                        <span class="material-symbols-outlined text-xl">confirmation_number</span>
                        <span>Kho Voucher</span>
                    </a>
                    <div class="pt-4 mt-4 border-t border-gray-100">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">@csrf</form>
                        <a href="javascript:void(0)" onclick="document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-red-500 font-medium transition-all duration-200 hover:bg-red-50 hover:text-red-600">
                            <span class="material-symbols-outlined text-xl">logout</span>
                            <span>Đăng xuất</span>
                        </a>
                    </div>
                </nav>
            </aside>

            {{-- Main Content --}}
            <section class="flex-1 space-y-6">
                {{-- Profile Form --}}
                <div class="bg-white rounded-[18px] border border-gray-200 overflow-hidden shadow-[0_2px_12px_rgba(0,0,0,0.04)]">
                    <div class="px-7 py-5 border-b border-gray-100">
                        <h1 class="text-xl font-bold text-gray-900">Hồ sơ của tôi</h1>
                        <p class="text-sm text-gray-500 mt-0.5">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                    </div>
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row gap-10">
                            {{-- Form --}}
                            <div class="flex-1 order-2 md:order-1">
                                <form class="space-y-5" method="POST" action="{{ route('account.profile.update') }}">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                        <label class="text-sm font-medium text-gray-600 md:text-right">Họ và tên</label>
                                        <div class="md:col-span-3">
                                            <input type="text" name="name" class="w-full px-3.5 py-2.5 border-[1.5px] border-gray-200 rounded-xl text-sm outline-none transition-all duration-200 bg-white focus:border-primary focus:ring-[3px] focus:ring-primary/10" value="{{ old('name', $user->name) }}">
                                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                        <label class="text-sm font-medium text-gray-600 md:text-right">Email</label>
                                        <div class="md:col-span-3 flex items-center gap-2">
                                            @php
                                                $emailParts = explode('@', $user->email);
                                                $maskedEmail = substr($emailParts[0], 0, 3) . '***@' . ($emailParts[1] ?? '');
                                            @endphp
                                            <span class="text-sm text-gray-900">{{ $maskedEmail }}</span>
                                            <button class="text-primary text-xs font-semibold underline" type="button">Thay đổi</button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                        <label class="text-sm font-medium text-gray-600 md:text-right">Số điện thoại</label>
                                        <div class="md:col-span-3 flex items-center gap-2">
                                            <span class="text-sm text-gray-900">{{ $user->phone ? '*******' . substr($user->phone, -3) : 'Chưa cập nhật' }}</span>
                                            <button class="text-primary text-xs font-semibold underline" type="button">Thay đổi</button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                        <label class="text-sm font-medium text-gray-600 md:text-right">Giới tính</label>
                                        <div class="md:col-span-3 flex gap-5">
                                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                                <input type="radio" name="gender" value="male" {{ old('gender', $user->gender?->value) === 'male' ? 'checked' : '' }} class="text-primary focus:ring-primary"> Nam
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                                <input type="radio" name="gender" value="female" {{ old('gender', $user->gender?->value) === 'female' ? 'checked' : '' }} class="text-primary focus:ring-primary"> Nữ
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                                <input type="radio" name="gender" value="other" {{ old('gender', $user->gender?->value) === 'other' ? 'checked' : '' }} class="text-primary focus:ring-primary"> Khác
                                            </label>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                        <label class="text-sm font-medium text-gray-600 md:text-right">Ngày sinh</label>
                                        <div class="md:col-span-3 flex gap-3">
                                            @php
                                                $dob = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth) : null;
                                                $day = $dob ? $dob->day : null;
                                                $month = $dob ? $dob->month : null;
                                                $year = $dob ? $dob->year : null;
                                            @endphp
                                            <select name="dob_day" class="flex-1 w-full px-3.5 py-2.5 border-[1.5px] border-gray-200 rounded-xl text-sm outline-none transition-all duration-200 bg-white focus:border-primary focus:ring-[3px] focus:ring-primary/10">
                                                <option value="">Ngày</option>
                                                @for($i = 1; $i <= 31; $i++)
                                                    <option value="{{ $i }}" {{ (old('dob_day', $day) == $i) ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <select name="dob_month" class="flex-1 w-full px-3.5 py-2.5 border-[1.5px] border-gray-200 rounded-xl text-sm outline-none transition-all duration-200 bg-white focus:border-primary focus:ring-[3px] focus:ring-primary/10">
                                                <option value="">Tháng</option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ (old('dob_month', $month) == $i) ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <select name="dob_year" class="flex-1 w-full px-3.5 py-2.5 border-[1.5px] border-gray-200 rounded-xl text-sm outline-none transition-all duration-200 bg-white focus:border-primary focus:ring-[3px] focus:ring-primary/10">
                                                <option value="">Năm</option>
                                                @for($i = date('Y'); $i >= 1920; $i--)
                                                    <option value="{{ $i }}" {{ (old('dob_year', $year) == $i) ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    
                                    {{-- Hidden field to consolidate DOB for controller --}}
                                    <input type="hidden" name="date_of_birth" id="date_of_birth" value="{{ $user->date_of_birth }}">

                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center pt-2">
                                        <div></div>
                                        <div class="md:col-span-3">
                                            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition-all shadow-lg shadow-red-200">
                                                Lưu thay đổi
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- Avatar --}}
                            <div class="w-full md:w-56 flex flex-col items-center gap-4 order-1 md:order-2 md:border-l border-gray-100 md:pl-10">
                                <div class="relative w-32 h-32 rounded-full overflow-hidden border-4 border-gray-100 mx-auto">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=C92127&color=fff&size=128" alt="Avatar" class="w-full h-full object-cover">
                                    @endif
                                    <button class="absolute bottom-0 right-0 w-9 h-9 bg-white rounded-full flex items-center justify-center border-2 border-gray-200 text-primary cursor-pointer transition-all duration-200 hover:bg-primary hover:text-white">
                                        <span class="material-symbols-outlined text-base">photo_camera</span>
                                    </button>
                                </div>
                                <div class="text-center space-y-2">
                                    <button class="px-5 py-2 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50">Chọn ảnh</button>
                                    <p class="text-xs text-gray-400">Dung lượng tối đa 1 MB<br>.JPEG, .PNG</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Security Section --}}
                <div class="bg-white rounded-[18px] border border-gray-200 overflow-hidden shadow-[0_2px_12px_rgba(0,0,0,0.04)]">
                    <div class="px-7 py-5 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900">Bảo mật tài khoản</h2>
                    </div>
                    <div class="p-6">
                        {{-- Change password --}}
                        <div class="flex items-center justify-between py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">lock</span>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Đổi mật khẩu</p>
                                    <p class="text-xs text-gray-500">Nên sử dụng mật khẩu mạnh để bảo vệ tài khoản</p>
                                </div>
                            </div>
                            <button class="px-5 py-2 border-2 border-primary text-primary font-bold rounded-xl hover:bg-red-50 transition-all text-sm">Cập nhật</button>
                        </div>

                        {{-- 2FA --}}
                        <div class="flex items-center justify-between py-4 border-t border-gray-100">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                                    <span class="material-symbols-outlined">security</span>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Xác thực 2 yếu tố (2FA)</p>
                                    <p class="text-xs text-gray-500">Tăng thêm một lớp bảo mật cho tài khoản</p>
                                </div>
                            </div>
                            <label class="relative inline-block w-12 h-6 cursor-pointer">
                                <input type="checkbox" class="peer hidden" onchange="this.parentElement.nextElementSibling?.classList.toggle('on')">
                                <div class="absolute inset-0 bg-slate-200 dark:bg-slate-700 rounded-full transition-all peer-checked:bg-green-500 after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const daySel = document.querySelector('select[name="dob_day"]');
        const monthSel = document.querySelector('select[name="dob_month"]');
        const yearSel = document.querySelector('select[name="dob_year"]');
        const hiddenDob = document.getElementById('date_of_birth');

        function updateHiddenDob() {
            if (daySel.value && monthSel.value && yearSel.value) {
                hiddenDob.value = `${yearSel.value}-${monthSel.value.padStart(2, '0')}-${daySel.value.padStart(2, '0')}`;
            } else {
                hiddenDob.value = '';
            }
        }

        [daySel, monthSel, yearSel].forEach(sel => sel.addEventListener('change', updateHiddenDob));
    });
</script>
@endsection

