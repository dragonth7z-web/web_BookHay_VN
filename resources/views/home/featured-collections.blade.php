@if(isset($collections) && $collections->count())
    <section id="featured-collections" class="scroll-reveal bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden py-10 px-6 md:px-8 md:py-12">
        <x-section-header title="Tuyển Tập" subtitle="Tổng hợp các loại sách hay" icon="auto_stories">
        </x-section-header>
        {{-- 2 hàng × 5 cột --}}
        @php $collectionList = $collections->take(10); @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-5 mt-6">
            @foreach($collectionList as $collection)
                <a href="{{ route('books.search') }}?collection={{ $collection->id }}"
                    class="group relative block aspect-[3/2] rounded-xl overflow-hidden shadow-sm hover:shadow-[var(--shadow-book-hover)] hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0">
                        @if($collection->image)
                            <img src="{{ filter_var($collection->image, FILTER_VALIDATE_URL) ? $collection->image : asset('storage/' . $collection->image) }}"
                                alt="{{ $collection->title }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-600 to-slate-800"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent group-hover:from-primary/75 transition-colors duration-500"></div>
                    </div>
                    <div class="absolute inset-0 z-10 flex flex-col justify-end p-3">
                        <h3 class="text-xs font-black text-white drop-shadow-md line-clamp-2 group-hover:translate-x-0.5 transition-transform duration-300">{{ $collection->title }}</h3>
                        <p class="text-white/70 text-[10px] mt-0.5 line-clamp-1">{{ $collection->subtitle ?? 'Khám phá ngay' }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Center Action Button --}}
        <div class="flex justify-center mt-12 pb-4">
            <a href="{{ route('collections.index') }}" class="btn-view-all-premium group">
                <span
                    class="material-symbols-outlined text-xl transition-transform group-hover:rotate-12">auto_stories</span>
                Xem tất cả bộ sưu tập
            </a>
        </div>
    </section>
@endif
