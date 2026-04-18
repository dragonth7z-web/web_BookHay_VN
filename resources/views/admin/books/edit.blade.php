@extends('layouts.admin')

@section('title', 'Sửa Sách')
@section('page-title', 'Sửa Sách')

@section('content')
<form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2 space-y-6">
            <!-- Thông tin cơ bản -->
            <div class="admin-card p-6">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm mb-5 pb-2 border-b border-[var(--admin-border)] flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">info</span> Thông Tin Cơ Bản
                </h3>

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-4">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
                @endif

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Tên sách <span class="text-primary">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $book->title) }}" required
                            class="admin-input w-full" placeholder="Ví dụ: Cây Cam Ngọt Của Tôi">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Slug (URL) <span class="text-primary">*</span></label>
                            <input type="text" name="slug" value="{{ old('slug', $book->slug) }}" required
                                class="admin-input w-full">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Mã SKU <span class="text-primary">*</span></label>
                            <input type="text" name="sku" value="{{ old('sku', $book->sku) }}" required
                                class="admin-input w-full">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Tác giả</label>
                            <select name="author_ids[]" multiple class="admin-input w-full">
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}"
                                        {{ in_array($author->id, old('author_ids', $book->authors->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Nhà xuất bản</label>
                            <select name="publisher_id" class="admin-input w-full">
                                <option value="">-- Chọn NXB --</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}"
                                        {{ old('publisher_id', $book->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                        {{ $publisher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Mô tả sách</label>
                        <textarea name="description" rows="5" class="admin-input w-full">{{ old('description', $book->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Mô tả ngắn</label>
                        <textarea name="short_description" rows="2" class="admin-input w-full">{{ old('short_description', $book->short_description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Giá & Kho -->
            <div class="admin-card p-6">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm mb-5 pb-2 border-b border-[var(--admin-border)] flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">sell</span> Giá bán & Kho hàng
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Giá gốc (₫)</label>
                        <input type="number" name="original_price" value="{{ old('original_price', $book->original_price) }}"
                            class="admin-input w-full" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Giá khuyến mãi (₫)</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price', $book->sale_price) }}"
                            class="admin-input w-full" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Giá nhập (₫)</label>
                        <input type="number" name="cost_price" value="{{ old('cost_price', $book->cost_price) }}"
                            class="admin-input w-full" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Số lượng tồn kho <span class="text-primary">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" required
                            class="admin-input w-full" placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Chi tiết xuất bản -->
            <div class="admin-card p-6">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm mb-5 pb-2 border-b border-[var(--admin-border)] flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">menu_book</span> Chi Tiết Xuất Bản
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">ISBN</label>
                        <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                            class="admin-input w-full" placeholder="978-...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Số trang</label>
                        <input type="number" name="pages" value="{{ old('pages', $book->pages) }}"
                            class="admin-input w-full" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Năm xuất bản</label>
                        <input type="number" name="published_year" value="{{ old('published_year', $book->published_year) }}"
                            class="admin-input w-full" placeholder="{{ date('Y') }}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Trọng lượng (gram)</label>
                        <input type="number" name="weight" value="{{ old('weight', $book->weight) }}"
                            class="admin-input w-full" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Loại bìa</label>
                        <select name="cover_type" class="admin-input w-full">
                            <option value="">-- Chọn loại bìa --</option>
                            <option value="paperback" {{ old('cover_type', $book->cover_type?->value) === 'paperback' ? 'selected' : '' }}>Bìa mềm</option>
                            <option value="hardcover" {{ old('cover_type', $book->cover_type?->value) === 'hardcover' ? 'selected' : '' }}>Bìa cứng</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Ngôn ngữ</label>
                        <input type="text" name="language" value="{{ old('language', $book->language) }}"
                            class="admin-input w-full">
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột phụ bên phải -->
        <div class="space-y-6">
            <!-- Ảnh Bìa -->
            <div class="admin-card p-6">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm mb-5 pb-2 border-b border-[var(--admin-border)] flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">image</span> Hình Ảnh
                </h3>

                @if($book->cover_image)
                <div class="mb-4 rounded-xl overflow-hidden border border-[var(--admin-border)] aspect-[3/4] bg-slate-100 flex items-center justify-center">
                    <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                </div>
                @endif

                <div class="border-2 border-dashed border-[var(--admin-border)] rounded-xl p-6 text-center hover:bg-[var(--admin-surface-muted)] transition-colors cursor-pointer group">
                    <div class="w-16 h-16 bg-red-50 text-red-400 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">upload_file</span>
                    </div>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">{{ $book->cover_image ? 'Thay đổi ảnh bìa' : 'Kéo thả ảnh vào đây' }}</p>
                    <p class="text-[10px] text-slate-400 mb-4">Hoặc click để chọn file (JPG, PNG, WebP)</p>
                    <input type="file" name="cover_image" accept="image/*" class="hidden" id="cover_image_input">
                    <label for="cover_image_input" class="admin-btn-secondary text-xs cursor-pointer">
                        Chọn Ảnh
                    </label>
                </div>
            </div>

            <!-- Tổ chức -->
            <div class="admin-card p-6">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm mb-5 pb-2 border-b border-[var(--admin-border)] flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">category</span> Tổ Chức
                </h3>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Danh mục sách</label>
                        <select name="category_id" class="admin-input w-full">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Trạng thái sản phẩm</label>
                        <select name="status" class="admin-input w-full">
                            <option value="in_stock" {{ old('status', $book->status?->value) === 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="out_of_stock" {{ old('status', $book->status?->value) === 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                            <option value="discontinued" {{ old('status', $book->status?->value) === 'discontinued' ? 'selected' : '' }}>Ngừng kinh doanh</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" id="is_featured"
                            {{ old('is_featured', $book->is_featured) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary">
                        <label for="is_featured" class="text-sm font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Sách nổi bật</label>
                    </div>

                    <div class="pt-4 border-t border-[var(--admin-border)] grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.books.index') }}" class="admin-btn-secondary text-center text-sm uppercase">
                            Hủy Bỏ
                        </a>
                        <button type="submit" class="admin-btn-primary text-sm uppercase">
                            Lưu Thay Đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
