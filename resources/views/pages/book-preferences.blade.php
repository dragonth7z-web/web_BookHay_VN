@extends('layouts.app')

@section('title', 'Tùy Chỉnh Sở Thích Sách - THLD')

@section('content')

{{-- ── Page Header ── --}}
<div class="text-center mb-10">
    <p class="text-[10px] font-black uppercase tracking-[0.25em] text-primary mb-3">Personalized Curation</p>
    <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 leading-tight"
        style="font-family: var(--font-heading, 'Lora', serif)">
        Tùy chỉnh sở thích sách
    </h1>
    <p class="text-gray-500 text-sm max-w-md mx-auto leading-relaxed">
        Giúp chúng tôi hiểu tâm hồn tri thức của bạn. Những lựa chọn này sẽ định hình các bộ sưu tập và gợi ý dành cho bạn trong không gian thư viện kỹ thuật số.
    </p>
</div>

{{-- ── Thể loại yêu thích ── --}}
<div class="mb-10">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-lg font-black text-gray-900">Thể loại yêu thích</h2>
            <p class="text-xs text-gray-400 mt-0.5">Chọn các thể loại bạn muốn khám phá</p>
        </div>
        <span class="text-[10px] font-black uppercase tracking-widest text-primary">Chọn ít nhất 3</span>
    </div>

    {{-- Bento grid: ô đầu lớn (2 hàng) + 4 ô hàng trên + 3 ô hàng dưới --}}
    <div class="grid gap-3" style="grid-template-columns: 1fr 1fr 1fr 1fr 1fr; grid-template-rows: 200px 200px; height: 415px;" id="category-grid">

        {{-- Ô lớn: col 1, span 2 hàng --}}
        @php $first = $categories->first(); @endphp
        @if($first)
        <label class="category-card cursor-pointer" style="grid-column: 1; grid-row: 1 / span 2;" data-id="{{ $first->id }}">
            <input type="checkbox" name="categories[]" value="{{ $first->id }}" class="sr-only category-checkbox">
            <div class="relative rounded-xl overflow-hidden h-full group ring-0 ring-primary ring-offset-2 hover:ring-2 transition-all duration-200">
                @if($first->image)
                    <img src="{{ filter_var($first->image, FILTER_VALIDATE_URL) ? $first->image : asset('storage/' . $first->image) }}"
                         alt="{{ $first->name }}"
                         class="w-full h-full object-cover absolute inset-0 transition-transform duration-700 group-hover:scale-105">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-800 to-amber-950"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent"></div>
                <div class="check-overlay absolute inset-0 bg-primary/30 opacity-0 transition-opacity duration-200 flex items-start justify-end p-3 z-20">
                    <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center shadow-lg">
                        <span class="material-symbols-outlined text-white text-[13px]">check</span>
                    </div>
                </div>
                <div class="absolute inset-0 z-10 flex flex-col justify-end p-4">
                    <h3 class="text-base font-black text-white leading-tight mb-1">{{ $first->name }}</h3>
                    <p class="text-white/60 text-[9px] font-bold uppercase tracking-widest">Gợi ý từ biên tập viên</p>
                </div>
            </div>
        </label>
        @endif

        {{-- 4 ô hàng trên (col 2-5, row 1) --}}
        @foreach($categories->skip(1)->take(4) as $index => $cat)
        @php
            $gradients = ['from-slate-600 to-slate-800','from-stone-700 to-stone-900','from-zinc-600 to-zinc-800','from-neutral-700 to-neutral-900'];
            $grad = $gradients[$index % 4];
            $col = $index + 2;
        @endphp
        <label class="category-card cursor-pointer" style="grid-column: {{ $col }}; grid-row: 1;" data-id="{{ $cat->id }}">
            <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="sr-only category-checkbox">
            <div class="relative rounded-xl overflow-hidden h-full group ring-0 ring-primary ring-offset-2 hover:ring-2 transition-all duration-200">
                @if($cat->image)
                    <img src="{{ filter_var($cat->image, FILTER_VALIDATE_URL) ? $cat->image : asset('storage/' . $cat->image) }}"
                         alt="{{ $cat->name }}"
                         class="w-full h-full object-cover absolute inset-0 transition-transform duration-700 group-hover:scale-105">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br {{ $grad }}"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                <div class="check-overlay absolute inset-0 bg-primary/30 opacity-0 transition-opacity duration-200 flex items-start justify-end p-2 z-20">
                    <div class="w-5 h-5 rounded-full bg-primary flex items-center justify-center shadow-lg">
                        <span class="material-symbols-outlined text-white text-[11px]">check</span>
                    </div>
                </div>
                <div class="absolute inset-0 z-10 flex flex-col justify-end p-3">
                    <h3 class="text-xs font-black text-white leading-tight">{{ $cat->name }}</h3>
                </div>
            </div>
        </label>
        @endforeach

        {{-- 3 ô hàng dưới (col 2-5, row 2) — ô cuối rộng hơn --}}
        @php
            $bottomCats = $categories->skip(5)->take(3)->values();
            $bottomCols = [
                ['col' => '2', 'span' => 1],
                ['col' => '3', 'span' => 1],
                ['col' => '4 / span 2', 'span' => 2],
            ];
            $gradients2 = ['from-gray-700 to-gray-900','from-rose-800 to-rose-950','from-amber-700 to-amber-900'];
        @endphp
        @foreach($bottomCats as $index => $cat)
        <label class="category-card cursor-pointer" style="grid-column: {{ $bottomCols[$index]['col'] }}; grid-row: 2;" data-id="{{ $cat->id }}">
            <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="sr-only category-checkbox">
            <div class="relative rounded-xl overflow-hidden h-full group ring-0 ring-primary ring-offset-2 hover:ring-2 transition-all duration-200">
                @if($cat->image)
                    <img src="{{ filter_var($cat->image, FILTER_VALIDATE_URL) ? $cat->image : asset('storage/' . $cat->image) }}"
                         alt="{{ $cat->name }}"
                         class="w-full h-full object-cover absolute inset-0 transition-transform duration-700 group-hover:scale-105">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br {{ $gradients2[$index] }}"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                <div class="check-overlay absolute inset-0 bg-primary/30 opacity-0 transition-opacity duration-200 flex items-start justify-end p-2 z-20">
                    <div class="w-5 h-5 rounded-full bg-primary flex items-center justify-center shadow-lg">
                        <span class="material-symbols-outlined text-white text-[11px]">check</span>
                    </div>
                </div>
                <div class="absolute inset-0 z-10 flex flex-col justify-end p-3">
                    <h3 class="text-xs font-black text-white leading-tight">{{ $cat->name }}</h3>
                </div>
            </div>
        </label>
        @endforeach

    </div>
</div>

{{-- ── Chủ đề chuyên sâu ── --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

    {{-- Left: description --}}
    <div class="flex flex-col justify-center">
        <h2 class="text-lg font-black text-gray-900 mb-2">Chủ đề chuyên sâu</h2>
        <p class="text-sm text-gray-500 leading-relaxed mb-4">
            Tinh chỉnh trải nghiệm của bạn. Chúng tôi sẽ ưu tiên hiển thị nội dung từ mỗi lĩnh vực bạn chọn để có thể tuyển chọn sách phù hợp và chính xác hơn.
        </p>
        <a href="{{ route('books.search') }}"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline">
            <span class="material-symbols-outlined text-[14px]">explore</span>
            Explore Categories
        </a>
    </div>

    {{-- Right: topic tags --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        @php
            $topics = [
                ['label' => 'Triết học hiện đại', 'active' => true],
                ['label' => 'Chuyển hóa bản thân', 'active' => false],
                ['label' => 'Tâm lý học hành vi', 'active' => false],
                ['label' => 'Khởi nghiệp', 'active' => true],
                ['label' => 'Văn học Á Đông', 'active' => false],
                ['label' => 'Công nghệ tương lai', 'active' => false],
                ['label' => 'Nhân loại học', 'active' => false],
                ['label' => 'Thiết kế đô thị', 'active' => false],
                ['label' => 'Kinh điển Phê Lạo', 'active' => false],
                ['label' => 'Thơ ca hiện đại', 'active' => false],
            ];
        @endphp
        <div class="flex flex-wrap gap-2" id="topic-tags">
            @foreach($topics as $topic)
            <button type="button"
                class="topic-tag px-3 py-1.5 rounded-lg text-xs font-bold transition-all border
                    {{ $topic['active']
                        ? 'bg-primary text-white border-primary shadow-[0_2px_8px_rgba(201,33,39,0.25)]'
                        : 'bg-white text-gray-600 border-gray-200 hover:border-primary hover:text-primary' }}">
                {{ $topic['label'] }}
            </button>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Tác giả bạn quan tâm ── --}}
<div class="mb-10">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-black text-gray-900">Tác giả bạn quan tâm</h2>
        <a href="{{ route('books.search') }}"
            class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
            Xem thêm tác giả
            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
        </a>
    </div>

    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4" id="author-grid">
        @forelse($authors as $author)
        <label class="author-card cursor-pointer text-center group" data-id="{{ $author->id }}">
            <input type="checkbox" name="authors[]" value="{{ $author->id }}" class="sr-only author-checkbox">
            <div class="relative mb-2">
                <div class="w-16 h-16 rounded-full overflow-hidden mx-auto border-2 border-transparent
                            group-hover:border-primary transition-all duration-200 author-avatar-wrap">
                    <img src="{{ $author->avatar_url }}"
                         alt="{{ $author->name }}"
                         class="w-full h-full object-cover">
                </div>
                {{-- Check badge --}}
                <div class="author-check absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-primary flex items-center justify-center shadow-md opacity-0 transition-opacity duration-200 mx-auto" style="left: calc(50% + 12px)">
                    <span class="material-symbols-outlined text-white text-[11px]">check</span>
                </div>
            </div>
            <p class="text-xs font-bold text-gray-800 line-clamp-1 group-hover:text-primary transition-colors">
                {{ $author->name }}
            </p>
            <p class="text-[10px] text-gray-400">{{ $author->books_count }} cuốn</p>
        </label>
        @empty
            <div class="col-span-6 text-center py-6 text-gray-400 text-sm">Chưa có tác giả nào</div>
        @endforelse
    </div>
</div>

{{-- ── Save CTA ── --}}
<div class="text-center mb-8">
    <a href="{{ route('books.search') }}"
        class="inline-flex items-center gap-2 bg-primary text-white font-black px-10 py-4 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_16px_rgba(201,33,39,0.30)] text-sm uppercase tracking-wider">
        Lưu sở thích
    </a>
    <p class="text-xs text-gray-400 mt-3">
        Sở thích của bạn sẽ được dùng để chọn lọc và gợi ý sách phù hợp nhất dành cho bạn.
    </p>
</div>

@endsection

@push('scripts')
<script>
(function () {
    // Category toggle
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', () => {
            const cb      = card.querySelector('.category-checkbox');
            const overlay = card.querySelector('.check-overlay');
            const wrap    = card.querySelector('.relative');

            cb.checked = !cb.checked;
            overlay.style.opacity = cb.checked ? '1' : '0';
            wrap.classList.toggle('ring-2', cb.checked);
        });
    });

    // Author toggle
    document.querySelectorAll('.author-card').forEach(card => {
        card.addEventListener('click', () => {
            const cb    = card.querySelector('.author-checkbox');
            const check = card.querySelector('.author-check');
            const wrap  = card.querySelector('.author-avatar-wrap');

            cb.checked = !cb.checked;
            check.style.opacity  = cb.checked ? '1' : '0';
            wrap.classList.toggle('border-primary', cb.checked);
            wrap.classList.toggle('border-transparent', !cb.checked);
        });
    });

    // Topic tag toggle
    document.querySelectorAll('.topic-tag').forEach(btn => {
        btn.addEventListener('click', () => {
            const active = btn.classList.contains('bg-primary');
            btn.classList.toggle('bg-primary', !active);
            btn.classList.toggle('text-white', !active);
            btn.classList.toggle('border-primary', !active);
            btn.classList.toggle('shadow-[0_2px_8px_rgba(201,33,39,0.25)]', !active);
            btn.classList.toggle('bg-white', active);
            btn.classList.toggle('text-gray-600', active);
            btn.classList.toggle('border-gray-200', active);
        });
    });

    // Select all categories — removed (replaced by "Chọn ít nhất 3" label)
})();
</script>
@endpush
