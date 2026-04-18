@extends('layouts.admin')

@section('title', 'Chi tiết Người dùng')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="admin-card overflow-hidden">
        <div class="p-6 border-b border-[var(--admin-border)] flex items-center justify-between bg-[var(--admin-surface-muted)]">
            <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[20px]">person</span> Khách Hàng / Người Dùng #{{ $user->id }}
            </h3>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-xs font-bold text-blue-500 hover:text-blue-700 transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">edit</span> Sửa
                </a>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-slate-500 hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span> Quay lại
                </a>
            </div>
        </div>
        
        <div class="p-8">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                <div class="w-32 h-32 flex-shrink-0 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center overflow-hidden border-4 border-white shadow-lg shadow-gray-200 dark:shadow-slate-900 mx-auto md:mx-0">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl text-slate-400 font-bold">{{ mb_substr($user->name, 0, 1) }}</span>
                    @endif
                </div>
                
                <div class="flex-1 space-y-6 w-full">
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                            {{ $user->name }}
                            @php $statusObj = is_object($user->status) ? $user->status : App\Enums\UserStatus::tryFrom($user->status); @endphp
                            @if($statusObj?->value === 'trạng_thái_bình_thường' || $statusObj?->value === 'active' || $user->status === 'active')
                                <span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded-full uppercase tracking-wider font-bold">Hoạt động</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-[10px] px-2 py-0.5 rounded-full uppercase tracking-wider font-bold">Khóa</span>
                            @endif
                        </h2>
                        <p class="text-sm font-bold text-primary mt-1">{{ $user->role->name ?? 'Người dùng' }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 bg-slate-50 dark:bg-slate-800 p-5 rounded-xl border border-slate-100 dark:border-slate-700">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Email</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Số điện thoại</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $user->phone ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Tổng chi tiêu</p>
                            <p class="text-sm font-bold text-green-600">{{ number_format($user->total_spent ?? 0, 0, ',', '.') }} ₫</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Điểm tích lũy</p>
                            <p class="text-sm font-bold text-orange-500">{{ number_format($user->loyalty_points ?? 0) }} Pts</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Ngày sinh</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $user->date_of_birth ? $user->date_of_birth->format('d/m/Y') : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Ngày tham gia</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

