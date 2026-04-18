@extends('layouts.admin')

@section('title', 'Quản lý Mã Giảm Giá')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Mã Giảm Giá & Khuyến Mãi</h1>
            <p class="text-slate-500 text-sm mt-1">Quản lý các chương trình ưu đãi.</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}"
            class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[20px]">add_circle</span>
            Thêm mã mới
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
                        <th>Mã Code</th>
                        <th>Chương trình</th>
                        <th>Loại giảm</th>
                        <th class="text-right">Giá trị</th>
                        <th>Hạn dùng</th>
                        <th>Trạng thái</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($discountCodes as $ma)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-5 py-4">
                            <span class="inline-block bg-primary/10 text-primary font-bold px-3 py-1 rounded-lg text-sm font-mono">
                                {{ $ma->code }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm font-medium text-slate-900 dark:text-slate-100">
                            {{ $ma->name ?? '—' }}
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-500">{{ $ma->type }}</td>
                        <td class="px-5 py-4 text-right">
                            @if($ma->type === 'percentage')
                                <span class="text-green-600 font-bold">{{ $ma->value }}%</span>
                            @else
                                <span class="text-green-600 font-bold">{{ number_format($ma->value) }}đ</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-500">
                            {{ $ma->expires_at ? \Carbon\Carbon::parse($ma->expires_at)->format('d/m/Y') : 'Không giới hạn' }}
                        </td>
                        <td class="px-5 py-4">
                            <x-status-badge :status="$ma->status" type="coupon" />
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.coupons.edit', $ma->id) }}"
                                    class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $ma->id) }}" method="POST"
                                    onsubmit="return confirm('Xác nhận xóa mã giảm giá này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2">discount_off</span>
                            Chưa có mã giảm giá nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($discountCodes->hasPages())
        <div class="p-4 border-t border-slate-100">{{ $discountCodes->links() }}</div>
        @endif
    </div>
</div>
@endsection

