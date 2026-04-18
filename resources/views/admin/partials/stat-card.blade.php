{{--
    Stat Card Partial
    Props:
      $label      — Card label (uppercase, text-[10px], tracking-widest)
      $value      — Main value to display (text-xl, font-black)
      $trend      — Numeric trend value (>= 0 → green/trending_up, < 0 → red/trending_down)
      $icon       — Material Symbols icon name for the icon box
      $canvasId   — ID for the sparkline <canvas> element
      $footerText — Text shown in the footer (left side)
      $footerLink — URL for the "Xem chi tiết" link (right side)
--}}
<div class="stat-card group hover:border-red-200 transition-colors">
    <div class="flex justify-between items-start">
        <div class="space-y-1">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $label }}</p>
            <h3 class="text-xl font-black text-gray-800 mt-1 leading-tight">{{ $value }}</h3>
            <p class="text-[10px] text-gray-400 font-medium">
                <span class="font-semibold {{ $trend >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $trend >= 0 ? '+' : '' }}{{ $trend }}%
                </span>
                so với hôm qua
            </p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-primary group-hover:bg-red-100 transition-colors">
            <span class="material-symbols-outlined">{{ $icon }}</span>
        </div>
    </div>
    <div class="mt-3">
        <div class="h-10 -mx-1">
            <canvas id="{{ $canvasId }}"></canvas>
        </div>
    </div>
    <div class="mt-3 pt-3 border-t border-gray-50 flex items-center justify-between text-[10px]">
        <span class="flex items-center gap-1 font-semibold {{ $trend >= 0 ? 'text-green-600' : 'text-red-600' }}">
            <span class="material-symbols-outlined text-sm">{{ $trend >= 0 ? 'trending_up' : 'trending_down' }}</span>
            {{ $footerText }}
        </span>
        <a href="{{ $footerLink }}" class="flex items-center gap-1 font-bold text-primary hover:underline">
            Xem chi tiết
            <span class="material-symbols-outlined text-[13px]">open_in_new</span>
        </a>
    </div>
</div>
