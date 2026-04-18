{{-- YOUNG AUTHORS / FEATURED BOOKS SECTION --}}
<section id="young-authors" class="scroll-reveal bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden py-10 px-6 md:px-8 md:py-12 section-block">
    <x-section-header icon="auto_stories" title="Tác phẩm tiêu điểm" subtitle="Khám phá những trí tuệ sáng giá nhất"
        badge="SÁNG TẠO" />

    <div class="grid-book-layout">
        @php
            $booksToDisplay = (isset($featuredBooks) && $featuredBooks->count()) ? $featuredBooks : ($youngAuthorsBooks ?? collect());
        @endphp

        @forelse($booksToDisplay->take(15) as $index => $book)
            <x-book-card :book="$book" />
        @empty
            <p class="col-span-full text-[13px] text-slate-400 italic text-center py-8">Chưa có dữ liệu tác phẩm tiêu điểm.
            </p>
        @endforelse
    </div>

    {{-- Center Action Button --}}
    @if($booksToDisplay->count() > 0)
        <div class="flex justify-center mt-12 pb-4">
            <a href="{{ route('books.search') }}" class="btn-view-all-premium group">
                <span
                    class="material-symbols-outlined text-xl transition-transform group-hover:rotate-12">auto_stories</span>
                Khám phá trọn bộ tác phẩm
            </a>
        </div>
    @endif
</section>
