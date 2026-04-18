@extends('layouts.admin')

@section('title', 'Quản Lý Sách')
@section('page-title', 'Quản Lý Sách')

@section('content')
<div class="admin-card overflow-hidden">
    <!-- Header Control -->
    <div class="p-5 border-b border-white/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4" style="background: linear-gradient(135deg, rgba(201,33,39,0.03) 0%, rgba(255,255,255,0.5) 100%);">
        <div class="relative w-full sm:w-80">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">search</span>
            <input type="text" class="admin-input w-full pl-10 pr-4 py-2" placeholder="Tìm kiếm tựa sách, tác giả...">
        </div>
        
        <div class="flex items-center gap-3">
            <button class="admin-btn-secondary flex items-center gap-2 px-4 py-2">
                <span class="material-symbols-outlined text-[18px]">filter_list</span> Lọc
            </button>
            <a href="{{ route('admin.books.create') }}" class="admin-btn-primary">
                <span class="material-symbols-outlined text-[18px]">add</span> Thêm Sách
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="admin-table min-w-[800px]">
            <thead>
                <tr>
                    <th class="w-12 text-center">
                        <input type="checkbox" class="rounded text-[#C92127] focus:ring-[#C92127] h-4 w-4 border-gray-300">
                    </th>
                    <th>Tựa Sách & Thông Tin</th>
                    <th>Danh Mục</th>
                    <th class="text-right">Giá Bán</th>
                    <th class="text-center">Tồn Kho</th>
                    <th class="text-center">Trạng Thái</th>
                    <th class="text-right">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    @php
                        $imgSrc = null;
                        if (!empty($book->cover_image)) {
                            $imgSrc = \Illuminate\Support\Str::startsWith($book->cover_image, ['http://', 'https://'])
                                ? $book->cover_image
                                : asset('storage/' . $book->cover_image);
                        }


                    @endphp
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-5 py-4 text-center">
                            <input type="checkbox" class="rounded text-primary focus:ring-primary h-4 w-4 border-gray-300">
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-16 bg-gray-100 dark:bg-slate-700/50 rounded border border-gray-200 dark:border-slate-600 p-0.5 flex-shrink-0 overflow-hidden flex items-center justify-center">
                                    @if($imgSrc)
                                        <img src="{{ $imgSrc }}" alt="{{ $book->title }}" class="w-full h-full object-contain">
                                    @else
                                        <span class="material-symbols-outlined text-3xl text-gray-400">image</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-gray-800 dark:text-slate-100 text-sm line-clamp-1 group-hover:text-primary transition-colors cursor-pointer">
                                        {{ $book->title }}
                                    </h4>
                                    <p class="text-[10px] text-gray-500 dark:text-slate-300 font-medium mt-0.5">
                                        @if($book->category) {{ $book->category->name }} @endif
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">SKU: {{ $book->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-xs font-semibold text-gray-600 dark:text-slate-300">
                            {{ $book->category?->name ?? '—' }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            <span class="font-black text-primary">{{ number_format((int) ($book->sale_price ?? 0), 0, ',', '.') }} đ</span>
                            @if(($book->price ?? 0) > ($book->sale_price ?? 0))
                                <span class="text-[10px] text-gray-400 line-through block font-medium">
                                    {{ number_format((int) ($book->price ?? 0), 0, ',', '.') }} đ
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="text-sm font-bold text-gray-800 dark:text-slate-100">{{ $book->stock ?? 0 }}</span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <x-status-badge :status="$book->status" type="book" />
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.books.edit', $book->id) }}"
                                   class="w-8 h-8 rounded bg-red-50 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all tooltip"
                                   title="Sửa">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST"
                                      onsubmit="return confirm('Xóa sách này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 rounded bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all tooltip"
                                            title="Xóa">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-400 dark:text-slate-400">
                            Không có sách nào phù hợp.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-5 border-t border-gray-100 dark:border-slate-700 flex items-center justify-between flex-wrap gap-3">
        <p class="text-xs text-gray-500 dark:text-slate-300 font-medium">
            Hiển thị
            <span class="font-bold text-gray-800 dark:text-slate-100">{{ $books->firstItem() ?? 0 }}-{{ $books->lastItem() ?? 0 }}</span>
            trong <span class="font-bold text-gray-800 dark:text-slate-100">{{ $books->total() }}</span> quyển sách
        </p>
        <div>
            {{ $books->links() }}
        </div>
    </div>
</div>
@endsection

