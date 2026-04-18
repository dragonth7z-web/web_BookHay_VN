<section id="recently-viewed-section" class="recently-viewed hidden scroll-reveal bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden py-10 px-6 md:px-8 md:py-12 section-block mt-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-primary text-2xl">history</span>
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-black text-slate-900 leading-tight tracking-tight" style="font-family: var(--font-heading, 'Lora', serif);">
                    Sách Bạn Vừa Xem
                </h2>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Lịch sử xem sách gần đây nhất của bạn</p>
            </div>
        </div>
        
        <button onclick="clearRecentlyViewed()" class="text-xs font-bold text-slate-400 hover:text-primary transition-colors flex items-center gap-1.5 px-4 py-2 rounded-xl hover:bg-red-50 group self-start sm:self-center">
            <span class="material-symbols-outlined text-base transition-transform group-hover:scale-110">delete</span>
            Xóa lịch sử xem
        </button>
    </div>

    <div id="rv-list" class="grid-book-layout">
        <!-- Dynamic items populate here via recently-viewed.js -->
    </div>
</section>

