@extends('layouts.admin')

@section('title', 'Chi tiết Yêu cầu Hỗ trợ')
@section('page-title', 'Chi tiết Yêu cầu Hỗ trợ')

@section('content')
<div class="max-w-[1230px] mx-auto space-y-6">

    {{-- ── Page Header ── --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.support-tickets.index') }}"
                class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-primary hover:border-primary/30 transition-all">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-black text-slate-900 dark:text-white leading-tight">{{ $ticket->subject }}</h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="font-mono text-[11px] text-slate-400">{{ $ticket->ticket_number }}</span>
                    {{-- status_badge_class and status_label are Model Accessors --}}
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wide border {{ $ticket->status_badge_class }}">
                        {{ $ticket->status_label }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black {{ $ticket->priority_badge_class }}">
                        {{ $ticket->priority_label }}
                    </span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2.5 rounded-xl flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-green-500 text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ── Left: Content ── --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Ticket Content --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6">
                <h3 class="font-bold text-slate-700 dark:text-slate-300 text-xs uppercase tracking-widest mb-5 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">description</span>
                    Nội dung yêu cầu
                </h3>

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black text-sm flex-shrink-0">
                        {{ strtoupper(substr($ticket->requester_name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        {{-- requester_name and requester_email are Model Accessors --}}
                        <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $ticket->requester_name }}</p>
                        <p class="text-[11px] text-slate-400">{{ $ticket->requester_email }}</p>
                    </div>
                    <p class="text-[11px] text-slate-400 flex-shrink-0">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap border border-slate-100 dark:border-slate-700">
                    {{ $ticket->description }}
                </div>
            </div>

            {{-- Admin Note --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6">
                <h3 class="font-bold text-slate-700 dark:text-slate-300 text-xs uppercase tracking-widest mb-5 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">edit_note</span>
                    Ghi chú xử lý nội bộ
                </h3>

                <form action="{{ route('admin.support-tickets.update', $ticket->id) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="{{ $ticket->status?->value }}">
                    <input type="hidden" name="priority" value="{{ $ticket->priority?->value }}">

                    <textarea name="admin_note" rows="4"
                        class="admin-input w-full mb-4 text-sm"
                        placeholder="Nhập ghi chú xử lý nội bộ...">{{ old('admin_note', $ticket->admin_note) }}</textarea>

                    <button type="submit" class="admin-btn-primary px-5 py-2 text-sm">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        Lưu ghi chú
                    </button>
                </form>
            </div>
        </div>

        {{-- ── Right: Actions ── --}}
        <div class="space-y-5">

            {{-- Update Status & Priority --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6">
                <h3 class="font-bold text-slate-700 dark:text-slate-300 text-xs uppercase tracking-widest mb-5 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">tune</span>
                    Cập nhật trạng thái
                </h3>

                <form action="{{ route('admin.support-tickets.update', $ticket->id) }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                        </div>
                    @endif

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Trạng thái</label>
                        <select name="status" class="admin-input w-full text-sm">
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" {{ $ticket->status?->value === $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Mức độ ưu tiên</label>
                        <select name="priority" class="admin-input w-full text-sm">
                            @foreach($priorities as $priority)
                                <option value="{{ $priority->value }}" {{ $ticket->priority?->value === $priority->value ? 'selected' : '' }}>
                                    {{ $priority->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="admin_note" value="{{ $ticket->admin_note }}">

                    <button type="submit" class="admin-btn-primary w-full py-2.5 text-sm">
                        <span class="material-symbols-outlined text-[16px]">update</span>
                        Cập nhật
                    </button>
                </form>
            </div>

            {{-- Ticket Meta --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6">
                <h3 class="font-bold text-slate-700 dark:text-slate-300 text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">info</span>
                    Thông tin ticket
                </h3>

                <dl class="space-y-3">
                    @foreach([
                        ['label' => 'Mã ticket', 'value' => $ticket->ticket_number, 'mono' => true],
                        ['label' => 'Ngày tạo',  'value' => $ticket->created_at->format('d/m/Y H:i'), 'mono' => false],
                    ] as $meta)
                        <div class="flex justify-between items-center text-sm">
                            <dt class="text-slate-400 font-medium">{{ $meta['label'] }}</dt>
                            <dd class="{{ $meta['mono'] ? 'font-mono' : '' }} font-bold text-slate-700 dark:text-slate-300">
                                {{ $meta['value'] }}
                            </dd>
                        </div>
                    @endforeach

                    @if($ticket->resolved_at)
                        <div class="flex justify-between items-center text-sm">
                            <dt class="text-slate-400 font-medium">Ngày giải quyết</dt>
                            <dd class="font-bold text-green-600">{{ $ticket->resolved_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-red-100 dark:border-red-900/30 shadow-sm p-6">
                <h3 class="font-bold text-red-500 text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    Vùng nguy hiểm
                </h3>
                <form action="{{ route('admin.support-tickets.destroy', $ticket->id) }}" method="POST"
                    onsubmit="return confirm('Xác nhận xóa vĩnh viễn yêu cầu hỗ trợ này?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="w-full py-2.5 border-2 border-red-200 text-red-500 font-bold text-sm rounded-xl hover:bg-red-50 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">delete_forever</span>
                        Xóa yêu cầu này
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
