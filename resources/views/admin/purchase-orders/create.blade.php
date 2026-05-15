@extends('layouts.admin')
@section('title', 'Tạo Phiếu Nhập Kho')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.purchase-orders.index') }}" class="text-gray-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[28px]">arrow_back</span>
        </a>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">Tạo Phiếu Nhập Mới</h1>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl">
        <ul class="list-disc pl-5 space-y-1 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.purchase-orders.store') }}" method="POST" id="po-form">
        @csrf

        {{-- Thông tin chung --}}
        <div class="admin-card p-6 mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">
                Thông Tin Phiếu Nhập
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Mã Phiếu Nhập <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="po_number" required
                        class="admin-input w-full"
                        value="{{ old('po_number', 'PO' . date('YmdHi')) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Người Nhập
                    </label>
                    <input type="text" readonly
                        class="admin-input w-full bg-gray-50 text-gray-500 cursor-not-allowed"
                        value="{{ session('user_name', 'Admin') }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Nhà Xuất Bản (Tùy chọn)
                    </label>
                    <select name="publisher_id" class="admin-input w-full">
                        <option value="">-- Không chỉ định --</option>
                        @foreach($publishers as $pub)
                            <option value="{{ $pub->id }}" {{ old('publisher_id') == $pub->id ? 'selected' : '' }}>
                                {{ $pub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tổng Tiền Thanh Toán (VNĐ) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="total_amount" id="total_amount" required min="0"
                        class="admin-input w-full font-bold text-primary"
                        value="{{ old('total_amount', 0) }}"
                        placeholder="Tự động tính từ danh sách sách">
                    <p class="text-xs text-gray-400 mt-1">Có thể chỉnh sửa nếu có phí khác (vận chuyển, v.v.)</p>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Ghi Chú</label>
                <textarea name="notes" class="admin-input w-full" rows="2"
                    placeholder="Thông tin thêm về lần nhập này...">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Danh sách sách --}}
        <div class="admin-card p-6 mb-6">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">inventory_2</span>
                    Danh Sách Sách Nhập
                </h2>
                <button type="button" onclick="addBookRow()"
                    class="flex items-center gap-1.5 text-sm font-bold text-primary hover:bg-primary/5 px-3 py-1.5 rounded-lg transition-colors border border-primary/20">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Thêm Sách
                </button>
            </div>

            {{-- Header --}}
            <div class="hidden md:grid grid-cols-12 gap-3 mb-2 px-2">
                <div class="col-span-6 text-xs font-bold text-gray-400 uppercase">Tên Sách</div>
                <div class="col-span-2 text-xs font-bold text-gray-400 uppercase text-center">Số Lượng</div>
                <div class="col-span-3 text-xs font-bold text-gray-400 uppercase text-right">Giá Nhập / Quyển</div>
                <div class="col-span-1"></div>
            </div>

            <div id="book-rows" class="space-y-3">
                {{-- Rows được inject bởi JS --}}
            </div>

            {{-- Tổng cộng --}}
            <div class="mt-4 pt-4 border-t border-dashed border-gray-200 flex justify-end">
                <div class="text-right">
                    <span class="text-sm text-gray-500 font-medium">Tổng cộng từ danh sách:</span>
                    <span class="ml-3 text-lg font-black text-primary" id="calc-total">0đ</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.purchase-orders.index') }}" class="admin-btn-secondary">Hủy Bỏ</a>
            <button type="submit" class="admin-btn-primary">
                <span class="material-symbols-outlined">save</span> Lưu Phiếu Nhập
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Danh sách sách từ server
const BOOKS = @json($books->map(fn($b) => ['id' => $b->id, 'title' => $b->title]));

let rowIndex = 0;

function buildBookOptions(selectedId = '') {
    return BOOKS.map(b =>
        `<option value="${b.id}" ${b.id == selectedId ? 'selected' : ''}>${b.title}</option>`
    ).join('');
}

function addBookRow(bookId = '', qty = 1, price = 0) {
    const i = rowIndex++;
    const row = document.createElement('div');
    row.className = 'book-row grid grid-cols-12 gap-3 items-center bg-gray-50/50 rounded-xl p-3 border border-gray-100';
    row.dataset.index = i;
    row.innerHTML = `
        <div class="col-span-12 md:col-span-6">
            <select name="items[${i}][book_id]" required
                class="admin-input w-full text-sm"
                onchange="recalcTotal()">
                <option value="">-- Chọn sách --</option>
                ${buildBookOptions(bookId)}
            </select>
        </div>
        <div class="col-span-5 md:col-span-2">
            <input type="number" name="items[${i}][quantity]"
                value="${qty}" min="1" required
                placeholder="SL"
                class="admin-input w-full text-center font-bold"
                oninput="recalcTotal()">
        </div>
        <div class="col-span-5 md:col-span-3">
            <input type="number" name="items[${i}][unit_price]"
                value="${price}" min="0" required
                placeholder="Giá nhập"
                class="admin-input w-full text-right"
                oninput="recalcTotal()">
        </div>
        <div class="col-span-2 md:col-span-1 flex justify-center">
            <button type="button" onclick="removeRow(this)"
                class="w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                title="Xóa dòng">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>
        </div>
    `;
    document.getElementById('book-rows').appendChild(row);
    recalcTotal();
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.book-row');
    if (rows.length <= 1) {
        alert('Phiếu nhập phải có ít nhất 1 sách.');
        return;
    }
    btn.closest('.book-row').remove();
    recalcTotal();
}

function recalcTotal() {
    let total = 0;
    document.querySelectorAll('.book-row').forEach(row => {
        const qty   = parseFloat(row.querySelector('[name$="[quantity]"]')?.value) || 0;
        const price = parseFloat(row.querySelector('[name$="[unit_price]"]')?.value) || 0;
        total += qty * price;
    });
    document.getElementById('calc-total').textContent = total.toLocaleString('vi-VN') + 'đ';
    // Tự động điền vào ô tổng tiền nếu user chưa chỉnh sửa thủ công
    const totalInput = document.getElementById('total_amount');
    if (!totalInput.dataset.manualEdit) {
        totalInput.value = total;
    }
}

// Cho phép user chỉnh sửa thủ công tổng tiền
document.getElementById('total_amount').addEventListener('input', function() {
    this.dataset.manualEdit = '1';
});

// Khởi tạo 1 dòng mặc định
addBookRow();
</script>
@endpush
@endsection
