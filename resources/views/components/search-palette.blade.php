<div id="search-palette" class="fixed inset-0 z-[10001] hidden flex items-start justify-center pt-[15vh] px-4">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-md" onclick="closeSearchPalette()"></div>

    {{-- Palette Container --}}
    <div class="relative w-full max-w-2xl bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden border border-white/40 transform scale-95 opacity-0 transition-all duration-300 ease-out"
        id="palette-content">
        {{-- Search Input Area --}}
        <div class="p-4 border-b border-gray-100 flex items-center gap-4">
            <span class="material-symbols-outlined text-gray-400 text-2xl">search</span>
            <input type="text" id="palette-search-input" placeholder="Tìm kiếm sách, tác giả, combo..."
                class="w-full bg-transparent border-none focus:ring-0 text-lg font-medium text-gray-800 placeholder-gray-400">
            <kbd
                class="hidden md:block px-2 py-1 bg-gray-100 text-gray-400 text-[10px] font-bold rounded-md border border-gray-200">ESC</kbd>
        </div>

        {{-- Results Area (Placeholder) --}}
        <div class="max-h-[50vh] overflow-y-auto p-2 scrollbar-thin scrollbar-thumb-gray-200">
            {{-- Quick Links / Suggestions --}}
            <div id="palette-suggestions">
                <p class="px-4 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Gợi ý tìm kiếm</p>
                <div class="space-y-1">
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors group">
                        <span
                            class="material-symbols-outlined text-gray-400 group-hover:text-primary">trending_up</span>
                        <span class="text-sm font-semibold text-gray-700">Sách hot tuần này</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors group">
                        <span
                            class="material-symbols-outlined text-gray-400 group-hover:text-indigo-500">auto_stories</span>
                        <span class="text-sm font-semibold text-gray-700">Manga mới phát hành</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors group">
                        <span
                            class="material-symbols-outlined text-gray-400 group-hover:text-amber-500">local_fire_department</span>
                        <span class="text-sm font-semibold text-gray-700">Combo ưu đãi cực sốc</span>
                    </a>
                </div>
            </div>

            {{-- Dynamic Results Display --}}
            <div id="palette-results" class="hidden space-y-1">
                {{-- Results will be injected here via JS --}}
            </div>
        </div>

        {{-- Footer --}}
        <div
            class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400">
            <div class="flex gap-4">
                <span class="flex items-center gap-1"><kbd
                        class="px-1.5 py-0.5 bg-white border border-gray-200 rounded shadow-sm text-gray-500">↵</kbd> để
                    chọn</span>
                <span class="flex items-center gap-1"><kbd
                        class="px-1.5 py-0.5 bg-white border border-gray-200 rounded shadow-sm text-gray-500">↑↓</kbd>
                    để di chuyển</span>
            </div>
            <span>Power by BookStore TH</span>
        </div>
    </div>
</div>

{{-- search-palette.js is loaded globally in layouts/app.blade.php --}}