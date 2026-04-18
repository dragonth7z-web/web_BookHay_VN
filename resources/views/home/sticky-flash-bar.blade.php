@if(isset($activeFlashSale) && $activeFlashSale)
<div id="sticky-flash-bar" class="fixed left-0 w-full z-[45] bg-gradient-to-r from-brand-primary to-orange-500 text-white shadow-lg transition-all duration-300 flex items-center justify-center py-2.5 px-4 cursor-pointer hover:from-brand-primary-dark hover:to-orange-600 -translate-y-full opacity-0 invisible" onclick="document.getElementById('flash-sale')?.scrollIntoView({behavior: 'smooth'})">
    <div class="max-w-main mx-auto w-full flex items-center justify-between gap-4 font-medium text-sm">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined font-black animate-[fsBoltPulse_1.5s_infinite_alternate]">bolt</span>
            <span class="font-black tracking-widest hidden sm:inline uppercase">Siêu Flash Sale</span>
            <span class="font-black tracking-widest sm:hidden uppercase">Flash Sale</span>
        </div>
        <div class="flex items-center gap-2 md:gap-4">
            <span class="text-xs uppercase tracking-widest opacity-90 hidden md:inline font-bold">Kết thúc trong:</span>
            <div class="flex items-center gap-1 font-black text-sm" id="fs-sticky-timer">
                <span class="bg-black/30 backdrop-blur-md px-2 py-0.5 rounded shadow-inner" id="sfs-hours">00</span>
                <span class="animate-pulse">:</span>
                <span class="bg-black/30 backdrop-blur-md px-2 py-0.5 rounded shadow-inner" id="sfs-minutes">00</span>
                <span class="animate-pulse">:</span>
                <span class="bg-black/30 backdrop-blur-md px-2 py-0.5 rounded shadow-inner" id="sfs-seconds">00</span>
            </div>
            <button class="ml-2 bg-white text-red-600 px-4 py-1.5 rounded-full text-xs font-black shadow-md hover:scale-105 transition-all uppercase tracking-tighter hover:bg-red-50">Săn Ngay</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const endDateString = document.querySelector('meta[name="flash-sale-end"]')?.content;
        if (!endDateString) return;
        const endDate = new Date(endDateString).getTime();

        const hEl = document.getElementById('sfs-hours');
        const mEl = document.getElementById('sfs-minutes');
        const sEl = document.getElementById('sfs-seconds');

        function updateStickyTimer() {
            const now = new Date().getTime();
            const diff = endDate - now;
            if (diff <= 0) return;

            let h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            let s = Math.floor((diff % (1000 * 60)) / 1000);

            if(hEl) hEl.textContent = h.toString().padStart(2, '0');
            if(mEl) mEl.textContent = m.toString().padStart(2, '0');
            if(sEl) sEl.textContent = s.toString().padStart(2, '0');
        }

        setInterval(updateStickyTimer, 1000);
        updateStickyTimer();

        // Scroll Logic
        const stickyFs = document.getElementById('sticky-flash-bar');
        const header = document.getElementById('main-header');
        const fsSection = document.getElementById('flash-sale');
        
        if (stickyFs) {
            window.addEventListener('scroll', () => {
                const headerHidden = header?.classList.contains('header-hidden');
                const headerHeight = header ? header.offsetHeight : 0;
                
                let isPastFlashSale = false;
                if(fsSection) {
                    const rect = fsSection.getBoundingClientRect();
                    // Tính là "lướt qua" khi chân của phần Flash Sale đã vượt quá bottom của header hiện tại
                    // Nghĩa là người dùng đã cuộn xuống và khung Flash Sale gốc không còn thấy nữa
                    const offset = headerHidden ? 0 : headerHeight;
                    isPastFlashSale = rect.bottom < offset;
                }
                
                if (isPastFlashSale && !headerHidden) {
                    // Vừa lướt qua thẻ flash sale VÀ đang cuộn lên (giống header)
                    stickyFs.style.top = `${headerHeight}px`;
                    stickyFs.classList.remove('-translate-y-full', 'opacity-0', 'invisible');
                    stickyFs.classList.add('translate-y-0', 'opacity-100', 'visible');
                } else {
                    // Giấu (khi chưa lướt qua thẻ flash sale HOẶC khi cuộn xuống giống header)
                    stickyFs.style.top = '0px';
                    stickyFs.classList.remove('translate-y-0', 'opacity-100', 'visible');
                    stickyFs.classList.add('-translate-y-full', 'opacity-0', 'invisible');
                }
            }, {passive: true});
        }
    });
</script>
@endif
