<div
    class="footer-topbar hidden md:flex items-center justify-between px-4 py-2 text-white text-[11px] font-medium bg-brand-primary border-b border-brand-primary/20">
    <div class="max-w-main mx-auto w-full flex items-center justify-between gap-4">
        {{-- Left: promo text --}}
        <div class="flex items-center gap-2 overflow-hidden">
            <span class="material-symbols-outlined text-sm opacity-80">local_offer</span>
            <span class="whitespace-nowrap opacity-90">🎉 Premium e-commerce bookstore &nbsp;|&nbsp; Miễn phí giao hàng
                đơn từ 200K &nbsp;|&nbsp; Đổi trả 30 ngày &nbsp;|&nbsp; 100% bản quyền</span>
        </div>
        {{-- Right: order lookup + hotline --}}
        <div class="flex items-center gap-5 flex-shrink-0">
            <a class="flex items-center gap-1 hover:opacity-80 transition-opacity font-bold" href="#">
                <span class="material-symbols-outlined text-sm">local_shipping</span>
                <span>Tra cứu đơn</span>
                <span
                    class="ml-1 bg-white/20 rounded px-1.5 py-0.5 text-[10px] font-black tracking-wider">#4053127</span>
            </a>
            <a class="flex items-center gap-1 hover:opacity-80 transition-opacity" href="tel:19006034">
                <span class="material-symbols-outlined text-sm">phone_in_talk</span> 1900 6034
            </a>
        </div>
    </div>
</div>

{{-- No JS needed for static topbar component --}}