@extends('layouts.app')

@section('title', 'Đối Tác Xuất Bản - THLD')

@section('content')

{{-- ── Page Header ── --}}
<div class="mb-8">
    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-2">Đối Tác Chiến Lược</p>
    <h1 class="text-4xl font-black text-gray-900 mb-4 leading-tight"
        style="font-family: var(--font-heading, 'Lora', serif)">
        Đối tác xuất bản
    </h1>
    <p class="text-gray-500 text-sm max-w-xl leading-relaxed">
        Sự tinh hoa của tri thức được vun đắp từ những mối quan hệ bền vững. The Literary Gallery tự hào là ngôi nhà chung của những đơn vị xuất bản hàng đầu, cam kết mang đến 100% bản quyền và chất lượng biên tập xuất sắc nhất.
    </p>
</div>

{{-- ── Search + Filter ── --}}
<div class="mb-7">
    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">Tìm kiếm đối tác</p>
    <div class="flex flex-col sm:flex-row gap-3">
        {{-- Search input --}}
        <div class="relative flex-1 max-w-sm">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
            <input type="text" id="publisher-search"
                   placeholder="Tìm kiếm xuất bản..."
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
        </div>

        {{-- Filter tabs --}}
        <div class="flex items-center gap-2 flex-wrap">
            @foreach(['all' => 'Tất cả', 'partner' => 'Trong nước', 'foreign' => 'Quốc tế', 'domestic' => 'Hàn lâm', 'kim-dien' => 'Kim điển'] as $key => $label)
            <a href="{{ route('publishers.index', ['filter' => $key]) }}"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
                    {{ $activeFilter === $key
                        ? 'bg-primary text-white shadow-[0_4px_12px_rgba(201,33,39,0.25)]'
                        : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Publisher Grid ── --}}
@forelse($publishers as $publisher)
    @if($loop->first)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8" id="publisher-grid">
    @endif

    <div class="publisher-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group"
         data-name="{{ strtolower($publisher->name) }}">

        {{-- Logo area --}}
        <div class="relative h-44 overflow-hidden flex items-center justify-center
            @php
                $gradients = [
                    'from-[#111827] to-[#1f2937]',
                    'from-[#C92127] to-[#ef5350]',
                    'from-[#1e293b] to-[#334155]',
                    'from-[#0f172a] to-[#1e293b]',
                    'from-[#7f1d1d] to-[#991b1b]',
                    'from-[#1e3a5f] to-[#1e40af]',
                ];
                $grad = $gradients[$publisher->id % count($gradients)];
            @endphp
            bg-gradient-to-br {{ $grad }}">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full pointer-events-none"></div>
            <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-white/5 rounded-full pointer-events-none"></div>

            @if($publisher->logo && !str_contains($publisher->logo, 'ui-avatars'))
                <img src="{{ $publisher->logo_url }}"
                     alt="{{ $publisher->name }}"
                     class="max-w-[60%] max-h-[60%] object-contain filter brightness-0 invert opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-all duration-300 relative z-10">
            @else
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center relative z-10">
                    <span class="material-symbols-outlined text-white text-3xl">corporate_fare</span>
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="p-5">
            <h3 class="font-black text-gray-900 text-base mb-2 group-hover:text-primary transition-colors">
                {{ $publisher->name }}
            </h3>
            <p class="text-xs text-gray-500 leading-relaxed mb-4 line-clamp-2">
                @if($publisher->address)
                    {{ $publisher->address }}
                @else
                    Nhà xuất bản uy tín, cam kết chất lượng và bản quyền chính hãng.
                @endif
            </p>

            <div class="flex items-center justify-between">
                <a href="{{ route('books.search', ['publisher' => $publisher->id]) }}"
                    class="inline-flex items-center gap-1 text-xs font-black text-primary hover:underline uppercase tracking-wider">
                    Xem bộ sưu tập
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
                @if($publisher->is_partner)
                <span class="inline-flex items-center gap-1 bg-primary/10 text-primary text-[10px] font-bold px-2 py-0.5 rounded-full">
                    <span class="material-symbols-outlined text-[11px]">verified</span>
                    Đối tác
                </span>
                @endif
            </div>
        </div>
    </div>

    @if($loop->last)
        </div>
    @endif

@empty
    <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-200 p-16 text-center mb-8">
        <span class="material-symbols-outlined text-gray-300 text-5xl block mb-4">corporate_fare</span>
        <p class="text-gray-500 font-semibold text-lg mb-2">Chưa có đối tác nào</p>
        <a href="{{ route('books.search') }}"
            class="inline-flex items-center gap-2 bg-primary text-white font-bold px-6 py-3 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] text-sm mt-4">
            Khám phá sách
        </a>
    </div>
@endforelse

{{-- ── Load more CTA ── --}}
@if($publishers->count() >= 6)
<div class="flex justify-center mb-8">
    <button type="button"
        class="inline-flex items-center gap-2 border-2 border-gray-200 text-gray-600 font-bold px-8 py-3 rounded-xl hover:border-primary hover:text-primary transition-all text-sm">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Tải thêm đối tác
    </button>
</div>
@endif

@endsection

@push('scripts')
<script>
    // Live search filter
    document.getElementById('publisher-search')?.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.publisher-card').forEach(card => {
            const name = card.dataset.name ?? '';
            card.style.display = name.includes(q) ? '' : 'none';
        });
    });
</script>
@endpush
