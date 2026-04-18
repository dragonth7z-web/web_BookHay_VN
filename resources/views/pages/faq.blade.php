@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="w-full bg-slate-100 dark:bg-slate-900/50 py-16 px-4">
        <div class="max-w-[800px] mx-auto text-center flex flex-col gap-6">
            <h1 class="text-slate-900 dark:text-white text-4xl md:text-5xl font-black leading-tight tracking-tight">
                Hỏi đáp thường gặp (FAQ)</h1>
            <p class="text-slate-600 dark:text-slate-400 text-lg">Tìm câu trả lời cho các thắc mắc của bạn về
                đơn hàng, vận chuyển và chính sách của THLD</p>
            <div class="relative w-full max-w-xl mx-auto mt-4">
                <label
                    class="flex items-center bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 focus-within:border-[#C92127] dark:focus-within:border-[#C92127] rounded-2xl h-14 px-4 shadow-sm transition-all">
                    <span class="material-symbols-outlined text-slate-400 mr-3">search</span>
                    <input
                        class="bg-transparent border-none focus:ring-0 w-full text-slate-900 dark:text-white placeholder:text-slate-400 font-medium"
                        placeholder="Tìm kiếm câu hỏi của bạn..." type="text" />
                </label>
            </div>
        </div>
    </div>
    <div class="w-full max-w-[960px] px-4 py-12 flex flex-col gap-12">
        <!-- Category: Giao hàng -->
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="size-10 rounded-full bg-[#C92127]/10 flex items-center justify-center text-[#C92127]">
                    <span class="material-symbols-outlined">local_shipping</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Giao hàng &amp; Vận chuyển</h2>
            </div>
            <div class="grid gap-3">
                <details
                    class="group border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900/40 overflow-hidden"
                    open="">
                    <summary class="flex cursor-pointer items-center justify-between p-5 list-none">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Thời gian giao hàng mất
                            bao lâu?</span>
                        <span
                            class="material-symbols-outlined transition-transform group-open:rotate-180 text-[#C92127]">expand_more</span>
                    </summary>
                    <div class="px-5 pb-5 pt-0 text-slate-600 dark:text-slate-400 leading-relaxed">
                        Thời gian giao hàng nội thành từ 1-2 ngày làm việc, các tỉnh thành khác từ 3-5 ngày làm
                        việc. Trong các dịp lễ Tết hoặc chương trình khuyến mãi lớn, thời gian có thể kéo dài
                        hơn một chút.
                    </div>
                </details>
                <details
                    class="group border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900/40 overflow-hidden">
                    <summary class="flex cursor-pointer items-center justify-between p-5 list-none">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Làm thế nào để theo dõi
                            đơn hàng của tôi?</span>
                        <span
                            class="material-symbols-outlined transition-transform group-open:rotate-180 text-[#C92127]">expand_more</span>
                    </summary>
                    <div class="px-5 pb-5 pt-0 text-slate-600 dark:text-slate-400 leading-relaxed">
                        Sau khi đơn hàng được gửi đi, bạn sẽ nhận được một mã vận đơn qua email hoặc tin nhắn
                        SMS. Bạn có thể sử dụng mã này để tra cứu trên website của đơn vị vận chuyển hoặc trực
                        tiếp trong phần "Lịch sử đơn hàng" trên THLD.
                    </div>
                </details>
            </div>
        </section>
        <!-- Category: Thanh toán -->
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="size-10 rounded-full bg-[#C92127]/10 flex items-center justify-center text-[#C92127]">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Thanh toán</h2>
            </div>
            <div class="grid gap-3">
                <details
                    class="group border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900/40 overflow-hidden">
                    <summary class="flex cursor-pointer items-center justify-between p-5 list-none">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Cửa hàng chấp nhận những
                            phương thức thanh toán nào?</span>
                        <span
                            class="material-symbols-outlined transition-transform group-open:rotate-180 text-[#C92127]">expand_more</span>
                    </summary>
                    <div class="px-5 pb-5 pt-0 text-slate-600 dark:text-slate-400 leading-relaxed">
                        Chúng tôi hỗ trợ đa dạng phương thức thanh toán: Thanh toán khi nhận hàng (COD), Chuyển
                        khoản ngân hàng, Thẻ tín dụng/ghi nợ (Visa, Mastercard) và các ví điện tử như MoMo,
                        ZaloPay.
                    </div>
                </details>
                <details
                    class="group border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900/40 overflow-hidden">
                    <summary class="flex cursor-pointer items-center justify-between p-5 list-none">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Tôi có thể nhận hóa đơn
                            VAT không?</span>
                        <span
                            class="material-symbols-outlined transition-transform group-open:rotate-180 text-[#C92127]">expand_more</span>
                    </summary>
                    <div class="px-5 pb-5 pt-0 text-slate-600 dark:text-slate-400 leading-relaxed">
                        Có, THLD cung cấp hóa đơn VAT cho mọi đơn hàng. Vui lòng tick vào ô "Yêu cầu hóa đơn
                        VAT" tại trang thanh toán và điền đầy đủ thông tin doanh nghiệp.
                    </div>
                </details>
            </div>
        </section>
        <!-- Category: Đổi trả -->
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="size-10 rounded-full bg-[#C92127]/10 flex items-center justify-center text-[#C92127]">
                    <span class="material-symbols-outlined">assignment_return</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Chính sách đổi trả</h2>
            </div>
            <div class="grid gap-3">
                <details
                    class="group border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900/40 overflow-hidden">
                    <summary class="flex cursor-pointer items-center justify-between p-5 list-none">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Điều kiện để được đổi trả
                            sản phẩm là gì?</span>
                        <span
                            class="material-symbols-outlined transition-transform group-open:rotate-180 text-[#C92127]">expand_more</span>
                    </summary>
                    <div class="px-5 pb-5 pt-0 text-slate-600 dark:text-slate-400 leading-relaxed">
                        Sản phẩm được đổi trả trong vòng 7 ngày kể từ khi nhận hàng nếu có lỗi từ nhà sản xuất,
                        hư hỏng do vận chuyển hoặc giao sai sản phẩm. Sách phải còn nguyên màng co (nếu có) và
                        chưa qua sử dụng.
                    </div>
                </details>
                <details
                    class="group border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900/40 overflow-hidden">
                    <summary class="flex cursor-pointer items-center justify-between p-5 list-none">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Quy trình hoàn tiền diễn
                            ra như thế nào?</span>
                        <span
                            class="material-symbols-outlined transition-transform group-open:rotate-180 text-[#C92127]">expand_more</span>
                    </summary>
                    <div class="px-5 pb-5 pt-0 text-slate-600 dark:text-slate-400 leading-relaxed">
                        Sau khi nhận được hàng trả về và kiểm tra điều kiện, chúng tôi sẽ tiến hành hoàn tiền
                        vào tài khoản của bạn trong vòng 3-5 ngày làm việc tùy thuộc vào phương thức thanh toán
                        ban đầu.
                    </div>
                </details>
            </div>
        </section>
        <!-- Contact Support Section -->
        <section class="bg-[#C92127]/5 dark:bg-[#C92127]/10 rounded-3xl p-8 text-center border border-[#C92127]/20">
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Vẫn còn thắc mắc?</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-6">Nếu bạn không tìm thấy câu trả lời, đừng ngần
                ngại liên hệ với đội ngũ hỗ trợ của chúng tôi.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <button
                    class="bg-[#C92127] hover:bg-[#C92127]/90 text-white font-bold py-3 px-8 rounded-xl transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">chat</span>
                    Chat ngay
                </button>
                <button
                    class="bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white font-bold py-3 px-8 rounded-xl border border-slate-200 dark:border-slate-700 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">mail</span>
                    Gửi Email
                </button>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/pages/faq.js')
@endpush
