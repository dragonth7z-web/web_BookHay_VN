@extends('layouts.admin')

@section('title', 'Quản lý Tài khoản')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Khách hàng & Người dùng</h1>
            <p class="text-slate-500 text-sm mt-1">Quản lý tài khoản, phân quyền và trạng thái người dùng.</p>
        </div>
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
                        <th>Người dùng</th>
                        <th>Email/Phone</th>
                        <th>Phân quyền</th>
                        <th>Chi tiêu</th>
                        <th>Trạng thái</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-10 h-10 rounded-full object-cover border border-slate-200" alt="">
                                @else
                                    <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-slate-100 text-sm">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-400">ID: {{ $user->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-sm font-medium text-slate-700">{{ $user->email }}</p>
                            <p class="text-xs text-slate-500">{{ $user->phone ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-block bg-slate-100 text-slate-600 font-bold px-3 py-1 rounded-lg text-xs">
                                {{ $user->role->name ?? 'Người dùng' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm font-bold text-green-600">
                            {{ number_format($user->total_spent ?? 0, 0, ',', '.') }}đ
                        </td>
                        <td class="px-5 py-4">
                            @php $statusObj = is_object($user->status) ? $user->status : App\Enums\UserStatus::tryFrom($user->status); @endphp
                            @if($statusObj?->value === 'trạng_thái_bình_thường' || $statusObj?->value === 'active' || $user->status === 'active')
                                <span class="inline-flex items-center gap-1 text-green-600 text-xs font-bold">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>Hoạt động
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-red-500 text-xs font-bold">
                                    <span class="w-2 h-2 rounded-full bg-red-400"></span>Khóa
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Xem">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Sửa">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
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
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2">group_off</span>
                            Chưa có dữ liệu người dùng.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($users) && $users->hasPages())
        <div class="p-4 border-t border-slate-100">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection

