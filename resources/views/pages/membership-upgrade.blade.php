@extends('layouts.app')

@section('title', 'Nâng Cấp Hạng Thành Viên - THLD')

@section('content')

@php
    $tierColors = [
        'silver'  => ['text' => 'text-slate-500',  'bg' => 'bg-slate-50',   'border' => 'border-slate-200', 'btn' => 'border border-slate-300 text-slate-600 hover:border-slate-500'],
        'gold'    => ['text' => 'text-amber-600',  'bg' => 'bg-white',      'border' => 'border-amber-400', 'btn' => 'bg-primary text-white hover:bg-primary/90 shadow-[0_4px_12px_rgba(201,33,39,0.25)]'],
        'diamond' => ['text' => 'text-sky-600',    'bg' => 'bg-slate-50',   'border' => 'border-sky-200',   'btn' => 'border border-sky-300 text-sky-600 hover:border-sky-500'],
    ];
    $currentKey = $currentTier['key'] ?? 'silver';
@endphp

{{-- ── Page Header ── --}}
<div class="mb-8">
    <p class="text-[10px] font-black uppercase tracking-[0.25em] text-primary mb-3">Hành Trình Tri Thức</p>
    <h1 class="text-4xl font-black text-gray-900 mb-3 leading-tight"
        style="font-family: var(--font-heading, 'Lora', serif)">
        Nâng Cấp Hạng Thành Viên
    </h1>
    <p class="text-gray-500 text-sm max-w-lg leading-relaxed">
        Mở khoá những đặc quyền thượng lưu và trải nghiệm nghệ thuật ngôn từ ở một tầm cao mới.
    </p>
</div>

{{-- ── Tier Comparison Cards ── --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
    @foreach($tiers as $tier)
    @php
        $tc        = $tierColors[$tier['key']];
        $isCurrent = $tier['key'] === $currentKey;
        $isPopular = $tier['key'] === 'gold';
        $isHighest = $tier['key'] === 'diamond';
    @endphp
    <div class="relative rounded-2xl border-2 {{ $tc['border'] }} {{ $tc['bg'] }} p-6 flex flex-col
        {{ $isPopular ? 'shadow-[0_8px_32px_rgba(201,33,39,0.15)] scale-[1.02]' : 'shadow-sm' }}">

        {{-- Popular badge --}}
        @if($isPopular)
        <div class="absolute -top-3 left-1/2 -translate-x-1/2">
            <span class="inline-flex items-center gap-1 bg-primary text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-md">
                Phổ biến nhất
            </span>
        </div>
        @endif

        {{-- Tier header --}}
        <div class="flex items-center justify-between mb-1">
            <p class="text-xs font-black uppercase tracking-widest {{ $tc['text'] }}">{{ $tier['label'] }}</p>
            @if($isCurrent)
            <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Hạng hiện tại</span>
            @endif
        </div>
        <p class="text-[11px] text-gray-500 mb-5">
            @if($tier['key'] === 'silver') Khởi đầu hành trình sưu tầm
            @elseif($tier['key'] === 'gold') Dành cho độc giả tâm huyết
            @else Đẳng cấp giám tuyển nghệ thuật
            @endif
        </p>

        {{-- Benefits --}}
        <ul class="space-y-3 flex-1 mb-6">
            @foreach($tier['benefits'] as $benefit)
            <li class="flex items-start gap-2.5">
                <span class="material-symbols-outlined {{ $tc['text'] }} text-[16px] mt-0.5 flex-shrink-0">check_circle</span>
                <span class="text-[13px] text-gray-700 leading-snug">{{ $benefit['title'] }}</span>
            </li>
            @endforeach
        </ul>

        {{-- Condition --}}
        <div class="border-t border-gray-100 pt-4 mb-4">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Điều kiện nâng cấp:</p>
            <p class="text-sm font-black text-gray-800">
                @if($tier['threshold'] === 0)
                    Miễn phí — Tự động
                @else
                    Tích lũy thêm {{ number_format($tier['threshold']) }}đ
                @endif
            </p>
        </div>

        {{-- CTA button --}}
        @if($isCurrent)
            <button disabled
                class="w-full py-3 rounded-xl text-sm font-black uppercase tracking-wider bg-gray-100 text-gray-400 cursor-not-allowed">
                Đang sở hữu
            </button>
        @elseif($tier['threshold'] <= ($totalSpent ?? 0))
            <button disabled
                class="w-full py-3 rounded-xl text-sm font-black uppercase tracking-wider bg-green-50 text-green-600 border border-green-200 cursor-not-allowed">
                ✓ Đã đủ điều kiện
            </button>
        @else
            <a href="{{ route('books.search') }}"
                class="w-full py-3 rounded-xl text-sm font-black uppercase tracking-wider text-center transition-all {{ $tc['btn'] }}">
                Nâng Cấp Ngay
            </a>
        @endif
    </div>
    @endforeach
</div>

{{-- ── Quyền Lợi Đặc Quyền — Bento Grid ── --}}
<div class="mb-10">
    <h2 class="text-xl font-black text-gray-900 mb-5"
        style="font-family: var(--font-heading, 'Lora', serif)">
        Quyền Lợi Đặc Quyền
    </h2>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

        {{-- Tăng Tốc F-Point --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-primary text-xl">add_circle</span>
            </div>
            <p class="font-black text-gray-900 text-sm mb-1">Tăng Tốc F-Point</p>
            <p class="text-[12px] text-gray-500 leading-relaxed">
                Mỗi mức tích lũy tăng lên tới 2% cho mọi đơn hàng. Điểm thưởng có thể đổi trực tiếp thành các phần thưởng hoặc mọi ưu kiện.
            </p>
        </div>

        {{-- Ảnh thư viện (center tall) --}}
        <div class="row-span-2 rounded-2xl overflow-hidden relative" style="min-height: 280px;">
            <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=600&q=80"
                 alt="Library"
                 class="w-full h-full object-cover absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
        </div>

        {{-- Quà Sinh Nhật --}}
        <div class="bg-primary rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full pointer-events-none"></div>
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-white text-xl">cake</span>
            </div>
            <div>
                <p class="font-black text-white text-sm mb-1">Quà Sinh Nhật</p>
                <p class="text-[12px] text-white/80 leading-relaxed">
                    Một phần quà cá nhân hoá trong ngày đặc biệt của bạn.
                </p>
            </div>
        </div>

        {{-- Vận Chuyển Đặc Quyền --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-blue-600 text-xl">local_shipping</span>
            </div>
            <p class="font-black text-gray-900 text-sm mb-1">Vận Chuyển Đặc Quyền</p>
            <p class="text-[12px] text-gray-500 leading-relaxed">
                Miễn phí hoàn toàn với mọi quy trình đóng gói thêm công ti m.
            </p>
        </div>

        {{-- Early Access --}}
        <div class="bg-slate-800 rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white/5 rounded-full pointer-events-none"></div>
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-white text-xl">schedule</span>
            </div>
            <div>
                <p class="font-black text-white text-sm mb-1">Early Access</p>
                <p class="text-[12px] text-white/70 leading-relaxed">
                    Sở hữu trước những bản in giới hạn trước khi ra mắt công chúng.
                </p>
            </div>
        </div>

        {{-- Hỗ Trợ Giảm Tuyển --}}
        <div class="col-span-2 md:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex gap-4">
            <div class="flex-1">
                <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center mb-3">
                    <span class="material-symbols-outlined text-violet-600 text-xl">support_agent</span>
                </div>
                <p class="font-black text-gray-900 text-sm mb-1">Hỗ Trợ Giảm Tuyển</p>
                <p class="text-[12px] text-gray-500 leading-relaxed">
                    Nhận tư vấn 1:1 từ các biên tập viên để xây dựng tủ sách cá nhân theo phong cách riêng của bạn.
                </p>
            </div>
            <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 self-end">
                <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=200&q=70"
                     alt="Support"
                     class="w-full h-full object-cover">
            </div>
        </div>

    </div>
</div>

@endsection
