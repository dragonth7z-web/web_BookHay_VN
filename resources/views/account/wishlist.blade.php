@extends('layouts.app')

@section('content')
<!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 mb-6">
                    <a class="text-slate-500 dark:text-slate-400 text-sm font-medium hover:text-[#C92127]" href="#">Tài
                        khoản</a>
                    <span class="material-symbols-outlined text-slate-400 text-sm">chevron_right</span>
                    <span class="text-[#C92127] text-sm font-bold">Danh sách yêu thích</span>
                </nav>
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
                    <div class="flex flex-col gap-1">
                        <h1 class="text-slate-900 dark:text-white text-3xl font-bold leading-tight">Sách yêu thích</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-base">Bạn có 12 cuốn sách trong danh sách quan
                            tâm</p>
                    </div>
                    <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
                        <button
                            class="px-4 py-2 rounded-lg bg-white dark:bg-slate-700 shadow-sm text-sm font-semibold text-[#C92127]">Tất
                            cả (12)</button>
                        <button
                            class="px-4 py-2 rounded-lg text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-[#C92127]">Đang
                            giảm giá (5)</button>
                    </div>
                </div>
                <!-- Product Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Book Item 1 -->
                    <div
                        class="group flex flex-col gap-4 p-4 bg-white dark:bg-slate-800/50 rounded-2xl border border-transparent hover:border-[#C92127]/20 hover:shadow-xl transition-all">
                        <div
                            class="relative w-full aspect-[3/4] overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                data-alt="Bìa sách Đắc Nhân Tâm bản dịch mới"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDRgXtqHcwITkZCQxCGUsx67kJcjqdzCS1b_wNo8K75RJRE89YW0O-zgAG9pI-EO1v5FbOOQ25SPlGKwKbdAwmwlEAWST3WfRLpscuGu71Sop9PVy1ZiMsr3s7HZVEI9H3RuNG1Ae-fiN7kN7xF_AuzUvAyawIqQP_ACrB6kLqJDHTa2HyGgFSsJ28ngaaG2lXZdjpe3THOkSdeuwIOvpyjYihzLsrZ8wmdIJqg2wb51xOzRsUbIwNBbgRMMSvdu3g3dxPuJSwrkLQ" />
                            <button
                                class="absolute top-3 right-3 size-8 rounded-full bg-white/90 dark:bg-slate-900/90 text-[#C92127] flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined font-fill-1 text-[20px]">favorite</span>
                            </button>
                        </div>
                        <div class="flex flex-col flex-1">
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Kỹ năng sống
                            </p>
                            <h3 class="text-slate-900 dark:text-white text-lg font-bold leading-snug mb-1 line-clamp-1">
                                Đắc Nhân Tâm</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mb-3">Dale Carnegie</p>
                            <div class="mt-auto flex flex-col gap-3">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-[#C92127] text-xl font-bold">86.000đ</span>
                                    <span class="text-slate-400 text-sm line-through">110.000đ</span>
                                </div>
                                <button
                                    class="w-full flex items-center justify-center gap-2 bg-[#C92127]/10 dark:bg-[#C92127]/20 text-[#C92127] hover:bg-[#C92127] hover:text-white transition-all py-2.5 rounded-xl font-bold text-sm">
                                    <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                                    Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Book Item 2 -->
                    <div
                        class="group flex flex-col gap-4 p-4 bg-white dark:bg-slate-800/50 rounded-2xl border border-transparent hover:border-[#C92127]/20 hover:shadow-xl transition-all">
                        <div
                            class="relative w-full aspect-[3/4] overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                data-alt="Bìa sách Nhà Giả Kim cổ điển"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuD8zOFs90mMMTBrZhSMjq7bpTfbvzrW2kfMhmUczv_fRwcr0FD2CFKMkDJJsGSMTEJRtQeEgKu21CUB8DSZvN_8MID9z0yau9x-jLhrH7tvu8kAgZ823skKiOORkdqeco1BkSbbY7XBfP5JTuZr1xkoZcNe-sYWQi13Flbfk5SgLE20KvjwSPuYUfstGigUPHXp226PZ5dvR0FSvnGuOeEDFGYdfcNEB9vW4lIUfuxusjh8825ibbyYMAAn1RWg9svWRFRXcEL9GPk" />
                            <button
                                class="absolute top-3 right-3 size-8 rounded-full bg-white/90 dark:bg-slate-900/90 text-[#C92127] flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined font-fill-1 text-[20px]">favorite</span>
                            </button>
                        </div>
                        <div class="flex flex-col flex-1">
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Văn học ngoại
                            </p>
                            <h3 class="text-slate-900 dark:text-white text-lg font-bold leading-snug mb-1 line-clamp-1">
                                Nhà Giả Kim</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mb-3">Paulo Coelho</p>
                            <div class="mt-auto flex flex-col gap-3">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-[#C92127] text-xl font-bold">79.000đ</span>
                                </div>
                                <button
                                    class="w-full flex items-center justify-center gap-2 bg-[#C92127]/10 dark:bg-[#C92127]/20 text-[#C92127] hover:bg-[#C92127] hover:text-white transition-all py-2.5 rounded-xl font-bold text-sm">
                                    <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                                    Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Book Item 3 -->
                    <div
                        class="group flex flex-col gap-4 p-4 bg-white dark:bg-slate-800/50 rounded-2xl border border-transparent hover:border-[#C92127]/20 hover:shadow-xl transition-all">
                        <div
                            class="relative w-full aspect-[3/4] overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                data-alt="Bìa sách Mắt Biếc nghệ thuật"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCR2Wdo6b38pul01V57Q79hl6ChaVcG_Sj-zk9mHWSlKcw3C5-2ELCTGDiZxpHsnvW7ArN_aUeG8amDYY9jOvbTP9uGZ1FCZENMPOu0OGZdGuSsR7gMIq5HRz7p4OgkaXBZjPS5SDNllOkna3QdhOne-aAQx1mQkoXnVP4U_BHR5IQwBgU-jw0sekT3B4HZ9knHwvDeYRZuLH6pQNwuVSnTcWnZOJl4AN0fwmjuigW-l1C_AnTpjm9OG2U-MFLRJyyimw6wAK9oQsc" />
                            <span
                                class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-md">HẾT
                                HÀNG</span>
                            <button
                                class="absolute top-3 right-3 size-8 rounded-full bg-white/90 dark:bg-slate-900/90 text-[#C92127] flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined font-fill-1 text-[20px]">favorite</span>
                            </button>
                        </div>
                        <div class="flex flex-col flex-1">
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Văn học Việt
                            </p>
                            <h3 class="text-slate-900 dark:text-white text-lg font-bold leading-snug mb-1 line-clamp-1">
                                Mắt Biếc</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mb-3">Nguyễn Nhật Ánh</p>
                            <div class="mt-auto flex flex-col gap-3">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-[#C92127] text-xl font-bold">110.000đ</span>
                                </div>
                                <button
                                    class="w-full flex items-center justify-center gap-2 bg-slate-100 dark:bg-slate-700 text-slate-400 cursor-not-allowed py-2.5 rounded-xl font-bold text-sm"
                                    disabled="">
                                    <span class="material-symbols-outlined text-[18px]">notifications</span>
                                    Nhận thông báo
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Book Item 4 -->
                    <div
                        class="group flex flex-col gap-4 p-4 bg-white dark:bg-slate-800/50 rounded-2xl border border-transparent hover:border-[#C92127]/20 hover:shadow-xl transition-all">
                        <div
                            class="relative w-full aspect-[3/4] overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                data-alt="Bìa sách Tuổi Trẻ Đáng Giá Bao Nhiêu"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuArVHVkNjTSm8Un82hiVFZejzQcYk48mmplJe02h8qcvd0hQZXmDMWpoteXKe6pU2JEmnTdOddfRW0viXhzFbCnTYmAih1Yx04SQgygxJNDgUdD1tCUQFWb-96Zj50g2BQ4HA24tAp9wnBcm-GmroPND5Po8WTyRqfuvgfhW8RecXEUq2dLHkPsYLksi0Oc0ZFDCrfK_1xJtycA2l7javA5_HZiVJmg4t2E70ltZ2DaGsucbqF0yQnpxftroqT7mgR7j4B-Ca24xqQ" />
                            <button
                                class="absolute top-3 right-3 size-8 rounded-full bg-white/90 dark:bg-slate-900/90 text-[#C92127] flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined font-fill-1 text-[20px]">favorite</span>
                            </button>
                        </div>
                        <div class="flex flex-col flex-1">
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Truyền cảm
                                hứng</p>
                            <h3 class="text-slate-900 dark:text-white text-lg font-bold leading-snug mb-1 line-clamp-1">
                                Tuổi Trẻ Đáng Giá Bao Nhiêu</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mb-3">Rosie Nguyễn</p>
                            <div class="mt-auto flex flex-col gap-3">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-[#C92127] text-xl font-bold">65.000đ</span>
                                    <span class="text-slate-400 text-sm line-through">85.000đ</span>
                                </div>
                                <button
                                    class="w-full flex items-center justify-center gap-2 bg-[#C92127]/10 dark:bg-[#C92127]/20 text-[#C92127] hover:bg-[#C92127] hover:text-white transition-all py-2.5 rounded-xl font-bold text-sm">
                                    <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                                    Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Empty State Suggestion (Optional but good UX) -->
                <div class="mt-16 p-8 rounded-3xl bg-[#C92127]/5 border border-dashed border-[#C92127]/20 text-center">
                    <span class="material-symbols-outlined text-[#C92127] text-5xl mb-4">explore</span>
                    <h3 class="text-slate-900 dark:text-white text-xl font-bold mb-2">Khám phá thêm nhiều đầu sách thú
                        vị</h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-md mx-auto">Chúng tôi luôn cập nhật những
                        tựa sách hot nhất mỗi tuần dựa trên sở thích của bạn.</p>
                    <a class="inline-flex items-center gap-2 bg-[#C92127] text-white px-8 py-3 rounded-xl font-bold hover:scale-105 transition-all"
                        href="#">
                        Xem sách mới về
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </a>
                </div>
@endsection

