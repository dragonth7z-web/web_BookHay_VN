<div id="social-proof-toast"
    class="fixed bottom-8 left-8 z-[9999] p-4 bg-white/90 backdrop-blur-xl rounded-2xl shadow-brand border border-white/40 flex items-center gap-4 max-w-sm transform -translate-x-full opacity-0 transition-all duration-700 ease-out pointer-events-none">
    {{-- Product Image --}}
    <div
        class="w-16 h-16 rounded-xl bg-gray-50 flex-shrink-0 relative overflow-hidden flex items-center justify-center p-2 shadow-inner border border-gray-100">
        <img id="sp-img" src="https://placehold.co/100x150?text=Book" alt="Book"
            class="max-h-full object-contain drop-shadow-sm">
    </div>

    {{-- Content --}}
    <div class="flex-1">
        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1">Cập nhật đơn hàng</p>
        <p class="text-[13px] text-gray-800 font-semibold leading-tight line-clamp-2" id="sp-text">
            <span class="text-brand-primary font-black" id="sp-user">Anh Nam</span> vừa mua cuốn <span
                class="font-bold italic" id="sp-book">"Đắc Nhân Tâm"</span>
        </p>
        <div class="flex items-center gap-1.5 mt-1.5 text-[10px] text-gray-400 font-medium">
            <span class="material-symbols-outlined text-xs">history</span>
            <span id="sp-time">vừa xong</span>
        </div>
    </div>

    {{-- Close (invisible but keeps layout) --}}
    <div class="w-2"></div>
</div>

{{-- social-proof.js is loaded globally in layouts/app.blade.php --}}