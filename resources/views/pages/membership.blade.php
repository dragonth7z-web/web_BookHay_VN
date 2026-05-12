@extends('layouts.app')

@section('title', 'Thành Viên VIP - THLD')

@section('content')

@php
    $tierIcons = ['silver' => 'military_tech', 'gold' => 'workspace_premium', 'diamond' => 'diamond'];
    $tierColors = [
        'silver'  => ['text' => 'text-slate-500',  'bg' => 'bg-slate-100',  'border' => 'border-slate-200',  'active_border' => 'border-slate-400'],
        'gold'    => ['text' => 'text-amber-500',  'bg' => 'bg-amber-50',   'border' => 'border-amber-200',  'active_border' => 'border-amber-400'],
        'diamond' => ['text' => 'text-sky-500',    'bg' => 'bg-sky-50',     'border' => 'border-sky-200',    'active_border' => 'border-sky-400'],
    ];
    $activeTierKey = request('tier', $currentTier['key'] ?? 'silver');
    $activeTierData = collect($tiers)->firstWhere('key', $activeTierKey) ?? $tiers[0];
@endphp

{{-- ── Member Status Card ── --}}
<div class="bg-gray-50 border border-gray-200 rounded-2xl p-8 mb-6 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-5 pointer-events-none"
         style="background-image: url('https://www.transparenttextures.com/patterns/asfalt-light.png')"></div>

    {{-- Breadcrumb top-right --}}
    @if(!$isGuest && $user)
    <div class="absolute top-4 right-4">
        <a href="{{ route('account.profile') }}"
            class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 hover:text-primary transition-colors bg-white border border-gray-200 px-3 py-1.5 rounded-lg">
            Thành viên
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        </a>
    </div>
    @endif

    {{-- Tier icon --}}
    <div class="relative z-10 flex flex-col items-center">
        <div class="w-20 h-20 rounded-2xl bg-white border-2 {{ $tierColors[$currentTier['key']]['border'] }} flex items-center justify-center mb-4 shadow-sm">
            <span class="material-symbols-outlined {{ $tierColors[$currentTier['key']]['text'] }} text-4xl"
                  style="font-variation-settings:'FILL' 1">{{ $currentTier['icon'] }}</span>
        </div>

        <p class="text-xs font-black uppercase tracking-[0.2em] {{ $tierColors[$currentTier['key']]['text'] }} mb-1">
            {{ $currentTier['label'] }}
        </p>

        @if(!$isGuest && $user)
            <p class="text-gray-500 text-sm max-w-xs leading-relaxed mb-5">
                Bạn đã là một phần của cộng đồng tinh hoa. Hãy tiếp tục hành trình để mở khoá những đặc quyền thượng lưu hơn.
            </p>

            {{-- Progress bar --}}
            <div class="w-full max-w-sm mb-2">
                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full transition-all duration-700"
                         style="width: {{ $progressPct }}%"></div>
                </div>
            </div>
            <div class="flex items-center justify-between w-full max-w-sm text-xs text-gray-500 font-semibold">
                <span>F-Point: {{ number_format($user->loyalty_points ?? 0) }}</span>
                @if($nextTier)
                    <span>Thêm {{ number_format($nextTier['threshold'] - ($user->total_spent ?? 0)) }}đ để nâng hạng {{ $nextTier['label'] }}</span>
                @else
                    <span class="text-sky-500 font-black">Hạng cao nhất ✦</span>
                @endif
            </div>
        @else
            <p class="text-gray-500 text-sm max-w-sm leading-relaxed mb-5">
                Đăng nhập để xem thông tin hạng thành viên và theo dõi đặc quyền của bạn.
            </p>
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 bg-primary text-white font-bold px-6 py-2.5 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] text-sm">
                Đăng nhập ngay
            </a>
        @endif
    </div>
</div>

{{-- ── Stats Row ── --}}
@if(!$isGuest && $user)
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    {{-- Ưu đãi của bạn --}}
    <div class="col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[14px]">redeem</span>
            Ưu đãi của bạn
        </p>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-[11px] text-gray-500 mb-1">F-Point hiện có</p>
                <p class="text-2xl font-black text-gray-900">{{ number_format($user->loyalty_points ?? 0) }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-[11px] text-gray-500 mb-1">Freeship hiện có</p>
                <p class="text-2xl font-black text-gray-900">
                    {{ $freeshipCount >= 999 ? '∞' : $freeshipCount }}
                    <span class="text-sm font-semibold text-gray-400">lần</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Thành tích năm --}}
    <div class="col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[14px]">bar_chart</span>
            Thành tích năm {{ now()->year }}
        </p>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-[11px] text-gray-500 mb-1">Số đơn hàng</p>
                <p class="text-2xl font-black text-gray-900">
                    {{ $orderCount }}
                    <span class="text-sm font-semibold text-gray-400">đơn</span>
                </p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-[11px] text-gray-500 mb-1">Đã thanh toán</p>
                <p class="text-2xl font-black text-gray-900">
                    {{ number_format($totalSpent / 1000, 0) }}
                    <span class="text-sm font-semibold text-gray-400">k đ</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Tier Benefits Section ── --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8">

    {{-- Section title --}}
    <div class="px-6 py-4 border-b border-gray-100">
        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-800">
            Quyền lợi thành viên tại Gallery
        </p>
    </div>

    {{-- Tier tabs --}}
    <div class="grid grid-cols-3 border-b border-gray-100" id="tier-tabs">
        @foreach($tiers as $tier)
        @php $tc = $tierColors[$tier['key']]; @endphp
        <a href="{{ route('membership.index', ['tier' => $tier['key']]) }}"
            class="flex flex-col items-center gap-1.5 py-4 px-3 transition-all border-b-2
                {{ $activeTierKey === $tier['key']
                    ? $tc['text'] . ' ' . $tc['active_border'] . ' bg-gray-50'
                    : 'text-gray-400 border-transparent hover:text-gray-600' }}">
            <span class="material-symbols-outlined text-2xl"
                  style="font-variation-settings:'FILL' {{ $activeTierKey === $tier['key'] ? '1' : '0' }}">
                {{ $tier['icon'] }}
            </span>
            <span class="text-[10px] font-black uppercase tracking-widest">
                {{ strtoupper(str_replace('Hạng ', '', $tier['label'])) }}
            </span>
        </a>
        @endforeach
    </div>

    {{-- Benefits list --}}
    <div class="p-6 space-y-5">
        @foreach($activeTierData['benefits'] as $benefit)
        <div class="flex items-start gap-4">
            <div class="w-9 h-9 rounded-xl {{ $tierColors[$activeTierKey]['bg'] }} flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined {{ $tierColors[$activeTierKey]['text'] }} text-[18px]">
                    {{ $benefit['icon'] }}
                </span>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">{{ $benefit['title'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $benefit['note'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- CTA --}}
    <div class="mx-6 mb-6 border border-dashed border-primary/30 rounded-xl p-5 bg-primary/5 text-center">
        <p class="text-sm italic text-gray-600 mb-4">
            "Tri thức là tài sản quý giá nhất, hãy để chúng tôi đồng hành cùng bạn."
        </p>
        @if($isGuest)
            <a href="{{ route('register') }}"
                class="inline-flex items-center gap-2 bg-primary text-white font-black px-8 py-3 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] text-sm uppercase tracking-wider">
                Đăng ký thành viên ngay
            </a>
        @elseif($nextTier)
            <a href="{{ route('membership.upgrade') }}"
                class="inline-flex items-center gap-2 bg-primary text-white font-black px-8 py-3 rounded-xl hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(201,33,39,0.25)] text-sm uppercase tracking-wider">
                Nâng cấp hạng ngay
            </a>
        @else
            <span class="inline-flex items-center gap-2 bg-sky-500 text-white font-black px-8 py-3 rounded-xl text-sm uppercase tracking-wider">
                <span class="material-symbols-outlined text-[18px]">diamond</span>
                Bạn đang ở hạng cao nhất
            </span>
        @endif
    </div>
</div>

{{-- ── Tier Comparison Table ── --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-100">
        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-800">So sánh các hạng thành viên</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Đặc quyền</th>
                    @foreach($tiers as $tier)
                    <th class="px-4 py-3 text-center">
                        <div class="flex flex-col items-center gap-1">
                            <span class="material-symbols-outlined {{ $tierColors[$tier['key']]['text'] }} text-xl"
                                  style="font-variation-settings:'FILL' 1">{{ $tier['icon'] }}</span>
                            <span class="text-xs font-black {{ $tierColors[$tier['key']]['text'] }}">{{ $tier['label'] }}</span>
                        </div>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach([
                    ['label' => 'Ngưỡng chi tiêu',    'values' => ['0đ', '300.000đ', '1.000.000đ']],
                    ['label' => 'Tỉ lệ tích F-Point', 'values' => ['0,5%', '1%', '2%']],
                    ['label' => 'Freeship/tháng',      'values' => ['—', '2 lần', 'Không giới hạn']],
                    ['label' => 'Quà sinh nhật',       'values' => ['—', 'Voucher 10%', 'Voucher 30% + sách']],
                    ['label' => 'Flash sale sớm',      'values' => ['—', '1 giờ', '3 giờ']],
                    ['label' => 'Hỗ trợ ưu tiên',     'values' => ['—', '—', '24/7 riêng']],
                ] as $row)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-3.5 text-gray-700 font-medium text-sm">{{ $row['label'] }}</td>
                    @foreach($row['values'] as $i => $val)
                    <td class="px-4 py-3.5 text-center text-sm
                        {{ $val === '—' ? 'text-gray-300' : $tierColors[$tiers[$i]['key']]['text'] . ' font-bold' }}">
                        {{ $val }}
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
