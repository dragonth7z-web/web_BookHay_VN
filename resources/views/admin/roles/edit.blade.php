@extends('layouts.admin')

@section('title', 'Sửa Vai Trò')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="admin-card overflow-hidden">
        <div class="p-6 border-b border-[var(--admin-border)] flex items-center justify-between bg-[var(--admin-surface-muted)]">
            <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[20px]">manage_accounts</span> Sửa Vai Trò #{{ $role->id }}
            </h3>
            <a href="{{ route('admin.roles.index') }}" class="text-xs font-bold text-slate-500 hover:text-primary transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Quay lại
            </a>
        </div>
        
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Tên Vai Trò (Tên hiển thị) <span class="text-primary">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                        class="admin-input w-full"
                        placeholder="Ví dụ: Quản trị viên">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Mã (Code, không dấu) <span class="text-primary">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $role->code) }}" required
                        class="admin-input w-full font-mono"
                        @if($role->id === 1) readonly title="Không thể đổi mã của Super Admin" @endif>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Mô tả chi tiết</label>
                <textarea name="description" rows="3"
                    class="admin-input w-full"
                    placeholder="Mô tả quyền hạn của vai trò này...">{{ old('description', $role->description) }}</textarea>
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

