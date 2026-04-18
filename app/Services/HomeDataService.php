<?php

namespace App\Services;

class HomeDataService
{
    /**
     * Get default category config.
     */
    public function getDefaultCategoryConfig(): array
    {
        return [
            'icon' => 'book_2',
            'text_color' => 'text-gray-500',
            'bg_gradient' => 'from-white to-gray-50'
        ];
    }

    /**
     * Get default configuration for gift cards (vouchers).
     */
    public function getDefaultGiftCardConfig(): array
    {
        return [
            'overlay_gradient' => 'linear-gradient(135deg, rgba(236,72,153,0.4), rgba(225,29,72,0.6))',
            'theme_class' => 'gc-card-pink',
            'ui_icon' => 'local_offer',
            'glow_color' => 'rgba(244,63,94,0.4)'
        ];
    }
}
