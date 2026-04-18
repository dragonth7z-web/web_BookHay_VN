@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="w-full max-w-[1200px] px-4 md:px-10 py-6 lg:py-10">
        <div
            class="relative overflow-hidden rounded-xl h-[280px] md:h-[350px] bg-slate-200 flex items-center justify-center">
            <div class="absolute inset-0 bg-cover bg-center" data-alt="Bên trong một hiệu sách hiện đại ấm cúng"
                style="background-image: linear-gradient(to right, rgba(0,0,0,0.7), rgba(0,0,0,0.3)), url('https://lh3.googleusercontent.com/aida-public/AB6AXuDEk95rbg3IrCvPq4fg76La_Iojj6E8C4n-xv-YnuaZ9ZT_gcYDvCqiUaga6Skt20wq4Oi9y56u2sHIYtUStC2ra22FR_jYiXVmNZ_ksPXpgOqMPi3dzQ82uFK2C-WO3gVgu83Uz3FXKbHIi_4RnVqsYyxj_yWi80q7QXgblcjlhQzdgZCaPBopWtfoVTGiNm6aBB1QAUN6GJgBYXF8A47Domi7HgDhw62TO-Y6Ot4rjF2SXu_Yue-7vPWShBM8XV_XNsNbdaG2-fM');">
            </div>
            <div class="relative z-10 text-center px-4">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Liên hệ với THLD</h1>
                <p class="text-slate-200 text-lg max-w-2xl mx-auto">Chúng tôi luôn sẵn sàng lắng nghe và
                    giải đáp mọi thắc mắc của bạn về thế giới tri thức.</p>
            </div>
        </div>
    </div>
    <!-- Main Content Grid -->
    <div class="w-full max-w-[1200px] grid grid-cols-1 lg:grid-cols-2 gap-12 px-4 md:px-10 pb-20">
        <!-- Contact Form Column -->
        <div class="bg-white dark:bg-slate-900/50 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <h2 class="text-2xl font-bold mb-6 text-slate-900 dark:text-slate-100">Gửi tin nhắn cho chúng
                tôi</h2>
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Họ và
                            tên</label>
                        <input
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent px-4 py-3 focus:border-[#C92127] focus:ring-1 focus:ring-[#C92127] outline-none transition-all"
                            placeholder="Nguyễn Văn A" type="text" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Email</label>
                        <input
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent px-4 py-3 focus:border-[#C92127] focus:ring-1 focus:ring-[#C92127] outline-none transition-all"
                            placeholder="example@gmail.com" type="email" />
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Số điện
                        thoại</label>
                    <input
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent px-4 py-3 focus:border-[#C92127] focus:ring-1 focus:ring-[#C92127] outline-none transition-all"
                        placeholder="0901 234 567" type="tel" />
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Chủ đề</label>
                    <select
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent px-4 py-3 focus:border-[#C92127] focus:ring-1 focus:ring-[#C92127] outline-none transition-all">
                        <option>Hỗ trợ đặt hàng</option>
                        <option>Góp ý dịch vụ</option>
                        <option>Hợp tác kinh doanh</option>
                        <option>Khác</option>
                    </select>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nội dung tin
                        nhắn</label>
                    <textarea
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent px-4 py-3 focus:border-[#C92127] focus:ring-1 focus:ring-[#C92127] outline-none transition-all resize-none"
                        placeholder="Nhập nội dung bạn muốn gửi..." rows="5"></textarea>
                </div>
                <button
                    class="w-full bg-[#C92127] hover:bg-[#C92127]/90 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-primary/20"
                    type="submit">
                    Gửi yêu cầu ngay
                </button>
            </form>
        </div>
        <!-- Information Column -->
        <div class="flex flex-col gap-8">
            <div>
                <h2 class="text-2xl font-bold mb-6 text-slate-900 dark:text-slate-100">Thông tin liên hệ
                </h2>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div
                            class="flex-shrink-0 size-12 bg-[#C92127]/10 rounded-xl flex items-center justify-center text-[#C92127]">
                            <span class="material-symbols-outlined">location_on</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-slate-100">Địa chỉ trụ sở</p>
                            <p class="text-slate-600 dark:text-slate-400">123 Đường Sách, Phường Bến Nghé,
                                Quận 1, TP. Hồ Chí Minh</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div
                            class="flex-shrink-0 size-12 bg-[#C92127]/10 rounded-xl flex items-center justify-center text-[#C92127]">
                            <span class="material-symbols-outlined">call</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-slate-100">Hotline hỗ trợ</p>
                            <p class="text-[#C92127] font-semibold text-lg">1900 6789</p>
                            <p class="text-slate-500 text-sm italic">(Thứ 2 - Chủ Nhật, 8:00 - 21:00)</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div
                            class="flex-shrink-0 size-12 bg-[#C92127]/10 rounded-xl flex items-center justify-center text-[#C92127]">
                            <span class="material-symbols-outlined">mail</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-slate-100">Email liên hệ</p>
                            <p class="text-slate-600 dark:text-slate-400">contact@bookth.com.vn</p>
                            <p class="text-slate-600 dark:text-slate-400">support@bookth.com.vn</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Map Section -->
            <div
                class="flex-1 min-h-[300px] w-full rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 grayscale hover:grayscale-0 transition-all duration-500 shadow-md">
                <div class="w-full h-full bg-slate-300 flex items-center justify-center relative">
                    <img alt="Bản đồ vị trí" class="w-full h-full object-cover"
                        data-alt="Bản đồ thành phố Hồ Chí Minh phong cách tối giản" data-location="Ho Chi Minh City"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuD7RSrZZhyzAob2j7dWGUYfBNvogiWq93mB7OiQuHr_K0BlbRbYhFCkpX0Z6MHqeBc5PaReLoXxFyTwP7SqgYUKApRpJN52yyzj_JTu5xA4h-V2HQVuNaR3uEVAIwEmHMuHrDCb-nCIpEFfR7_Z_acAhK6enlEMxiXUvyt0OwZxw8xNkJyFUWWB3zenn5flHkjlxli_B_qrP23LjUF4hTwgFPuaA__DRmigNUn0WoNOxnHTxv7DcnwWAjsHoZtSRxHMY4IOVE-sx7Y" />
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="bg-[#C92127] p-4 rounded-full text-white shadow-2xl animate-bounce">
                            <span class="material-symbols-outlined text-3xl">location_on</span>
                        </div>
                    </div>
                    <div
                        class="absolute bottom-4 left-4 right-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md p-3 rounded-lg flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Mở trong Google
                            Maps</span>
                        <a class="text-xs font-bold text-[#C92127] flex items-center gap-1 uppercase tracking-wider"
                            href="#">Xem ngay <span class="material-symbols-outlined text-xs">open_in_new</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FAQ/Notice Banner -->
    <div class="w-full bg-[#C92127]/5 dark:bg-[#C92127]/10 py-16">
        <div class="max-w-[1200px] mx-auto px-4 md:px-10 text-center">
            <h3 class="text-2xl font-bold mb-4">Bạn cần hỗ trợ ngay lập tức?</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-8 max-w-xl mx-auto">Trước khi gửi tin nhắn, hãy
                thử kiểm tra trang Câu hỏi thường gặp để tìm câu trả lời nhanh nhất cho vấn đề của bạn.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a class="px-8 py-3 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 font-bold rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all"
                    href="#">Xem trang FAQ</a>
                <a class="px-8 py-3 bg-[#C92127] text-white font-bold rounded-xl hover:shadow-lg transition-all"
                    href="#">Chat qua Zalo</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/pages/contact.js')
@endpush
