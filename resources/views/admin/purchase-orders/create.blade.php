@extends('layouts.admin')
@section('title', 'Tạo Phiếu Nhập Kho')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.purchase-orders.index') }}" class="text-gray-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[28px]">arrow_back</span>
        </a>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">Tạo Phiếu Nhập Mới</h1>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="admin-card p-6">
        <form action="{{ route('admin.purchase-orders.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Mã Phiếu Nhập <span class="text-red-500">*</span></label>
                    <input type="text" name="po_number" required class="admin-input w-full" value="{{ old('po_number', 'PO' . date('YmdHi')) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Người Nhập (Mã NV) <span class="text-red-500">*</span></label>
                    <input type="number" name="user_id" required class="admin-input w-full bg-gray-50" readonly title="Tự động lấy ID của bạn" value="{{ auth()->user()->id ?? old('user_id') ?? 1 }}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nhà Xuất Bản (Tùy chọn)</label>
                    <select name="publisher_id" class="admin-input w-full">
                        <option value="">-- Trống --</option>
                        @foreach($publishers as $pub)
                            <option value="{{ $pub->id }}" {{ old('publisher_id') == $pub->id ? 'selected' : '' }}>{{ $pub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tổng Tiền Thanh Toán (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="total_amount" required min="0" class="admin-input w-full" value="{{ old('total_amount', 0) }}">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Ghi Chú</label>
                <textarea name="notes" class="admin-input w-full" rows="3" placeholder="Thông tin thêm...">{{ old('notes') }}</textarea>
            </div>

            <div class="mb-8 border border-gray-100 rounded-xl p-4 bg-gray-50/50">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 border-b border-gray-200 pb-2">Danh sách sách</h3>
                <div id="chi-tiet-container" class="space-y-4">
                    {{-- Row 1 --}}
                    <div class="flex flex-wrap lg:flex-nowrap gap-4 items-center">
                        <div class="flex-1 min-w-[200px]">
                            <select name="items[0][book_id]" class="admin-input flex-1 w-full" required>
                                <option value="">-- Chọn sách cần nhập --</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="number" name="items[0][quantity]" placeholder="Số Lượng" required min="1" class="admin-input w-24">
                        <input type="number" name="items[0][unit_price]" placeholder="Giá Nhập / 1 Q" required min="0" class="admin-input w-36">
                    </div>
                </div>
                
                <p class="text-[11px] text-gray-400 mt-4 italic">* Lưu ý: Để nhập nhiều sách cùng lúc, vui lòng sử dụng chức năng cập nhật hệ thống tương lai. Hiện tại bản demo hỗ trợ gửi 1 dòng chi tiết.</p>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.purchase-orders.index') }}" class="admin-btn-secondary">Hủy Bỏ</a>
                <button type="submit" class="admin-btn-primary">
                    <span class="material-symbols-outlined">save</span> Lưu Phiếu Nhập
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

