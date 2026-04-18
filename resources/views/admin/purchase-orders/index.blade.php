@extends('layouts.admin')
@section('title', 'Quản lý Phiếu Nhập')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Quản lý Phiếu Nhập Kho</h1>
            <p class="text-slate-500 text-sm mt-1">Danh sách các lần nhập sách vào kho.</p>
        </div>
        <a href="{{ route('admin.purchase-orders.create') }}" class="admin-btn-primary">
            <span class="material-symbols-outlined">add</span> Tạo Phiếu Nhập
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 text-green-800 p-4 rounded-xl flex items-center gap-2">
        <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
    </div>
    @endif

    <div class="admin-card">
        <div class="p-6">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th class="text-left font-bold text-gray-600">Mã Phiếu</th>
                        <th class="text-left font-bold text-gray-600">Người Nhập</th>
                        <th class="text-left font-bold text-gray-600">NXB</th>
                        <th class="text-left font-bold text-gray-600">Tổng Tiền</th>
                        <th class="text-left font-bold text-gray-600">Ngày Tạo</th>
                        <th class="text-center font-bold text-gray-600">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $order)
                    <tr class="border-b last:border-0 border-gray-100 hover:bg-gray-50/50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="py-3 px-2 font-bold text-primary">#{{ $order->po_number }}</td>
                        <td class="py-3 px-2">{{ $order->user->name ?? '---' }}</td>
                        <td class="py-3 px-2 text-sm text-gray-600">{{ $order->publisher->name ?? '---' }}</td>
                        <td class="py-3 px-2 font-bold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                        <td class="py-3 px-2 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-3 px-2 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.purchase-orders.show', $order->id) }}" class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded bg-white shadow-sm border border-gray-100 transition-colors">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </a>
                                <form action="{{ route('admin.purchase-orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phiếu nhập kho này?')">
                                    @csrf @method('DELETE')
                                    <button class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded bg-white shadow-sm border border-gray-100 transition-colors">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-10 italic">
                            Chưa có dữ liệu phiếu nhập kho nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($purchaseOrders->hasPages())
        <div class="mt-4">{{ $purchaseOrders->links() }}</div>
    @endif
</div>
@endsection

