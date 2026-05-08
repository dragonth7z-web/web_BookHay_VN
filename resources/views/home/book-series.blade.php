{{-- BOOK SERIES SECTION --}}
@if($bookSeries && $bookSeries->count())
<section id="book-series" class="scroll-reveal bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden py-10 px-6 md:px-8 md:py-12 section-block">
    <x-section-header icon="library_books" title="Bộ Truyện Trọn Bộ"
        subtitle="Tuyển tập & Boxset đặc biệt từ các nhà xuất bản" badge="BOXSET" />

    <div class="flex flex-col gap-5 mt-6">
        @foreach($bookSeries->take(6) as $series)
        @php
            $totalBooks = $series->books->count();
            $allBooks   = $series->books->sortBy('id')->values();

            // [5 đầu] [+N] [5 cuối]
            $headBooks  = $allBooks->take(5);
            $tailBooks  = $totalBooks > 10 ? $allBooks->slice($totalBooks - 5) : $allBooks->slice(5);
            $midBooks   = $totalBooks > 10 ? $allBooks->slice(5, $totalBooks - 10) : collect();
            $midCount   = $midBooks->count();

            $badgeText  = strtolower($series->badge_text ?? '');
            $isOngoing  = str_contains($badgeText, 'đang') || str_contains($badgeText, 'ongoing') || str_contains($badgeText, 'updating');

            $realCovers = $series->books->filter(fn($b) =>
                !empty($b->cover_image) && !str_contains($b->cover_image_url, 'ui-avatars.com')
            );

            $gradients  = ['from-violet-600 to-indigo-700','from-rose-500 to-pink-700','from-amber-500 to-orange-600','from-emerald-500 to-teal-700','from-sky-500 to-blue-700','from-fuchsia-500 to-purple-700'];
            $gc         = $gradients[$series->id % count($gradients)];
            $hasImg     = !empty($series->image);

            $tapLabel   = $totalBooks >= 1000
                ? number_format(floor($totalBooks / 100) * 100, 0, ',', '.') . '+'
                : ($totalBooks >= 100 ? $totalBooks . '+' : $totalBooks);
            $tapLabel  .= ' TẬP';

            $seriesUrl  = route('books.search', ['series' => $series->id]);
            $discountPct = $series->original_price > $series->sale_price
                ? round((($series->original_price - $series->sale_price) / $series->original_price) * 100) : 0;
            $saving     = max(0, $series->original_price - $series->sale_price);

            // Ảnh bìa chính để hiển thị
            $mainCoverUrl = $hasImg ? $series->image_url
                : ($realCovers->count() ? $realCovers->first()->cover_image_url : null);
        @endphp

        <a href="{{ $seriesUrl }}"
           onclick="if(typeof trackSeriesView==='function') trackSeriesView({{ $series->id }},'{{ addslashes($series->name) }}')"
           class="group flex flex-row bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-0.5 transition-all duration-300"
           style="min-height: 260px;">

            {{-- ===== ẢNH BÌA ===== --}}
            <div class="relative shrink-0 flex items-center justify-center overflow-hidden"
                 style="width: 240px; background: #f1f5f9;">

                @if($mainCoverUrl)
                    {{-- Blur background --}}
                    <div class="absolute inset-0 overflow-hidden">
                        <img src="{{ $mainCoverUrl }}" alt=""
                             class="w-full h-full object-cover scale-110 blur-lg opacity-50">
                    </div>
                    {{-- Ảnh chính --}}
                    <img loading="lazy" src="{{ $mainCoverUrl }}" alt="{{ $series->name }}"
                         class="relative z-10 w-full object-contain transition-transform duration-500 group-hover:scale-[1.04] drop-shadow-2xl"
                         style="max-height: 320px; padding: 8px;">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br {{ $gc }}">
                        <div class="absolute inset-0 opacity-10"
                             style="background-image:repeating-linear-gradient(45deg,transparent,transparent 10px,rgba(255,255,255,.15) 10px,rgba(255,255,255,.15) 11px)"></div>
                    </div>
                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <span class="material-symbols-outlined text-white/50 text-[52px]">library_books</span>
                        <span class="text-white/60 text-[10px] font-bold text-center px-2">{{ $series->name }}</span>
                    </div>
                @endif

                {{-- Badge số tập --}}
                <div class="absolute top-2.5 left-2.5 z-20 inline-flex items-center gap-1
                            {{ $isOngoing ? 'bg-amber-500' : 'bg-emerald-500' }}
                            text-white text-[9px] font-black px-2 py-1 rounded-full shadow-lg">
                    <span class="material-symbols-outlined !text-[10px]">layers</span>
                    {{ $tapLabel }}
                </div>

                {{-- Badge trạng thái --}}
                <div class="absolute bottom-2.5 left-0 right-0 z-20 flex justify-center">
                    <span class="inline-flex items-center gap-1
                                 {{ $isOngoing ? 'bg-amber-500/90' : 'bg-emerald-600/90' }}
                                 text-white text-[9px] font-bold px-2.5 py-1 rounded-full shadow">
                        <span class="material-symbols-outlined !text-[9px]">{{ $isOngoing ? 'pending' : 'check_circle' }}</span>
                        {{ $isOngoing ? 'Đang cập nhật' : 'Hoàn chỉnh' }}
                    </span>
                </div>
            </div>

            {{-- ===== NỘI DUNG ===== --}}
            <div class="flex flex-col flex-1 min-w-0 py-4 px-5 gap-3">

                {{-- Tên + meta --}}
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined text-amber-500 text-[18px] shrink-0">auto_stories</span>
                        <h3 class="text-[15px] font-black text-slate-800 line-clamp-1 group-hover:text-brand-primary transition-colors">
                            {{ $series->name }}
                        </h3>
                    </div>
                    <p class="text-[11px] text-slate-400 pl-7">
                        {{ $isOngoing ? 'Đang cập nhật' : 'Bộ truyện hoàn chỉnh' }}
                        &bull;
                        {{ $totalBooks >= 1000 ? number_format($totalBooks, 0, ',', '.') : $totalBooks }} tập
                    </p>
                    @if(!empty($series->description))
                    <p class="text-[11px] text-slate-500 mt-1.5 pl-7 line-clamp-2 leading-relaxed">{{ $series->description }}</p>
                    @endif
                </div>

                {{-- THUMBNAILS: [5 đầu] [+N] [5 cuối] --}}
                <div class="flex items-end gap-2 overflow-x-auto hide-scrollbar pb-0.5"
                     id="thumbs-{{ $series->id }}">

                    @php
                    // Helper macro để render 1 thumbnail
                    $renderThumb = function($book, $gc, $extraClass = '', $extraAttr = '') use (&$renderThumb) {
                        $rc = !empty($book->cover_image) && !str_contains($book->cover_image_url, 'ui-avatars.com');
                        preg_match('/(\d+)$/', $book->title, $m);
                        $num = $m[1] ?? '';
                        return compact('rc', 'num', 'book', 'gc', 'extraClass', 'extraAttr');
                    };
                    @endphp

                    {{-- 5 tập đầu --}}
                    @foreach($headBooks as $book)
                    @php
                        $rc = !empty($book->cover_image) && !str_contains($book->cover_image_url, 'ui-avatars.com');
                        preg_match('/(\d+)$/', $book->title, $m); $num = $m[1] ?? '';
                    @endphp
                    <div class="group/t relative shrink-0 rounded-xl overflow-hidden border border-slate-200
                                hover:border-brand-primary/50 hover:-translate-y-1 hover:shadow-lg
                                shadow-sm transition-all duration-200 cursor-pointer bg-white"
                         style="width:85px; height:128px;" title="{{ $book->title }}">
                        @if($rc)
                            <img loading="lazy" src="{{ $book->cover_image_url }}" alt="{{ $book->title }}"
                                 class="w-full h-full object-cover group-hover/t:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br {{ $gc }} flex flex-col items-center justify-center gap-0.5 px-1">
                                @if($num)
                                    <span class="text-white/60 text-[8px] font-bold uppercase tracking-wider">Tập</span>
                                    <span class="text-white font-black text-xl leading-none">{{ $num }}</span>
                                @else
                                    <span class="material-symbols-outlined text-white/60 text-2xl">auto_stories</span>
                                @endif
                            </div>
                        @endif
                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent pt-3 pb-1 px-1">
                            <span class="text-[8px] font-bold text-white block text-center truncate">{{ $num ?: Str::limit($book->title, 5) }}</span>
                        </div>
                    </div>
                    @endforeach

                    {{-- Nút +N --}}
                    @if($midCount > 0)
                    <div class="relative shrink-0 rounded-xl border-2 border-dashed border-slate-300
                                bg-slate-50 hover:bg-brand-primary/5 hover:border-brand-primary/50
                                flex flex-col items-center justify-center gap-1
                                transition-all duration-200 cursor-pointer hover:-translate-y-1"
                         style="width:85px; height:128px;"
                         id="more-btn-{{ $series->id }}"
                         onclick="event.preventDefault(); expandSeriesThumbs({{ $series->id }})">
                        <span class="material-symbols-outlined text-slate-400 text-2xl">add_circle</span>
                        <span class="text-[9px] font-black text-slate-500 text-center leading-tight">+{{ $midCount }}<br>tập</span>
                    </div>

                    {{-- Tập giữa (ẩn) --}}
                    @foreach($midBooks as $book)
                    @php
                        $rc = !empty($book->cover_image) && !str_contains($book->cover_image_url, 'ui-avatars.com');
                        preg_match('/(\d+)$/', $book->title, $m); $num = $m[1] ?? '';
                    @endphp
                    <div class="group/t relative shrink-0 rounded-xl overflow-hidden border border-slate-200
                                hover:border-brand-primary/50 hover:-translate-y-1 hover:shadow-lg
                                shadow-sm transition-all duration-200 cursor-pointer bg-white hidden"
                         data-series-mid="{{ $series->id }}"
                         style="width:85px; height:128px;" title="{{ $book->title }}">
                        @if($rc)
                            <img loading="lazy" src="{{ $book->cover_image_url }}" alt="{{ $book->title }}"
                                 class="w-full h-full object-cover group-hover/t:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br {{ $gc }} flex flex-col items-center justify-center gap-0.5 px-1">
                                @if($num)
                                    <span class="text-white/60 text-[8px] font-bold uppercase tracking-wider">Tập</span>
                                    <span class="text-white font-black text-xl leading-none">{{ $num }}</span>
                                @else
                                    <span class="material-symbols-outlined text-white/60 text-2xl">auto_stories</span>
                                @endif
                            </div>
                        @endif
                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent pt-3 pb-1 px-1">
                            <span class="text-[8px] font-bold text-white block text-center truncate">{{ $num ?: Str::limit($book->title, 5) }}</span>
                        </div>
                    </div>
                    @endforeach
                    @endif

                    {{-- 5 tập cuối (ring đỏ nhạt để phân biệt là mới nhất) --}}
                    @foreach($tailBooks as $book)
                    @php
                        $rc = !empty($book->cover_image) && !str_contains($book->cover_image_url, 'ui-avatars.com');
                        preg_match('/(\d+)$/', $book->title, $m); $num = $m[1] ?? '';
                    @endphp
                    <div class="group/t relative shrink-0 rounded-xl overflow-hidden
                                border-2 border-brand-primary/30 hover:border-brand-primary
                                hover:-translate-y-1 hover:shadow-lg shadow-sm
                                transition-all duration-200 cursor-pointer bg-white"
                         style="width:85px; height:128px;" title="{{ $book->title }}">
                        @if($rc)
                            <img loading="lazy" src="{{ $book->cover_image_url }}" alt="{{ $book->title }}"
                                 class="w-full h-full object-cover group-hover/t:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br {{ $gc }} flex flex-col items-center justify-center gap-0.5 px-1">
                                @if($num)
                                    <span class="text-white/60 text-[8px] font-bold uppercase tracking-wider">Tập</span>
                                    <span class="text-white font-black text-xl leading-none">{{ $num }}</span>
                                @else
                                    <span class="material-symbols-outlined text-white/60 text-2xl">auto_stories</span>
                                @endif
                            </div>
                        @endif
                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent pt-3 pb-1 px-1">
                            <span class="text-[8px] font-bold text-white block text-center truncate">{{ $num ?: Str::limit($book->title, 5) }}</span>
                        </div>
                        {{-- Nhãn "Mới" cho tập cuối cùng --}}
                        @if($loop->last && $isOngoing)
                        <div class="absolute top-1 right-1 bg-brand-primary text-white text-[7px] font-black px-1 py-0.5 rounded leading-none">MỚI</div>
                        @endif
                    </div>
                    @endforeach

                </div>

                {{-- GIÁ + BUTTONS --}}
                <div class="flex items-center gap-4 flex-wrap mt-auto pt-2 border-t border-slate-100">
                    {{-- Giá --}}
                    <div class="flex items-baseline gap-2 flex-wrap">
                        <span class="text-brand-primary font-black text-xl leading-none">
                            {{ number_format($series->sale_price, 0, ',', '.') }}<span class="text-sm ml-0.5 align-top font-bold">đ</span>
                        </span>
                        @if($discountPct > 0)
                            <span class="bg-red-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-md">-{{ $discountPct }}%</span>
                            <span class="text-slate-400 text-xs line-through">{{ number_format($series->original_price, 0, ',', '.') }}đ</span>
                        @endif
                    </div>
                    @if($discountPct > 0)
                    <div class="flex items-center gap-1 text-emerald-600">
                        <span class="material-symbols-outlined !text-[13px]">savings</span>
                        <span class="text-[11px] font-bold">Tiết kiệm {{ number_format($saving, 0, ',', '.') }}đ</span>
                    </div>
                    @endif

                    {{-- Buttons --}}
                    <div class="flex gap-2 ml-auto shrink-0" onclick="event.preventDefault()">
                        <button type="button"
                                onclick="window.location.href='{{ $seriesUrl }}?buy=1'"
                                class="border border-slate-300 hover:border-brand-primary bg-white hover:bg-brand-primary/5
                                       text-slate-700 hover:text-brand-primary text-xs font-bold py-2 px-4 rounded-xl
                                       transition-all duration-200 flex items-center gap-1.5 whitespace-nowrap">
                            <span class="material-symbols-outlined !text-[14px]">bolt</span>
                            Mua ngay
                        </button>
                        <button type="button"
                                class="bg-brand-primary hover:bg-brand-primary-dark text-white text-xs font-bold
                                       py-2 px-4 rounded-xl transition-all duration-200 flex items-center gap-1.5
                                       shadow-sm hover:shadow-md whitespace-nowrap"
                                onclick="addSeriesToCart({{ $series->id }},'{{ addslashes($series->name) }}',event)">
                            <span class="material-symbols-outlined !text-[14px]">shopping_bag</span>
                            Thêm vào giỏ
                        </button>
                        <button type="button"
                                class="w-9 h-9 bg-slate-100 hover:bg-red-50 text-slate-400 hover:text-red-500
                                       rounded-xl transition-all duration-200 flex items-center justify-center
                                       border border-slate-200 hover:border-red-200 shrink-0"
                                onclick="toggleSeriesWishlist({{ $series->id }},event)"
                                aria-label="Yêu thích">
                            <span class="material-symbols-outlined !text-[16px]">favorite</span>
                        </button>
                    </div>
                </div>

            </div>
        </a>
        @endforeach
    </div>

    <div class="flex justify-center mt-10 pb-2">
        <a href="{{ route('books.search') }}" class="btn-view-all-premium group">
            <span class="material-symbols-outlined text-xl transition-transform group-hover:rotate-12">auto_stories</span>
            Khám phá trọn bộ truyện
        </a>
    </div>
</section>
@endif

@push('scripts')
<script>
function expandSeriesThumbs(seriesId) {
    const btn    = document.getElementById('more-btn-' + seriesId);
    const hidden = document.querySelectorAll('[data-series-mid="' + seriesId + '"]');
    if (!btn) return;
    // Chèn các tập ẩn vào trước nút +N rồi xoá nút
    hidden.forEach(el => { el.classList.remove('hidden'); btn.before(el); });
    btn.remove();
}
</script>
@endpush
