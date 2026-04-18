@extends('layouts.admin')

@section('title', 'Tạo Flash Sale')

@section('content')
<form action="{{ route('admin.flash_sales.store') }}" method="POST">
    @csrf
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Tạo Flash Sale mới</h1>
                <div class="text-sm text-slate-500 mt-1">Chọn sách cho Top 1..Top 8 và đặt giá flash</div>
            </div>
            <a href="{{ route('admin.flash_sales.index') }}" class="text-sm text-slate-500 hover:text-primary">Quay lại</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="admin-card overflow-hidden">
                    <div class="p-6 border-b border-[var(--admin-border)] bg-[var(--admin-surface-muted)] flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">schedule</span>
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">Thời gian áp dụng</h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Tiêu đề (Tùy chọn)</label>
                            <input type="text" name="sale_name" value="{{ old('sale_name') }}"
                                   class="admin-input w-full"
                                   placeholder="Ví dụ: Flash sale cuối tuần" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Bắt đầu</label>
                                <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                                       class="admin-input w-full" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Thời lượng (Giờ)</label>
                                <input type="number" min="1" name="duration_hours" value="{{ old('duration_hours', 24) }}"
                                       class="admin-input w-full"
                                       placeholder="VD: 24" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="admin-card overflow-hidden">
                    <div class="p-6 border-b border-[var(--admin-border)] bg-[var(--admin-surface-muted)] flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">bolt</span>
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">Danh sách sách (Top 1..Top 8)</h3>
                    </div>

                    <div class="p-6 space-y-4">
                        @foreach(range(1,8) as $slot)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Top {{ $slot }}</label>
                                    <select name="items[{{ $slot }}][book_id]"
                                            class="admin-input w-full flash-sale-book-select"
                                            data-price-target="flash-price-{{ $slot }}">
                                        <option value="">-- Trống --</option>
                                        @foreach($books as $book)
                                            <option value="{{ $book->id }}"
                                                    data-sale-price="{{ $book->sale_price }}">
                                                {{ $book->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Giá flash</label>
                                    <input type="number" min="0" step="1"
                                           name="items[{{ $slot }}][flash_price]"
                                           id="flash-price-{{ $slot }}"
                                           class="admin-input w-full"
                                           placeholder="Để trống = sale_price" />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('items')
                        <div class="mt-4 text-red-600 text-sm font-bold">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="space-y-6">
                <div class="admin-card overflow-hidden">
                    <div class="p-6 border-b border-[var(--admin-border)] bg-[var(--admin-surface-muted)] flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">info</span>
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">Gợi ý</h3>
                    </div>
                    <div class="p-6">
                        <ul class="text-sm text-slate-600 dark:text-slate-300 space-y-2 list-disc pl-5">
                            <li>Nếu để trống <strong>Giá flash</strong>, hệ thống sẽ tự lấy theo <strong>sale_price</strong>.</li>
                            <li>Trang chủ ưu tiên flash sale đang nằm trong khoảng thời gian.</li>
                        </ul>
                    </div>
                </div>

                <button type="submit" class="admin-btn-primary w-full py-4 justify-center uppercase tracking-wide">
                    Tạo flash sale
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    (function () {
        const selects = document.querySelectorAll('.flash-sale-book-select');
        selects.forEach(sel => {
            sel.addEventListener('change', function () {
                const targetId = this.dataset.priceTarget;
                const input = document.getElementById(targetId);
                if (!input) return;

                const salePrice = this.options[this.selectedIndex]?.dataset?.salePrice;
                if (!salePrice) return;

                // Chỉ tự điền khi input đang trống
                if (!input.value) {
                    input.value = salePrice;
                }
            });
        });
    })();
</script>

@endsection


