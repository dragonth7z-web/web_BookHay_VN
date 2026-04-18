<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class HomeDisplaySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'key' => 'home_flash_sale_limit',
                'value' => '10',
                'description' => 'Số lượng sản phẩm hiển thị trong mục Flash Sale'
            ],
            [
                'key' => 'home_featured_limit',
                'value' => '12',
                'description' => 'Số lượng sản phẩm hiển thị trong mục Tác phẩm tiêu điểm'
            ],
            [
                'key' => 'home_combos_limit',
                'value' => '6',
                'description' => 'Số lượng Combo hiển thị trong mục Combo Sách Cháy'
            ],
            [
                'key' => 'home_book_series_limit',
                'value' => '4',
                'description' => 'Số lượng bộ truyện hiển thị trong mục Bộ truyện trọn bộ'
            ],
            [
                'key' => 'home_series_books_limit',
                'value' => '5',
                'description' => 'Số lượng sách con hiển thị trong mỗi dòng của Bộ truyện trọn bộ'
            ],
            [
                'key' => 'home_weekly_ranking_limit',
                'value' => '10',
                'description' => 'Số lượng sách hiển thị trong Bảng xếp hạng tuần'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'description' => $setting['description']]
            );
        }

        $this->command->info('Home display settings seeded successfully!');
    }
}
