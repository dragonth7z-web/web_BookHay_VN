@extends('layouts.app')

@section('content')
<!-- Sidebar Navigation -->
                <aside class="w-full md:w-64 flex flex-col gap-6">
                    <div
                        class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="size-12 rounded-full bg-[#C92127]/10 flex items-center justify-center text-[#C92127]">
                                <span class="material-symbols-outlined text-2xl">person</span>
                            </div>
                            <div class="flex flex-col">
                                <h1 class="text-slate-900 dark:text-white text-base font-bold leading-none">Nguyễn Văn A
                                </h1>
                                <p class="text-[#C92127] text-xs font-semibold mt-1">Thành viên Thân thiết</p>
                            </div>
                        </div>
                        <nav class="flex flex-col gap-1">
                            <a class="flex items-center gap-3 px-3 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                href="#">
                                <span class="material-symbols-outlined text-xl">account_circle</span>
                                <span class="text-sm font-medium">Hồ sơ cá nhân</span>
                            </a>
                            <a class="flex items-center gap-3 px-3 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                href="#">
                                <span class="material-symbols-outlined text-xl">package</span>
                                <span class="text-sm font-medium">Đơn hàng của tôi</span>
                            </a>
                            <a class="flex items-center gap-3 px-3 py-2.5 bg-[#C92127]/10 text-[#C92127] rounded-lg transition-colors"
                                href="#">
                                <span class="material-symbols-outlined text-xl fill">star</span>
                                <span class="text-sm font-bold">Đánh giá của tôi</span>
                            </a>
                            <a class="flex items-center gap-3 px-3 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                href="#">
                                <span class="material-symbols-outlined text-xl">location_on</span>
                                <span class="text-sm font-medium">Sổ địa chỉ</span>
                            </a>
                            <a class="flex items-center gap-3 px-3 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                href="#">
                                <span class="material-symbols-outlined text-xl">settings</span>
                                <span class="text-sm font-medium">Cài đặt tài khoản</span>
                            </a>
                        </nav>
                    </div>
                    <div class="bg-[#C92127]/5 p-4 rounded-xl border border-[#C92127]/20">
                        <p class="text-xs font-bold text-[#C92127] uppercase tracking-wider">Ưu đãi của bạn</p>
                        <p class="text-sm text-slate-700 dark:text-slate-300 mt-2">Bạn có 500 điểm thưởng. Đổi ngay mã
                            giảm giá 50k!</p>
                        <button
                            class="mt-3 w-full py-2 bg-[#C92127] text-white text-xs font-bold rounded-lg hover:bg-[#C92127]/90 transition-colors uppercase tracking-wide">Đổi
                            ngay</button>
                    </div>
                </aside>
                <!-- Content Area -->
                <section class="flex-1">
                    <div class="mb-8">
                        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Đánh giá của tôi
                        </h2>
                        <p class="text-slate-500 dark:text-slate-400 mt-2">Quản lý và xem lại tất cả các nhận xét sản
                            phẩm bạn đã mua.</p>
                    </div>
                    <!-- Tabs -->
                    <div class="flex border-b border-slate-200 dark:border-slate-800 mb-6 overflow-x-auto">
                        <button
                            class="px-6 py-3 border-b-2 border-[#C92127] text-[#C92127] text-sm font-bold whitespace-nowrap">Đã
                            đánh giá (12)</button>
                        <button
                            class="px-6 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 text-sm font-medium whitespace-nowrap">Chờ
                            đánh giá (3)</button>
                    </div>
                    <!-- Review List -->
                    <div class="flex flex-col gap-4">
                        <!-- Review Card 1 -->
                        <div
                            class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row gap-5">
                                <div
                                    class="w-24 h-32 flex-shrink-0 bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden shadow-sm">
                                    <img class="w-full h-full object-cover"
                                        data-alt="Bìa sách Đắc Nhân Tâm bản mới nhất"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCJrI3AqaDpXThoD-PjDUY0aqIXOI7-ef_wJGXvnoExJCOmBUvA00YmBK6opLgLEgX8iMyCRXgHNIqk7jQREAyioQS98MBn7GKKG0TqdyATt4Dj3fUU669AGGKb-Dzz-9umyEZwCQTRSDGYbgLLZrlQ9VFgh2_8qZsXmWwnqcan6WpMAF_KnSerIC-zv3s6lBF_vPv3daKRPpDIsUqEF_YMt1uGGOkq8CkpcGailqdJFLqn6ajRKMT4f3we_PHliS3X-qYmA-boB7k" />
                                </div>
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p
                                                    class="text-xs text-slate-400 font-medium mb-1 uppercase tracking-tighter">
                                                    Ngày đánh giá: 15/10/2023</p>
                                                <h3 class="text-base font-bold text-slate-900 dark:text-white">Đắc Nhân
                                                    Tâm (Bản đặc biệt)</h3>
                                            </div>
                                            <div class="flex text-[#C92127]">
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                            </div>
                                        </div>
                                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 line-clamp-2 italic">
                                            "Một cuốn sách gối đầu giường tuyệt vời. Nội dung vẫn còn nguyên giá trị
                                            theo thời gian. Giao hàng nhanh, đóng gói rất cẩn thận."</p>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2 items-center justify-between">
                                        <div class="flex gap-2">
                                            <div
                                                class="size-12 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                                <img class="w-full h-full object-cover rounded-lg"
                                                    data-alt="Ảnh chụp thật sản phẩm sách đóng gói"
                                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuD5Yau5dcsiZHDG0bTqjiz1YtT0-4mkhQdUQsfiT31vIGTQwUUQopZ7i8QlTnX2g2rS_Gt7C1cNWACOZCNr2I9ztkqK37tszSSe9SxmsPH-pdg4QLzl32h4g9ibkwZJ7KL7QU18U67E25dzmRUcDJN-F2k0g1GAkJf8fuUWBK-W6BUD-GZtu6lVO5TMmI2NEFlUevkyFD6E_t1x-LkA5TqjzkRKLzd7K2krRfKYuGO_Sch0MAe9MWT1YGiHtqIWIjZ91QaeELbNvoU" />
                                            </div>
                                            <div
                                                class="size-12 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                                <img class="w-full h-full object-cover rounded-lg"
                                                    data-alt="Ảnh chụp góc nghiêng cuốn sách"
                                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCErx4kOscrj98tmzx5srXGfQb9LQFloo7GCdnlekhUjj1diOYxkeDV4XCQe8qKleaGiZMIhZSQinBZFOqyDRTCge_opQLBl1b_lxuZ6_gAMSNGtb9b52WjODlE3R_dL3O2pAapGHRuCTJCHbJT6A2dT_tltKdyA4BEKko_edS3DQUlNHWND0fm3_ZiLYUllGfVkN6HsdE0VHIb9Dl-cEQrkEH_RDCVq6R95awlUU3crPa-DiNOm-RPRSKhB88-J61D27Bfw5-VZOM" />
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button
                                                class="flex items-center gap-1 px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                                Xóa
                                            </button>
                                            <button
                                                class="flex items-center gap-1 px-4 py-2 bg-[#C92127]/10 text-[#C92127] text-xs font-bold rounded-lg hover:bg-[#C92127]/20 transition-colors">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                                Chỉnh sửa
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Review Card 2 -->
                        <div
                            class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row gap-5">
                                <div
                                    class="w-24 h-32 flex-shrink-0 bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden shadow-sm">
                                    <img class="w-full h-full object-cover" data-alt="Bìa sách Tâm Lý Học Tội Phạm"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuAEV1ftoyqSSS2fvAQPih1pPif6snRAwYW1BqgDvExbWx6btl8FM4sMZw8SDYvxwnglJnDD1_tklDHek3MzGDEzIrohWYY65Q4kA-B8hTM3JcLkPhzLmOmB0JUwbXODO53jszWwjOFmYkvyVgLp7r_xt2lFAq3ZLvYRyiyEQZbaSx0YGtb-jsdeBj8OIIyk3MQRHwESk2XKLVF2bUWBVWVXnCHgMJOwoV96lqIT7GMUWL7GLNR1I3gfC1e8x3LC1atJONDkzZx5bKs" />
                                </div>
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p
                                                    class="text-xs text-slate-400 font-medium mb-1 uppercase tracking-tighter">
                                                    Ngày đánh giá: 02/09/2023</p>
                                                <h3 class="text-base font-bold text-slate-900 dark:text-white">Tâm Lý
                                                    Học Tội Phạm</h3>
                                            </div>
                                            <div class="flex text-[#C92127]">
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                                <span class="material-symbols-outlined text-sm">star</span>
                                            </div>
                                        </div>
                                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 line-clamp-2 italic">
                                            "Sách kiến thức chuyên sâu nhưng trình bày dễ hiểu. Tuy nhiên bìa sách hơi
                                            bị móp một chút ở góc do vận chuyển."</p>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2 items-center justify-between">
                                        <div class="flex gap-2">
                                            <span class="text-xs text-slate-500 italic">Không có ảnh đính kèm</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <button
                                                class="flex items-center gap-1 px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                                Xóa
                                            </button>
                                            <button
                                                class="flex items-center gap-1 px-4 py-2 bg-[#C92127]/10 text-[#C92127] text-xs font-bold rounded-lg hover:bg-[#C92127]/20 transition-colors">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                                Chỉnh sửa
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Review Card 3 -->
                        <div
                            class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 hover:shadow-md transition-shadow opacity-90">
                            <div class="flex flex-col sm:flex-row gap-5">
                                <div
                                    class="w-24 h-32 flex-shrink-0 bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden shadow-sm">
                                    <img class="w-full h-full object-cover" data-alt="Bìa tiểu thuyết Người Đua Diều"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuAPf21yfgMKKWWXtl9i0lumr3j6ysCp6Qhmhclrr3ZddEl_hokzwS5lNRWd7eayQqgVqUefBJmKUOGbf8MRjQWZAWv6_G2X7uPQJn8u6R005dEeP9zXQTCCiDqPhRvZ6ihXgQDIDuV36AlAXc5-q4RPT2MWyT2cCIUADPyL0nkQgnX1mmyDg0L_adHg0CTOZPoYU2A8A0hXlnD7mHJfsGePipXEiuBu3eDbw1MO20AQOqTXUGMazoejtdV3dYtNY0xesrhPBN5TpwU" />
                                </div>
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p
                                                    class="text-xs text-slate-400 font-medium mb-1 uppercase tracking-tighter">
                                                    Ngày đánh giá: 20/07/2023</p>
                                                <h3 class="text-base font-bold text-slate-900 dark:text-white">Người Đua
                                                    Diều</h3>
                                            </div>
                                            <div class="flex text-[#C92127]">
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                                <span class="material-symbols-outlined fill text-sm">star</span>
                                            </div>
                                        </div>
                                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 line-clamp-2 italic">
                                            "Câu chuyện đầy ám ảnh và xúc động. Một trong những tác phẩm hay nhất tôi
                                            từng đọc. Rất cảm ơn nhà sách THLD đã tư vấn."</p>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2 items-center justify-between">
                                        <div class="flex gap-2">
                                            <div
                                                class="size-12 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                                <img class="w-full h-full object-cover rounded-lg"
                                                    data-alt="Ảnh chụp bìa trước cuốn sách"
                                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuDv8_Lda8xMDL6Yief32e2iBHja4Mh_4T64QmpZYmLayV7OG_EQ_E9V0YiwjNpyn0Oi_3-VEpSQF5Crol_ORxxQnTXagh3mlbhvkXaBL1flJfpshv8atYidYabYnbsCLGXd0l3o0mKeygt5PxbekXn4_b1vUS9HcJvNddHC83glv7HykFsM4GbPa3uC2Qn7DgMWvIKXke0sr58EFi0O9_n5gLf25tpoSb4mdyLHhcKTgLOsM8tUXonI-IBypampTNb_Tb6NoeRfOvs" />
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button
                                                class="flex items-center gap-1 px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                                Xóa
                                            </button>
                                            <button
                                                class="flex items-center gap-1 px-4 py-2 bg-[#C92127]/10 text-[#C92127] text-xs font-bold rounded-lg hover:bg-[#C92127]/20 transition-colors">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                                Chỉnh sửa
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Load More -->
                        <div class="flex justify-center mt-6">
                            <button
                                class="px-8 py-2.5 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-bold rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all uppercase tracking-wide">
                                Xem thêm đánh giá
                            </button>
                        </div>
                    </div>
                </section>
@endsection

