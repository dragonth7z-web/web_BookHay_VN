<?php

namespace App\Services;

use App\Models\User;
use App\Enums\OrderStatus;

class MembershipService
{
    /**
     * Tier definitions — ordered from lowest to highest.
     * threshold = minimum total_spent (VND) to reach this tier.
     */
    public function getTiers(): array
    {
        return [
            [
                'key'         => 'silver',
                'label'       => 'Hạng Bạc',
                'icon'        => 'military_tech',
                'color'       => 'text-slate-500',
                'bg'          => 'bg-slate-100',
                'border'      => 'border-slate-300',
                'threshold'   => 0,
                'next_target' => 300000,
                'freeship'    => 0,
                'point_rate'  => 0.5,
                'benefits'    => [
                    ['icon' => 'redeem',        'title' => 'Quà tặng sinh nhật',              'note' => 'Không áp dụng cho hạng Bạc'],
                    ['icon' => 'local_shipping', 'title' => 'Ưu đãi freeship và mã giảm giá', 'note' => 'Cập nhật theo từng chương trình cụ thể'],
                    ['icon' => 'toll',           'title' => 'Tỉ lệ tích luỹ F-Point trên giá trị đơn hàng', 'note' => '0,5% cho mọi đơn hàng hợp lệ'],
                ],
            ],
            [
                'key'         => 'gold',
                'label'       => 'Hạng Vàng',
                'icon'        => 'workspace_premium',
                'color'       => 'text-amber-500',
                'bg'          => 'bg-amber-50',
                'border'      => 'border-amber-300',
                'threshold'   => 300000,
                'next_target' => 1000000,
                'freeship'    => 2,
                'point_rate'  => 1.0,
                'benefits'    => [
                    ['icon' => 'redeem',        'title' => 'Quà tặng sinh nhật',              'note' => 'Voucher giảm 10% vào tháng sinh nhật'],
                    ['icon' => 'local_shipping', 'title' => 'Freeship 2 lần/tháng',           'note' => 'Áp dụng cho đơn từ 150.000đ'],
                    ['icon' => 'toll',           'title' => 'Tỉ lệ tích luỹ F-Point x2',     'note' => '1% cho mọi đơn hàng hợp lệ'],
                    ['icon' => 'local_offer',    'title' => 'Ưu đãi độc quyền hạng Vàng',    'note' => 'Flash sale sớm 1 giờ'],
                ],
            ],
            [
                'key'         => 'diamond',
                'label'       => 'Kim Cương',
                'icon'        => 'diamond',
                'color'       => 'text-sky-500',
                'bg'          => 'bg-sky-50',
                'border'      => 'border-sky-300',
                'threshold'   => 1000000,
                'next_target' => null,
                'freeship'    => 999,
                'point_rate'  => 2.0,
                'benefits'    => [
                    ['icon' => 'redeem',        'title' => 'Quà tặng sinh nhật cao cấp',      'note' => 'Voucher giảm 30% + sách tặng kèm'],
                    ['icon' => 'local_shipping', 'title' => 'Freeship không giới hạn',        'note' => 'Mọi đơn hàng, không điều kiện'],
                    ['icon' => 'toll',           'title' => 'Tỉ lệ tích luỹ F-Point x4',     'note' => '2% cho mọi đơn hàng hợp lệ'],
                    ['icon' => 'verified',       'title' => 'Ưu tiên đặt trước bản giới hạn', 'note' => 'Sách ký tặng & bản đặc biệt'],
                    ['icon' => 'support_agent',  'title' => 'Hỗ trợ ưu tiên 24/7',           'note' => 'Đường dây riêng cho thành viên Kim Cương'],
                ],
            ],
        ];
    }

    /**
     * Get personalised membership data for a logged-in user.
     */
    public function getMemberData(int $userId): array
    {
        $user  = User::with('orders')->find($userId);
        $tiers = $this->getTiers();

        if (!$user) {
            return [
                'user'         => null,
                'tiers'        => $tiers,
                'currentTier'  => $tiers[0],
                'nextTier'     => $tiers[1],
                'progressPct'  => 0,
                'orderCount'   => 0,
                'totalSpent'   => 0,
                'freeshipCount'=> 0,
            ];
        }

        $totalSpent = (float) ($user->total_spent ?? 0);

        // Determine current tier
        $currentTier = $tiers[0];
        foreach ($tiers as $tier) {
            if ($totalSpent >= $tier['threshold']) {
                $currentTier = $tier;
            }
        }

        // Determine next tier
        $nextTier = null;
        foreach ($tiers as $tier) {
            if ($tier['threshold'] > $currentTier['threshold']) {
                $nextTier = $tier;
                break;
            }
        }

        // Progress percentage toward next tier
        $progressPct = 0;
        if ($nextTier) {
            $range       = $nextTier['threshold'] - $currentTier['threshold'];
            $earned      = $totalSpent - $currentTier['threshold'];
            $progressPct = $range > 0 ? min(100, round(($earned / $range) * 100)) : 100;
        } else {
            $progressPct = 100;
        }

        $orderCount   = $user->orders->count();
        $freeshipCount = $currentTier['freeship'];

        return compact(
            'user',
            'tiers',
            'currentTier',
            'nextTier',
            'progressPct',
            'orderCount',
            'totalSpent',
            'freeshipCount',
        );
    }
}
