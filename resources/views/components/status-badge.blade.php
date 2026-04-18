@props(['status', 'type' => 'order'])

@php
    $value = $status instanceof \BackedEnum ? $status->value : (string) $status;

    $map = [
        'order' => [
            'pending' => ['bg-yellow-100 text-yellow-800', 'Chờ xác nhận'],
            'confirmed' => ['bg-blue-100 text-blue-800', 'Đã xác nhận'],
            'shipping' => ['bg-indigo-100 text-indigo-800', 'Đang giao'],
            'delivered' => ['bg-green-100 text-green-800', 'Đã giao'],
            'completed' => ['bg-emerald-100 text-emerald-800', 'Hoàn thành'],
            'cancelled' => ['bg-red-100 text-red-800', 'Đã hủy'],
            'returned' => ['bg-orange-100 text-orange-800', 'Đã hoàn trả'],
        ],
        'book' => [
            'in_stock' => ['bg-green-100 text-green-800', 'Còn hàng'],
            'out_of_stock' => ['bg-red-100 text-red-800', 'Hết hàng'],
            'discontinued' => ['bg-gray-100 text-gray-600', 'Ngừng bán'],
        ],
        'coupon' => [
            'active' => ['bg-green-100 text-green-800', 'Đang hoạt động'],
            'paused' => ['bg-yellow-100 text-yellow-800', 'Tạm dừng'],
            'expired' => ['bg-gray-100 text-gray-500', 'Hết hạn'],
        ],
    ];

    [$classes, $label] = $map[$type][$value] ?? ['bg-gray-100 text-gray-500', 'Không xác định'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold $classes"]) }}>
    {{ $label }}
</span>

{{-- No JS needed for static status-badge component --}}