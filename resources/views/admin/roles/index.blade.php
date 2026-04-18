@extends('layouts.admin')

@section('title', 'Quản lý Vai trò (Roles)')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Vai trò & Phân quyền</h1>
            <p class="text-slate-500 text-sm mt-1">Danh sách các nhóm quyền trong hệ thống.</p>
        </div>
        <a href="{{ route('admin.roles.create') }}"
            class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[20px]">add_circle</span>
            Thêm vai trò mới
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
                        <th>ID</th>
                        <th>Mã (Code)</th>
                        <th>Tên Vai trò</th>
                        <th>Mô tả</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($roles as $role)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-5 py-4 text-sm font-medium text-slate-500 w-16 text-center">
                            {{ $role->id }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-block bg-primary/10 text-primary font-bold px-3 py-1 rounded-lg text-sm font-mono tracking-wide">
                                {{ $role->code ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm font-bold text-slate-900 dark:text-slate-100">
                            {{ $role->name }}
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-500 max-w-xs truncate">
                            {{ $role->description ?? 'Không có mô tả' }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Sửa">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                @if($role->id !== 1) <!-- Protect Super Admin -->
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vai trò này? Cảnh báo: Các tài khoản sử dụng quyền này sẽ bị ảnh hưởng!')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2">shield_off</span>
                            Chưa có dữ liệu vai trò.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($roles) && method_exists($roles, 'hasPages') && $roles->hasPages())
        <div class="p-4 border-t border-slate-100">{{ $roles->links() }}</div>
        @endif
    </div>
</div>
@endsection

