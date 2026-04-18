{{-- QUICK CATEGORIES SECTION --}}
<section class="py-0.5">
    <div class="grid grid-cols-4 sm:grid-cols-5 lg:grid-cols-8 gap-1.5">
        @foreach($features as $index => $cat)
            <a href="{{ $cat['href'] }}"
                class="group relative flex flex-col items-center gap-1.5 p-2 md:p-2.5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-[0_12px_30px_rgba(0,0,0,0.1)] hover:-translate-y-1 hover:border-primary/15 duration-300 transition-all no-underline cursor-pointer dark:hover:bg-slate-700/80">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl flex items-center justify-center border border-black/[0.04] shrink-0 transition-all duration-300 group-hover:[animation:bouncePremium_0.6s_cubic-bezier(0.25,1,0.5,1)] group-hover:shadow-[0_8px_20px_rgba(0,0,0,0.12)]"
                    style="background: {{ $cat['color'] ?? '#f8fafc' }}">
                    <span
                        class="material-symbols-outlined text-2xl transition-all duration-300 group-hover:!text-primary group-hover:[font-variation-settings:'FILL'_1]"
                        style="color: {{ $cat['icolor'] ?? '#4b5563' }}">{{ $cat['icon'] }}</span>
                </div>
                <span
                    class="text-[12px] md:text-[13px] font-semibold text-gray-700 dark:text-slate-300 text-center leading-[1.3] group-hover:text-primary group-hover:dark:text-brand-primary">{{ $cat['label'] }}</span>
                @if(!empty($cat['badge']))
                    <span
                        class="feature-badge absolute top-1 right-2 bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 text-[10px] font-bold tracking-wide px-1.5 py-0.5 rounded-md uppercase">{{ $cat['badge'] }}</span>
                @endif
            </a>
        @endforeach
    </div>
</section>
