@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative h-[500px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center"
            data-alt="Modern library interior with warm lighting and wooden shelves"
            style="background-image: linear-gradient(rgba(34, 22, 16, 0.7), rgba(34, 22, 16, 0.7)), url('https://lh3.googleusercontent.com/aida-public/AB6AXuCyV4o8auctRygkWYoKDJ9e-nXL-ta_YnBM5r2Lgvn_SWimuiwu-H5McWE7861FlZQV54Ezf78_TiqX4vgV36x6t13TmjJgsr3Sks-5Z0GBDtoCPcRuhmxgGTct8ErXA4aLDTfFsSPTiOsm4SmXgXIMdZTESmQbAaK_oIe2Wl5HWCK8btLE6DoJuZJAU3uNi-8VOE9YJe0ljVXcqQ0Lw_1R4slbvmGts1aZbaEF8Z6a6O5mO0UQhDIqBVFBC-upn0-oojQqbh38Qfo');">
        </div>
        <div class="relative z-10 text-center px-4 max-w-3xl">
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">Nuôi dưỡng tâm hồn qua từng
                trang sách</h1>
            <p class="text-lg md:text-xl text-slate-200 mb-8">Hơn 20 năm đồng hành cùng bạn đọc Việt Nam trên hành
                trình chinh phục tri thức.</p>
            <div class="flex justify-center gap-4">
                <div class="h-1 w-20 bg-[#C92127] rounded-full"></div>
            </div>
        </div>
    </section>
    <!-- Mission & Vision Section -->
    <section class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-black mb-6 text-slate-900 dark:text-slate-100">Sứ mệnh &amp; Tầm nhìn
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400 mb-8 text-lg">Chúng tôi tin rằng mỗi cuốn sách là
                        một cánh cửa mở ra thế giới mới. THLD không chỉ bán sách, chúng tôi kiến tạo không gian
                        văn hóa.</p>
                    <div class="space-y-6">
                        <div
                            class="flex gap-4 p-6 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                            <div class="bg-[#C92127]/10 p-3 rounded-lg h-fit">
                                <span class="material-symbols-outlined text-[#C92127]">auto_stories</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold mb-2">Sứ mệnh</h3>
                                <p class="text-slate-600 dark:text-slate-400">Cung cấp nguồn tri thức đa dạng, chất
                                    lượng cao và tạo dựng thói quen đọc sách bền vững cho cộng đồng người Việt.</p>
                            </div>
                        </div>
                        <div
                            class="flex gap-4 p-6 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                            <div class="bg-[#C92127]/10 p-3 rounded-lg h-fit">
                                <span class="material-symbols-outlined text-[#C92127]">visibility</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold mb-2">Tầm nhìn</h3>
                                <p class="text-slate-600 dark:text-slate-400">Trở thành hệ thống nhà sách trải
                                    nghiệm hàng đầu, nơi kết nối bạn đọc với tinh hoa văn hóa nhân loại thông qua
                                    công nghệ và dịch vụ tận tâm.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="aspect-square rounded-2xl overflow-hidden shadow-2xl">
                        <img alt="Team meeting" class="w-full h-full object-cover"
                            data-alt="Professional bookstore management team collaborating in modern office"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBconZy3Scdxek5WqLXe2Af2nv63jCTC2cXQKm3oF7x6QRQoVvuAB31390lYcAk0HXPTc8JIjmtsv1uAIjKwY8YHvZENtBuRBaccN29AfsIeIRq0kZMVKY2CNuzdpJl1tjoVmzrrxOTaRvds0CaPHv8vs_N2kudJWW8znVRWRJEdBScikRXt0legWKx4GhJVSlr4zjXU06_2JE2d7JPFdQ4B4iPZQtI2nrj5_fh5XRvJ7NJvAT9FjzFgU9WwW55iLQs-zs85piuJRs" />
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-[#C92127] p-8 rounded-2xl text-white hidden lg:block">
                        <p class="text-4xl font-black">20+</p>
                        <p class="text-sm font-medium uppercase tracking-wider">Năm kinh nghiệm</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- History Timeline -->
    <section class="py-20 bg-slate-100 dark:bg-slate-900/50 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black mb-4">Lịch sử hình thành</h2>
                <p class="text-slate-600 dark:text-slate-400">Chặng đường phát triển đầy tự hào của THLD</p>
            </div>
            <div
                class="relative space-y-12 before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 dark:before:via-slate-700 before:to-transparent">
                <!-- Item 1 -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-full border border-white dark:border-slate-900 bg-[#C92127] text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                        <span class="material-symbols-outlined text-sm">flag</span>
                    </div>
                    <div
                        class="w-[calc(100%-4rem)] md:w-[45%] p-6 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="flex items-center justify-between space-x-2 mb-1">
                            <div class="font-bold text-[#C92127]">2004</div>
                        </div>
                        <div class="text-slate-900 dark:text-slate-100 font-bold mb-1">Cửa hàng đầu tiên</div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm">Khai trương cửa hàng nhỏ đầu tiên tại
                            trung tâm TP.HCM với tâm huyết lan tỏa văn hóa đọc.</p>
                    </div>
                </div>
                <!-- Item 2 -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-full border border-white dark:border-slate-900 bg-[#C92127] text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                        <span class="material-symbols-outlined text-sm">hub</span>
                    </div>
                    <div
                        class="w-[calc(100%-4rem)] md:w-[45%] p-6 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="flex items-center justify-between space-x-2 mb-1">
                            <div class="font-bold text-[#C92127]">2012</div>
                        </div>
                        <div class="text-slate-900 dark:text-slate-100 font-bold mb-1">Mở rộng quy mô</div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm">Đạt mốc 10 chi nhánh trên toàn quốc và
                            bắt đầu hợp tác với các nhà xuất bản quốc tế.</p>
                    </div>
                </div>
                <!-- Item 3 -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-full border border-white dark:border-slate-900 bg-[#C92127] text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                        <span class="material-symbols-outlined text-sm">devices</span>
                    </div>
                    <div
                        class="w-[calc(100%-4rem)] md:w-[45%] p-6 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="flex items-center justify-between space-x-2 mb-1">
                            <div class="font-bold text-[#C92127]">2020</div>
                        </div>
                        <div class="text-slate-900 dark:text-slate-100 font-bold mb-1">Chuyển đổi số</div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm">Ra mắt nền tảng thương mại điện tử và
                            ứng dụng đọc sách điện tử hiện đại.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Our Space Gallery -->
    <section class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div>
                    <h2 class="text-3xl font-black mb-4">Không gian của chúng tôi</h2>
                    <p class="text-slate-600 dark:text-slate-400 max-w-xl">Khám phá không gian đọc sách chuyên
                        nghiệp, yên tĩnh và đầy cảm hứng tại các chi nhánh THLD.</p>
                </div>
                <button class="flex items-center gap-2 text-[#C92127] font-bold hover:underline">
                    Xem tất cả chi nhánh <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="col-span-2 row-span-2 relative group overflow-hidden rounded-2xl">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        data-alt="Main reading hall with high ceilings and rows of books"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuC-y0x1Aic_PUtJJE13r60p6wrEL_mvdoAzMVNFTPdXKZKj1jtCYOPFOahbvDqPtiHsRbcPY9gxgLg9AEF9F1ya8DJS5XX9qvwvQo4WYn5ZvPz4tw7YVgOHqdxeBDKSEHNe8ubr6tDQ--LYEl0L1kCnHu034x4NaqyPr0jvpKM4cY5x1HWHgfhR1kaBkI0kLgkcJ_R9iyEsCst9bSXPRoBgDQfeAOkegjvE25nuZ4h8W_8gU2WDHnyftVQzFcdzRZ2C1p7tD77J5D4" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-6">
                        <span class="text-white font-bold">Chi nhánh Trung tâm - Quận 1</span>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-2xl aspect-square">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        data-alt="Cozy reading nook with armchairs"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuAL3YWgwzHBzZaVx4YvY3iH0mAFxaiNhHr2JGHZJ3TufcDanTU-uv6VNSp-XyTpcXDc2-2bdexhtEZpIH76W2-w3wB8EpFqGXEHwKD6wBcd7MPOqsOoW7KBQn-s6o01c5MP-pvhiL17Qc9oN8wRjXxcz8998B5PcsL2Oa39hYcvIUqzlqjGOuK63K96iyoZIZBRiwcw2vG0w4btj8m9NGmZNZCFOI7AfCcidtuOL-KgxQkWqKAfsb0_hXgSMEZg6Dn4UfnydWgFfCI" />
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                </div>
                <div class="relative group overflow-hidden rounded-2xl aspect-square">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        data-alt="Close up of organized book shelves"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDlnaRoK_ACtdRNreYrTu3WVYiT2nZH-Qybxy3hpZZsMGJKXqG_XP3QIvdTcGh7R6e9nuWolaRqmT4kVaC_3w2aQRJQHgKH20F8zaOdehg5MrZECbRsI2uaa-KodUm9TOjfRm-j3kFK4I5506UZqC-I1jccRba3STa3eF3enaN2DXdQFfmBXSPRuYlgNqQlbAspZc8rXTDaO9SSoiNtSbI9FmLAKIMS1wjfPDiCUp_6aIRYu1Q2-dwuy-IFz9-i7-rS_SK7A3uVMaU" />
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                </div>
                <div class="col-span-2 relative group overflow-hidden rounded-2xl h-48">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        data-alt="Kids section with colorful furniture"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBjmYDYlBs3qtI06hjh8Nulti_lLF8XnWncUEmQekOh5ErjaPxwH_tRKxDVtUhhRPIa0tV7YoTGY05I6DUFjBQnk4v3fDkpxQtuO-kPI8xdUGAARGeZrul7V4RVWzo8Omz7Las7SLTr6989uY079N4ymsOV5KvTt5BgBizR_Ptj5ClaOAfQyrbv2tmMbm0igeFawduFeeDTEvAZaDnKIFOMajxySFpFh3G2JdwM_s7guOaSlsiKQxy9viKeQ9jS8nk-RTu1OtHmUTM" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
                        <span class="text-white font-bold text-sm">Khu vực dành cho thiếu nhi</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Newsletter / Contact -->
    <section class="py-20 px-4">
        <div
            class="max-w-7xl mx-auto bg-[#C92127] rounded-[2rem] p-8 md:p-16 text-center text-white relative overflow-hidden">
            <div
                class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-64 h-64 bg-white/10 rounded-full blur-3xl">
            </div>
            <div
                class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-64 h-64 bg-black/10 rounded-full blur-3xl">
            </div>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-black mb-6">Đồng hành cùng THLD</h2>
                <p class="text-white/80 max-w-2xl mx-auto mb-10 text-lg">Đăng ký nhận tin để không bỏ lỡ những đầu
                    sách mới nhất và các sự kiện giao lưu tác giả đặc sắc.</p>
                <form class="max-w-md mx-auto flex flex-col sm:flex-row gap-3">
                    <input class="flex-1 px-6 py-4 rounded-full text-slate-900 border-none focus:ring-2 focus:ring-white"
                        placeholder="Email của bạn" type="email" />
                    <button
                        class="bg-slate-900 text-white px-8 py-4 rounded-full font-bold hover:bg-slate-800 transition-colors"
                        type="submit">Gửi ngay</button>
                </form>
            </div>
        </div>
    </section>
@endsection
