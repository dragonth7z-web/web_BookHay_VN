<section class="py-6 scroll-reveal">
    <div class="max-w-main mx-auto px-2 md:px-4">
        <div class="text-left mb-8 relative">
            <h2 class="font-heading text-2xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight relative inline-block after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-10 after:h-1 after:bg-primary after:rounded-full"
                style="font-family: var(--font-heading, 'Lora', serif);">Danh Mục Nổi Bật</h2>
            <p class="text-sm md:text-base text-slate-500 mt-4 max-w-2xl">Khám phá thế giới tri thức đa dạng với hàng
                nghìn đầu sách chất lượng</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-5">
            @forelse($sidebarCategories as $cat)
                <a href="{{ route('books.search') }}?category={{ $cat->id }}"
                    class="group relative flex flex-col items-center justify-center gap-4 p-5 rounded-[8px] bg-white/80 dark:bg-slate-800/70 backdrop-blur-md border border-white/50 dark:border-white/5 hover:-translate-y-2 hover:scale-[1.02] hover:border-red-500/30 hover:shadow-premium hover:shadow-brand-primary/5 transition-all duration-400 overflow-hidden"
                    data-cat-id="{{ $cat->id }}">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-white/40 to-white/10 dark:from-white/5 dark:to-transparent z-[-1] pointer-events-none">
                    </div>
                    <div
                        class="w-16 h-16 rounded-[6px] bg-slate-50 dark:bg-slate-900/50 flex items-center justify-center relative z-10 transition-all duration-300 shadow-[inset_0_2px_4px_rgba(0,0,0,0.02)] group-hover:bg-primary group-hover:rotate-12 group-hover:shadow-[0_10px_20px_rgba(201,33,39,0.25)]">
                        <span
                            class="material-symbols-outlined text-3xl text-slate-600 dark:text-slate-400 transition-all duration-300 group-hover:text-white group-hover:scale-110">
                            {{ $cat->icon ?: 'menu_book' }}
                        </span>
                    </div>
                    <span
                        class="text-sm font-bold text-slate-700 dark:text-slate-200 text-center tracking-wide z-10 transition-colors duration-300 group-hover:text-slate-900 dark:group-hover:text-white">{{ $cat->name }}</span>
                    @if($cat->badge_text)
                        <span
                            class="absolute top-3 right-3 bg-primary text-white text-[9px] font-extrabold px-2 py-1 rounded-full z-20 shadow-[0_4px_10px_rgba(201,33,39,0.3)] uppercase">{{ $cat->badge_text }}</span>
                    @endif
                    <div
                        class="absolute top-1/2 left-1/2 w-[140%] h-[140%] bg-[radial-gradient(circle,rgba(201,33,39,0.08)_0%,transparent_70%)] -translate-x-1/2 -translate-y-1/2 scale-0 group-hover:scale-100 transition-transform duration-600 z-[-1] pointer-events-none">
                    </div>
                </a>
            @empty
                <div
                    class="col-span-full py-12 text-center bg-gray-50/50 dark:bg-slate-800/50 rounded-2xl border border-dashed border-gray-200 dark:border-slate-700">
                    <span class="material-symbols-outlined text-4xl text-gray-300 dark:text-slate-600 mb-2">category</span>
                    <p class="text-sm text-gray-400 dark:text-slate-500">Đang cập nhật danh mục...</p>
                </div>
            @endforelse

            <a href="{{ route('books.search') }}"
                class="group relative flex flex-col items-center justify-center gap-4 p-5 rounded-[8px] bg-white/80 dark:bg-slate-800/70 backdrop-blur-md border border-white/50 dark:border-white/5 hover:-translate-y-2 hover:scale-[1.02] hover:border-red-500/30 hover:shadow-premium hover:shadow-brand-primary/5 transition-all duration-400 overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-white/40 to-white/10 dark:from-white/5 dark:to-transparent z-[-1] pointer-events-none">
                </div>
                <div
                    class="w-16 h-16 rounded-[6px] bg-slate-50 dark:bg-slate-900/50 flex items-center justify-center relative z-10 transition-all duration-300 shadow-[inset_0_2px_4px_rgba(0,0,0,0.02)] group-hover:bg-primary group-hover:rotate-12 group-hover:shadow-[0_10px_20px_rgba(201,33,39,0.25)]">
                    <span
                        class="material-symbols-outlined text-3xl text-slate-600 dark:text-slate-400 transition-all duration-300 group-hover:text-white group-hover:scale-110">apps</span>
                </div>
                <span
                    class="text-sm font-bold text-slate-700 dark:text-slate-200 text-center tracking-wide z-10 transition-colors duration-300 group-hover:text-slate-900 dark:group-hover:text-white">Xem
                    tất cả</span>
                <div
                    class="absolute top-1/2 left-1/2 w-[140%] h-[140%] bg-[radial-gradient(circle,rgba(201,33,39,0.08)_0%,transparent_70%)] -translate-x-1/2 -translate-y-1/2 scale-0 group-hover:scale-100 transition-transform duration-600 z-[-1] pointer-events-none">
                </div>
            </a>
        </div>
    </div>
</section>
