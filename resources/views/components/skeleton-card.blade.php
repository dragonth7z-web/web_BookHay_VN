@props(['count' => 5])

@for($i = 0; $i < $count; $i++)
    <div
        class="skeleton-card p-3 flex flex-col gap-2 h-full bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-[6px]">
        {{-- Image --}}
        <div class="skeleton-box w-full aspect-square rounded-md mb-2"></div>
        {{-- Title --}}
        <div class="skeleton-box w-full h-4 rounded-sm"></div>
        <div class="skeleton-box w-2/3 h-4 rounded-sm mb-1"></div>
        {{-- Social Proof line --}}
        <div class="skeleton-box w-3/4 h-3 rounded-full opacity-60"></div>
        {{-- Price --}}
        <div class="mt-auto pt-2 border-t border-slate-50 dark:border-white/5">
            <div class="skeleton-box w-1/2 h-5 rounded-md"></div>
        </div>
    </div>
@endfor

{{-- js/components/skeleton-card.js: no JS needed for static skeleton --}}