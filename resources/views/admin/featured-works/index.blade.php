@extends('layouts.admin')

@section('title', 'Tác Phẩm Tiêu Điểm')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Tác Phẩm Tiêu Điểm</h1>
            <div class="flex items-center gap-2 text-sm text-slate-500 mt-1">
                <a class="hover:text-primary" href="{{ route('admin.dashboard') }}">Trang chủ</a>
                <span>›</span>
                <span>Tác phẩm tiêu điểm</span>
            </div>
            <p class="text-xs text-slate-500 mt-2">
                Lưu ý: Cập nhật chỉ áp dụng cho các sách đang hiển thị ở trang hiện tại.
            </p>
        </div>

        <div class="flex items-center gap-2">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-500">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('admin.featured-works.update') }}">
        @csrf

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">
                                <span class="inline-flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[18px]">check_box_outline_blank</span>
                                </span>
                            </th>
                            <th>Tựa sách</th>
                            <th>Danh mục</th>
                            <th class="text-right">Giá bán</th>
                            <th class="text-center">Tồn kho</th>
                            <th class="text-center">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($books as $book)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                                <td class="px-6 py-4 text-center">
                                    <input type="hidden" name="page_ids[]" value="{{ $book->id }}">
                                    <input
                                        type="checkbox"
                                        name="featured_ids[]"
                                        value="{{ $book->id }}"
                                        class="rounded text-primary focus:ring-primary h-4 w-4 border-gray-300"
                                        {{ $book->is_featured ? 'checked' : '' }}
                                    >
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-16 bg-gray-100 dark:bg-slate-700/50 rounded border border-gray-200 dark:border-slate-600 p-0.5 flex-shrink-0 overflow-hidden flex items-center justify-center">
                                            @if($book->cover_image_url)
                                                <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="w-full h-full object-contain">
                                            @else
                                                <span class="material-symbols-outlined text-3xl text-gray-400">image</span>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-900 dark:text-slate-100 text-sm line-clamp-1">
                                                {{ $book->title }}
                                            </div>
                                            <div class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">
                                                SKU: {{ $book->sku }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-xs font-semibold text-slate-600 dark:text-slate-300">
                                    {{ $book->category?->name ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <span class="font-black text-primary">
                                        {{ number_format((int) ($book->sale_price ?? 0), 0, ',', '.') }} đ
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-slate-900 dark:text-slate-100">
                                        {{ $book->stock ?? 0 }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="{{ $book->status_badge['class'] }}">
                                        {{ $book->status_badge['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-400">
                                    Không có sách nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3 mt-5">
            <p class="text-xs text-slate-500">
                Chọn sách tiêu điểm ở trang hiện tại rồi nhấn <span class="font-bold text-slate-700 dark:text-slate-200">Cập nhật</span>.
            </p>

            <button type="submit"
                    class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[20px]">save</span>
                Cập nhật
            </button>
        </div>
    </form>

    <div class="flex items-center justify-between flex-wrap gap-3">
        {{ $books->links() }}
    </div>
</div>
@endsection


