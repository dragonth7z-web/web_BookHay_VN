<footer
    class="bg-gradient-to-b from-white to-gray-50 dark:from-slate-900 dark:to-slate-950 border-t border-gray-100 dark:border-slate-800 pt-20 pb-12 mt-12">
    {{-- Newsletter Banner --}}
    <div class="max-w-main mx-auto px-2 md:px-4">
        <div
            class="rounded-3xl p-8 md:p-10 bg-gradient-to-br from-red-100 via-red-200 to-red-300 dark:from-brand-primary/20 dark:via-brand-primary/15 dark:to-brand-primary/10 text-red-900 dark:text-red-100 flex flex-col md:flex-row items-center justify-between gap-8 flex-wrap relative overflow-hidden mb-10 shadow-[0_6px_20px_rgba(201,33,39,0.15)] border border-red-500/20">
            <div
                class="absolute -top-1/2 -right-[10%] w-[400px] h-[400px] rounded-full bg-black/5 dark:bg-white/5 pointer-events-none">
            </div>
            <div class="flex items-center gap-5 relative z-10">
                <div
                    class="w-12 h-12 rounded-2xl bg-white/90 dark:bg-slate-800/90 border border-brand-primary/30 flex items-center justify-center flex-shrink-0 shadow-brand">
                    <span
                        class="material-symbols-outlined text-2xl text-brand-primary dark:text-brand-primary/80">drafts</span>
                </div>
                <div>
                    <h4 class="font-heading text-xl font-black text-red-900 dark:text-red-100 mb-0.5"
                        style="font-family: var(--font-heading, 'Lora', serif);">Nhận Ngay Ưu Đãi 50%</h4>
                    <p class="text-sm text-red-800 dark:text-red-200 font-medium">Đăng ký email để nhận mã giảm giá và
                        thông tin sách mới bản quyền sớm nhất.</p>
                </div>
            </div>
            <form id="footer-newsletter-form"
                class="flex gap-2 bg-white/90 dark:bg-slate-800/90 border border-brand-primary/30 rounded-[4px] p-1.5 relative z-10 min-w-[400px] shadow-brand">
                <input
                    class="flex-1 py-2.5 px-4 rounded-[4px] border border-brand-primary/20 bg-white/80 dark:bg-slate-900/80 text-red-900 dark:text-white text-sm font-medium outline-none placeholder-red-600 dark:placeholder-red-400"
                    placeholder="Nhập địa chỉ email của bạn..." type="email" />
                <button
                    class="bg-brand-primary text-white font-extrabold text-xs py-2.5 px-6 rounded-[4px] border-none cursor-pointer whitespace-nowrap transition-all duration-300 hover:bg-brand-primary-dark hover:scale-105 shadow-brand"
                    type="submit">Đăng Ký</button>
            </form>
        </div>
    </div>

    {{-- Footer Links – 4 columns --}}
    <div class="max-w-main mx-auto px-2 md:px-4">
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12 mb-8 pb-8 border-b border-gray-100 dark:border-white/10">
            {{-- Column 1: Brand --}}
            <div>
                <div class="mb-6 inline-block">
                    <img src="{{ asset('images/logos/thanh_dieu_huong.png') }}" alt="THLD"
                        class="h-24 md:h-24 w-auto object-contain"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='inline-block'">
                    <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-[#f43f5e] tracking-tighter font-heading"
                        style="display:none">THLD</div>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-[13px] leading-relaxed mb-6 font-medium">
                    THLD tự hào là hệ thống nhà sách điện tử cao cấp, cung cấp 100% sách bản quyền với trải nghiệm mua
                    sắm tuyệt vời nhất.<br /><br />
                    <span class="flex items-center gap-2 text-gray-800 dark:text-gray-300">
                        <span class="material-symbols-outlined text-[18px] text-brand-primary">location_on</span> Lầu 5,
                        387-389 Hai Bà Trưng, Quận 3, TP.HCM
                    </span>
                    <span class="flex items-center gap-2 text-gray-800 dark:text-gray-300 mt-2">
                        <span class="material-symbols-outlined text-[18px] text-brand-primary">support_agent</span>
                        Hotline: <a href="tel:19006034" class="text-brand-primary font-bold hover:underline">1900
                            6034</a>
                    </span>
                </p>
                <div class="flex gap-3 mt-5">
                    <a class="w-10 h-10 rounded-[6px] bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 flex items-center justify-center transition-all duration-300 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_20px_rgba(0,0,0,0.1)] hover:-translate-y-1 text-[#1877F2]"
                        href="#" title="Facebook" target="_blank" rel="noopener noreferrer">
                        <span class="material-symbols-outlined text-xl">public</span>
                    </a>
                    <a class="w-10 h-10 rounded-[6px] bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 flex items-center justify-center transition-all duration-300 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_20px_rgba(0,0,0,0.1)] hover:-translate-y-1 text-[#000000] dark:text-white"
                        href="#" title="TikTok" target="_blank" rel="noopener noreferrer">
                        <span class="material-symbols-outlined text-xl">music_note</span>
                    </a>
                    <a class="w-10 h-10 rounded-[6px] bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 flex items-center justify-center transition-all duration-300 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_20px_rgba(0,0,0,0.1)] hover:-translate-y-1 text-[#0068FF]"
                        href="#" title="Zalo" target="_blank" rel="noopener noreferrer">
                        <span class="material-symbols-outlined text-xl">chat</span>
                    </a>
                    <a class="w-10 h-10 rounded-[6px] bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 flex items-center justify-center transition-all duration-300 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_20px_rgba(0,0,0,0.1)] hover:-translate-y-1 text-[#FF0000]"
                        href="#" title="YouTube" target="_blank" rel="noopener noreferrer">
                        <span class="material-symbols-outlined text-xl">play_arrow</span>
                    </a>
                </div>
            </div>

            {{-- Column 2: Về Chúng Tôi --}}
            <div>
                <h5
                    class="font-sans text-[13px] font-extrabold text-gray-900 dark:text-gray-100 mb-4 uppercase tracking-wider">
                    Về Chúng Tôi</h5>
                <ul
                    class="flex flex-col gap-2.5 m-0 p-0 list-none text-[13px] font-medium text-gray-500 dark:text-gray-400">
                    <li><a class="hover:text-brand-primary hover:translate-x-1 inline-block transition-all duration-200"
                            href="{{ route('pages.about') }}">Giới thiệu THLD</a></li>
                    <li><a class="hover:text-brand-primary hover:translate-x-1 inline-block transition-all duration-200"
                            href="{{ route('stores.index') }}">Hệ thống cửa hàng</a></li>
                    <li><a class="hover:text-brand-primary hover:translate-x-1 inline-block transition-all duration-200"
                            href="{{ route('pages.contact') }}">Tuyển dụng</a></li>
                    <li><a class="hover:text-brand-primary hover:translate-x-1 inline-block transition-all duration-200"
                            href="{{ route('pages.contact') }}">Tin tức & sự kiện</a></li>
                    <li><a class="hover:text-brand-primary hover:translate-x-1 inline-block transition-all duration-200"
                            href="{{ route('pages.contact') }}">Liên hệ hợp tác doanh nghiệp</a></li>
                </ul>
            </div>

            {{-- Column 3: Hỗ Trợ --}}
            <div>
                <h5
                    class="font-sans text-[13px] font-extrabold text-gray-900 dark:text-gray-100 mb-4 uppercase tracking-wider">
                    Hỗ Trợ Khách Hàng</h5>
                <ul
                    class="flex flex-col gap-2.5 m-0 p-0 list-none text-[13px] font-medium text-gray-500 dark:text-gray-400">
                    <li><a class="hover:text-brand-primary hover:translate-x-1 inline-block transition-all duration-200"
                            href="{{ route('pages.return') }}">Chính sách đổi trả 30 ngày</a></li>
                    <li><a class="hover:text-brand-primary hover:translate-x-1 inline-block transition-all duration-200"
                            href="{{ route('pages.shipping') }}">Chính sách vận chuyển</a></li>
                    <li><a class="hover:text-brand-primary hover:translate-x-1 inline-block transition-all duration-200"
                            href="{{ route('pages.about') }}">Phương thức thanh toán</a></li>
                    <li><a class="hover:text-brand-primary hover:translate-x-1 inline-block transition-all duration-200"
                            href="{{ route('pages.faq') }}">Câu hỏi thường gặp (FAQ)</a></li>
                    <li><a class="hover:text-brand-primary hover:translate-x-1 inline-block transition-all duration-200"
                            href="{{ route('pages.privacy') }}">Chính sách bảo mật dữ liệu</a></li>
                </ul>
            </div>

            {{-- Column 4: App Download --}}
            <div>
                <h5
                    class="font-sans text-[13px] font-extrabold text-gray-900 dark:text-gray-100 mb-4 uppercase tracking-wider">
                    Tải Trải Nghiệm Ứng Dụng</h5>
                <p class="text-[13px] text-gray-500 dark:text-gray-400 font-medium mb-6">Mua sắm tiện lợi hơn, nhận ngay
                    ưu đãi 50K cho đơn hàng đầu tiên qua App!</p>
                <div class="grid grid-cols-2 gap-4">
                    <a href="#"
                        class="flex items-center gap-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-2xl p-3 transition-all duration-300 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(0,0,0,0.1)] hover:border-brand-primary">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/6/67/App_Store_%28iOS%29.svg"
                            alt="App Store" class="w-8 h-8" />
                        <div class="leading-tight">
                            <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold">Download on</p>
                            <p class="font-black text-[13px] text-gray-900 dark:text-gray-100">App Store</p>
                        </div>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-2xl p-3 transition-all duration-300 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(0,0,0,0.1)] hover:border-brand-primary">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/Google_Play_Arrow_logo.svg"
                            alt="Google Play" class="w-8 h-8" />
                        <div class="leading-tight">
                            <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold">Get it on</p>
                            <p class="font-black text-[13px] text-gray-900 dark:text-gray-100">Google Play</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Bottom --}}
    <div class="max-w-main mx-auto px-2 md:px-4">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <p class="text-[13px] text-gray-500 dark:text-gray-400 font-medium">
                © 2026 <strong class="text-gray-900 dark:text-gray-100 font-bold">THLD Premium</strong>. Chuẩn mực mua
                sắm sách tuyệt đỉnh.<br>
                <span class="opacity-80">Giấy chứng nhận Đăng ký Kinh doanh số 031204xxxx do Sở KH&ĐT TP.HCM cấp.</span>
            </p>
            <div class="flex flex-col md:flex-row items-center gap-4">
                <div class="w-32 h-10 bg-contain bg-center bg-no-repeat opacity-60 hover:opacity-100 transition-opacity mr-4"
                    style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBPt8r8sP3sDAt1GiFxmCXf8Z5BYZrJrDOHhTIFifUF-CYWn7lLsXKPq0BPgCUZrqAfavPDFi0c0Z85zoqXb93ipBnAXM0JzeHzLZbupRFsHBMfAVge2LydQ152OtpDZHJvm3RSPtyev26iqqdnOXV7LjSRyvnMBM7nlEd2OLYrQd5d7No3IOrf3nVyZ7JAHv3mMBIBeOFpuOJLv3FnDndMsqO9U1KgZfZ9UTxsHKnou_hRGUqjMeOir5bpQgWiEgGLqQVPWBfhDTM');">
                </div>
                <div class="flex items-center gap-2">
                    <div
                        class="h-8 w-[50px] bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-lg flex items-center justify-center p-1.5 shadow-[0_1px_4px_rgba(0,0,0,0.04)] transition-all hover:shadow-[0_4px_12px_rgba(0,0,0,0.1)] group">
                        <img alt="Visa"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBUHJSoPSrrBuJ_k5S_8lKeOllAJieIofPrY9PrRVOepH43FzM808Kv7MS92AVsy-cX8Sxhz3uN70sap1WCuZo4KN4gkoJ8-nInJj2fCo7NBtfWZn4VHgWmBJWIbMw6cjPlHA2fFZTtVoEd9gs17pcUHLTHsR60IXMFR-ihWQxuqJgq1KHLhTaoA7ONJCgVKpJoGe7IwM9rLdhCuMZVNL_LXM6pENZ5TpuRknIG8APhrr0KBZLyAKVm9A1_hEWd59kzCtzgZafUpt4"
                            class="h-full w-auto object-contain filter grayscale-[0.4] opacity-80 transition-all duration-200 group-hover:grayscale-0 group-hover:opacity-100" />
                    </div>
                    <div
                        class="h-8 w-[50px] bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-lg flex items-center justify-center p-1.5 shadow-[0_1px_4px_rgba(0,0,0,0.04)] transition-all hover:shadow-[0_4px_12px_rgba(0,0,0,0.1)] group">
                        <img alt="Mastercard"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuABmqZkexqKoeoBxb9PpSwnAbMhaAPk3-XwntmD2wAGVluKEL8Zc34L2nyA3NMxeXF1TYQHdrjETF1qFMqcoY59LWeFhN-P4oXWjfoXK3VLB84ervBRAsoj0CxKidCLSarX10w6c5Te8sQ5AL98mU8t2jKzfMITx7Quf9OCw-MG2EOdws_LA8hiBUCvViY4Qi5F3DFfw1l_-lIdl1bcGxw5DmY30InU81fJQotPRIsNoWUjtBvS6KJrc6by86Lu5kIn99ypt4ONKb0"
                            class="h-full w-auto object-contain filter grayscale-[0.4] opacity-80 transition-all duration-200 group-hover:grayscale-0 group-hover:opacity-100" />
                    </div>
                    <div
                        class="h-8 w-[50px] bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-lg flex items-center justify-center p-1.5 shadow-[0_1px_4px_rgba(0,0,0,0.04)] transition-all hover:shadow-[0_4px_12px_rgba(0,0,0,0.1)] group">
                        <img alt="MoMo"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDiVn8qDlQyWGqLb0hKNSVPc54tXinAiuoS2WtC8rYCTf_8x_nY93Redfx8TebAUVGs_0IovDh21EyUI4Cx5s-IDaMtt42-T74VGHBEvGTja-wdbxXeMQ71ss9Iq-OvUMtHbSjrm6z48oTCveEuloQrWXRhYH4RnBzUye2T2Lu1LM8HUrIRRxPheXBCTvCLsOCNYO9f8fnUboYpzDb_UohsDNHEKY8MvH8vAIXEmS_y4dye4NdDqcUZar4TzVjrvdDJzFqWLXwkoao"
                            class="h-full w-auto object-contain filter grayscale-[0.4] opacity-80 transition-all duration-200 group-hover:grayscale-0 group-hover:opacity-100" />
                    </div>
                    <div
                        class="h-8 w-[50px] bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-lg flex items-center justify-center p-1.5 shadow-[0_1px_4px_rgba(0,0,0,0.04)] transition-all hover:shadow-[0_4px_12px_rgba(0,0,0,0.1)] group">
                        <img alt="VNPAY"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDjJg_EVFO8nXCA2YoeFF9GwljwzuTWud0ZEvTN3GnMRj1MtqPrVS4rz2B86_rxVvl1GvRgTr7c5GDmKi2zr-EkYGl4UmX-d4JwCO5lKflE8xDAtP_ED8HAONjmIaDBdgTmEJPmVlI3YOqmI-FmJYMf2ab_BSk7j6Jbg7aq96XkYmP9NNcrlmJBY2KhM5AiCglFH-AxvvQI9ODnVPlsgEKPZ1troyAiGHZKXBiMlaqzwjeY5GT3fajF61L-Rf6XoB7-lN0Jkf0rt0g"
                            class="h-full w-auto object-contain filter grayscale-[0.4] opacity-80 transition-all duration-200 group-hover:grayscale-0 group-hover:opacity-100" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
