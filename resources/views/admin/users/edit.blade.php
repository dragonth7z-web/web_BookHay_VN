@extends('layouts.admin')

@section('title', 'Sửa Tài Khoản')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="admin-card overflow-hidden">
        <div class="p-6 border-b border-[var(--admin-border)] flex items-center justify-between bg-[var(--admin-surface-muted)]">
            <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[20px]">manage_accounts</span> Sửa Thông Tin Tài Khoản #{{ $user->id }}
            </h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-slate-500 hover:text-primary transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Quay lại
            </a>
        </div>
        
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Họ & Tên <span class="text-primary">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="admin-input w-full">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Email <span class="text-primary">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="admin-input w-full">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="admin-input w-full">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Vai trò (Role) <span class="text-primary">*</span></label>
                    <select name="role_id" required class="admin-input w-full">
                        @php $roles = App\Models\Role::all(); @endphp
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Trạng thái <span class="text-primary">*</span></label>
                <select name="status" required class="admin-input w-full">
                    @php $statusVal = is_object($user->status) ? $user->status->value : $user->status; @endphp
                    <option value="active" {{ $statusVal === 'active' || $statusVal === 'trạng_thái_bình_thường' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ $statusVal === 'inactive' || $statusVal === 'khóa' ? 'selected' : '' }}>Bị Khóa</option>
                </select>
            </div>

            <div class="pt-6 border-t border-[var(--admin-border)] bg-[var(--admin-surface-muted)] -mx-8 -mb-6 px-8 py-6 text-right">
                <button type="submit" class="admin-btn-primary">
                    Lưu Thay Đổi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

