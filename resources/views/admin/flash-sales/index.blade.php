@extends('layouts.admin')

@section('title', 'Quản lý Flash Sale')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Quản lý Flash Sale</h1>
            <div class="text-sm text-slate-500 mt-1">Thiết lập thời gian và danh sách sách cho section Flash Sale</div>
        </div>
        <a href="{{ route('admin.flash_sales.create') }}"
           class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[20px]">add_circle</span>
            Tạo Flash Sale
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-green-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Flash Sale</th>
                        <th>Thời gian</th>
                        <th class="text-center">Số sách</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($flashSales as $flashSale)
                        @php $isActive = $flashSale->start_date <= now() && $flashSale->end_date >= now(); @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-900 dark:text-slate-100">
                                    {{ $flashSale->sale_name ?? 'Flash sale' }}
                                </div>
                                <div class="text-xs text-slate-500">ID: #{{ $flashSale->id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-600 dark:text-slate-300">
                                    {{ $flashSale->start_date?->format('d/m/Y H:i') }} - {{ $flashSale->end_date?->format('d/m/Y H:i') }}
                                </div>
                                <div class="mt-2">
                                    @if($isActive)
                                        <span class="inline-flex items-center gap-1 text-green-600 text-xs font-bold">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>Đang hiển thị
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-slate-400 text-xs font-bold">
                                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>Chưa đến hạn
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php $count = $flashSale->items()->count(); @endphp
                                <span class="bg-slate-100 text-slate-600 dark:bg-slate-700/30 dark:text-slate-200 text-xs px-2 py-1 rounded-full font-bold">
                                    {{ $count }} sách
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.flash_sales.edit', $flashSale->id_flash_sale) }}"
                                       class="p-2 text-primary hover:bg-red-50 rounded-lg transition-colors" title="Sửa">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.flash_sales.destroy', $flashSale->id_flash_sale) }}" method="POST"
                                          onsubmit="return confirm('Xóa flash sale này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if($flashSales->isEmpty())
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                Chưa có flash sale nào.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if($flashSales->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $flashSales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection


