@extends('layouts.app')

@section('content')
<main class="max-w-main mx-auto px-2 py-6 dark:bg-transparent">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] font-bold text-gray-500 dark:text-slate-400 mb-6 uppercase tracking-wider">
        <a class="hover:text-primary" href="{{ route('home') }}">Trang chủ</a>
        <span class="material-symbols-outlined text-[10px]">chevron_right</span>
        <span class="text-primary">Tìm kiếm sách</span>
    </nav>

    <div class="grid grid-cols-12 gap-6 items-start">

        {{-- Sidebar filters --}}
        <aside class="col-span-12 lg:col-span-3 space-y-4">
            <form method="GET" action="{{ route('books.search') }}" id="filter-form">
                <div class="bg-white dark:bg-slate-800/50 rounded-[6px] shadow-sm border border-slate-200/60 dark:border-slate-700/60 p-5">
                    <div class="flex items-center gap-2 mb-5 pb-3 border-b border-slate-200/60 dark:border-slate-700/60">
                        <span class="material-symbols-outlined text-primary font-bold">filter_alt</span>
                        <h3 class="text-sm font-bold uppercase tracking-tight text-gray-800">Bộ Lọc Tìm Kiếm</h3>
                    </div>

                    {{-- Danh mục --}}
                    <div class="sidebar-section mb-4">
                        <div class="filter-label font-bold text-xs uppercase text-gray-600 dark:text-slate-400 mb-2">DANH MỤC</div>
                        <div class="space-y-1 max-h-48 overflow-y-auto pr-1">
                            @foreach($categories as $category)
                            <label class="filter-item flex items-center gap-2 cursor-pointer text-sm py-1">
                                <input type="checkbox" name="category[]" value="{{ $category->id }}"
                                    class="rounded text-primary focus:ring-primary w-4 h-4 border-gray-300 dark:border-slate-600"
                                    {{ in_array($category->id, (array) request('category')) ? 'checked' : '' }}
                                    onchange="document.getElementById('filter-form').submit()">
                                {{ $category->name }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Giá --}}
                    <div class="sidebar-section mb-4">
                        <div class="filter-label font-bold text-xs uppercase text-gray-600 dark:text-slate-400 mb-2">GIÁ TIỀN</div>
                        <div class="space-y-2">
                            @php
                                $priceRanges = [
                                    ['label' => '0đ – 150.000đ',       'min' => 0,      'max' => 150000],
                                    ['label' => '150.000đ – 300.000đ', 'min' => 150000, 'max' => 300000],
                                    ['label' => '300.000đ – 500.000đ', 'min' => 300000, 'max' => 500000],
                                    ['label' => 'Trên 500.000đ',       'min' => 500000, 'max' => null],
                                ];
                            @endphp
                            @foreach($priceRanges as $range)
                            <label class="filter-item flex items-center gap-2 cursor-pointer text-sm py-1">
                                <input type="radio" name="price_range" value="{{ $range['min'] }}_{{ $range['max'] ?? '' }}"
                                    class="text-primary focus:ring-primary w-4 h-4 border-gray-300 dark:border-slate-600"
                                    {{ request('price_min') == $range['min'] ? 'checked' : '' }}
                                    onchange="applyPriceRange({{ $range['min'] }}, {{ $range['max'] ?? 'null' }})">
                                {{ $range['label'] }}
                            </label>
                            @endforeach
                        </div>
                        <input type="hidden" name="price_min" id="price_min" value="{{ request('price_min') }}">
                        <input type="hidden" name="price_max" id="price_max" value="{{ request('price_max') }}">
                    </div>

                    {{-- Nhà xuất bản --}}
                    <div class="sidebar-section mb-4">
                        <div class="filter-label font-bold text-xs uppercase text-gray-600 dark:text-slate-400 mb-2">NHÀ XUẤT BẢN</div>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-2 hide-scrollbar">
                            @foreach($publishers as $publisher)
                            <label class="filter-item flex items-center gap-2 cursor-pointer text-sm py-1">
                                <input type="checkbox" name="publisher[]" value="{{ $publisher->id }}"
                                    class="rounded text-primary focus:ring-primary w-4 h-4 border-gray-300 dark:border-slate-600"
                                    {{ in_array($publisher->id, (array) request('publisher')) ? 'checked' : '' }}
                                    onchange="document.getElementById('filter-form').submit()">
                                {{ $publisher->name }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white text-sm font-bold py-2 rounded-lg hover:bg-primary-dark transition-all">
                        Áp dụng bộ lọc
                    </button>
                    <a href="{{ route('books.search') }}" class="block text-center text-xs text-gray-400 hover:text-primary mt-2">Xóa bộ lọc</a>
                </div>

                {{-- Hidden fields to preserve other params --}}
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
            </form>
        </aside>

        {{-- Product grid --}}
        <div class="col-span-12 lg:col-span-9 space-y-4">

            {{-- Sort bar --}}
            <div class="bg-white dark:bg-slate-800/50 rounded-[6px] shadow-sm border border-slate-200/60 dark:border-slate-700/60 p-4 flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-4 flex-wrap">
                    <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Sắp xếp theo:</span>
                    @php
                        $sorts = [
                            '' => 'Phổ biến',
                            'moi_nhat' => 'Mới nhất',
                            'ban_chay' => 'Bán chạy',
                            'gia_tang' => 'Giá thấp → cao',
                            'gia_giam' => 'Giá cao → thấp',
                        ];
                    @endphp
                    @foreach($sorts as $val => $label)
                    <a href="{{ request()->fullUrlWithQuery(['sort' => $val]) }}"
                       class="text-xs font-bold pb-1 transition-colors {{ request('sort', '') === $val ? 'text-primary border-b-2 border-primary' : 'text-charcoal hover:text-primary' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
                <span class="text-xs text-gray-500 dark:text-slate-400 font-medium">{{ $books->total() }} sản phẩm</span>
            </div>

            {{-- Books --}}
            @if($books->count())
            <div class="grid-book-layout">
                @foreach($books as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="flex items-center justify-center pt-6 pb-4">
                {{ $books->links() }}
            </div>
            @else
            <div class="bg-white dark:bg-slate-800/50 rounded-xl border border-slate-200/60 dark:border-slate-700/60 p-16 text-center">
                <span class="material-symbols-outlined text-5xl text-gray-300 block mb-3">search_off</span>
                <p class="text-gray-500 font-medium">Không tìm thấy sản phẩm phù hợp.</p>
                <a href="{{ route('books.search') }}" class="mt-4 inline-block text-primary font-bold text-sm hover:underline">Xóa bộ lọc</a>
            </div>
            @endif
        </div>
    </div>
</main>

<script>
function applyPriceRange(min, max) {
    document.getElementById('price_min').value = min;
    document.getElementById('price_max').value = max || '';
    document.getElementById('filter-form').submit();
}
</script>
@endsection

