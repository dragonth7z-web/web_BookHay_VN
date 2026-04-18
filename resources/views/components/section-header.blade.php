@props([
    'icon'        => 'local_fire_department',
    'title'       => '',
    'subtitle'    => '',
    'viewAllUrl'  => null,
    'viewAllText' => 'Xem tất cả',
    'badge'       => null,
])

{{-- 
  Section Header — Fahasa Style with Tailwind "Bảo trì vàng"
--}}
<div class="flex items-center justify-between mb-5 pb-3 border-b-2 border-slate-200/60 dark:border-slate-700/60 relative gap-4">
    <div class="flex items-center gap-3 min-w-0">
        {{-- Red accent bar (Custom CSS shape could be kept or done via Tailwind, we use Tailwind here) --}}
        <div class="w-1 h-7 bg-gradient-to-b from-primary to-rose-500 rounded flex-shrink-0 shadow-[0_2px_8px_rgba(201,33,39,0.25)]"></div>

        <div class="flex items-center justify-center w-8 h-8 rounded-[4px] bg-primary shadow-sm shadow-brand-primary/30 flex-shrink-0">
            <span class="material-symbols-outlined text-white text-lg font-[FILL_1]">{{ $icon }}</span>
        </div>

        <div class="min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <h2 class="text-base md:text-lg font-black text-slate-900 dark:text-slate-100 uppercase tracking-tight whitespace-nowrap"
                    style="font-family: var(--font-heading, 'Lora', serif);">{{ $title }}</h2>
                @if($badge)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-brand-primary/10 text-brand-primary dark:bg-brand-primary/20 dark:text-red-300 leading-snug whitespace-nowrap">{{ $badge }}</span>
                @endif
                {{ $slot }}
            </div>
            @if($subtitle)
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5 whitespace-nowrap overflow-hidden text-ellipsis">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    @if($viewAllUrl)
        <a href="{{ $viewAllUrl }}" class="group inline-flex items-center gap-1 text-[13px] font-bold text-primary px-3 py-1.5 border-[1.5px] border-brand-primary/30 dark:border-brand-primary/20 rounded-[4px] hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-primary-hover dark:hover:text-red-400 whitespace-nowrap flex-shrink-0 transition-all">
            {{ $viewAllText }}
            <span class="material-symbols-outlined text-base transition-transform group-hover:translate-x-0.5">chevron_right</span>
        </a>
    @endif
</div>

{{-- No JS needed for static section-header component --}}