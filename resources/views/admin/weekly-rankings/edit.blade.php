@extends('layouts.admin')

@section('title', 'Chỉnh sửa Bảng Xếp Hạng Tuần')

@section('content')
<form action="{{ route('admin.weekly-rankings.update', $weeklyRanking->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Chỉnh sửa bảng xếp hạng tuần</h1>
                <div class="text-sm text-slate-500 mt-1">ID: #{{ $weeklyRanking->id }}</div>
            </div>
            <a href="{{ route('admin.weekly-rankings.index') }}" class="text-sm text-slate-500 hover:text-primary">Quay lại</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="admin-card overflow-hidden">
                    <div class="p-6 border-b border-[var(--admin-border)] bg-[var(--admin-surface-muted)] flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">schedule</span>
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">Thông tin bảng</h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Tiêu đề (Tùy chọn)</label>
                            <input type="text" name="name_ranking" value="{{ old('name_ranking', $weeklyRanking->week_name) }}"
                                   class="admin-input w-full" placeholder="Ví dụ: Tuần 12 - Tháng 3" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Ngày bắt đầu</label>
                                <input type="date" name="start_date" value="{{ old('start_date', $weeklyRanking->week_start?->format('Y-m-d')) }}"
                                       class="admin-input w-full" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Ngày kết thúc</label>
                                <input type="date" name="end_date" value="{{ old('end_date', $weeklyRanking->week_end?->format('Y-m-d')) }}"
                                       class="admin-input w-full" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="admin-card overflow-hidden">
                    <div class="p-6 border-b border-[var(--admin-border)] bg-[var(--admin-surface-muted)] flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">leaderboard</span>
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">Danh sách theo hạng</h3>
                    </div>

                    <div class="p-6 space-y-4">
                        @foreach([1,2,3,4,5,6] as $rank)
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Top {{ $rank }}</label>
                                @php $selItem = $selectedByRank[$rank] ?? null; @endphp
                                <select name="items[{{ $rank }}][book_id]" class="admin-input w-full">
                                    <option value="">-- Trống --</option>
                                    @foreach($books as $book)
                                        @php $isSelected = (string) old("items.$rank.book_id", $selItem?->book_id) === (string) $book->id; @endphp
                                        <option value="{{ $book->id }}" {{ $isSelected ? 'selected' : '' }}>
                                            {{ $book->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>

                    @error('items')
                        <div class="mt-4 text-red-600 text-sm font-bold">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="space-y-6">
                <div class="admin-card overflow-hidden">
                    <div class="p-6 border-b border-[var(--admin-border)] bg-[var(--admin-surface-muted)] flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">info</span>
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">Lưu ý</h3>
                    </div>
                    <div class="p-6">
                        <ul class="text-sm text-slate-600 dark:text-slate-300 space-y-2 list-disc pl-5">
                            <li>Sách trong cùng một bảng không được lặp lại ở nhiều hạng.</li>
                            <li>Trang chủ sẽ ưu tiên dữ liệu trong khoảng tuần hiện tại.</li>
                        </ul>
                    </div>
                </div>

                <button type="submit" class="admin-btn-primary w-full py-4 justify-center uppercase tracking-wide">
                    Cập nhật bảng
                </button>
            </div>
        </div>
    </div>
</form>
@endsection


