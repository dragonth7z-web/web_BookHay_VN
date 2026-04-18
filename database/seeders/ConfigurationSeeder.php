<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class ConfigurationSeeder extends Seeder
{
    public function run()
    {
        $configs = [
            // Service Bar — Miễn phí vận chuyển
            ['key' => 'service_ship_title', 'value' => 'Miễn phí giao hàng', 'description' => 'Tiêu đề dịch vụ vận chuyển trên trang chủ'],
            ['key' => 'service_ship_sub', 'value' => 'Đơn từ 200.000đ toàn quốc', 'description' => 'Mô tả phụ dịch vụ vận chuyển'],

            // Service Bar — Đổi trả
            ['key' => 'service_return_title', 'value' => '30 ngày đổi trả', 'description' => 'Tiêu đề chính sách đổi trả trên trang chủ'],
            ['key' => 'service_return_sub', 'value' => 'Thủ tục nhanh, dễ dàng', 'description' => 'Mô tả phụ chính sách đổi trả'],

            // Service Bar — Bản quyền
            ['key' => 'service_copyright_title', 'value' => '100% bản quyền', 'description' => 'Tiêu đề cam kết bản quyền trên trang chủ'],
            ['key' => 'service_copyright_sub', 'value' => 'Nói không với sách giả, lậu', 'description' => 'Mô tả phụ cam kết bản quyền'],

            // Service Bar — Tích điểm
            ['key' => 'service_point_title', 'value' => 'Tích điểm nhận quà', 'description' => 'Tiêu đề chương trình tích điểm trên trang chủ'],
            ['key' => 'service_point_sub', 'value' => 'Đặc quyền thành viên VIP', 'description' => 'Mô tả phụ chương trình tích điểm'],

            // General
            ['key' => 'site_name', 'value' => 'THLD Bookstore', 'description' => 'Tên website'],
            ['key' => 'site_description', 'value' => 'Nhà sách trực tuyến hàng đầu Việt Nam', 'description' => 'Mô tả website'],
            ['key' => 'contact_phone', 'value' => '1900 6868', 'description' => 'Số điện thoại liên hệ'],
            ['key' => 'contact_email', 'value' => 'cskh@thld.vn', 'description' => 'Email hỗ trợ khách hàng'],
            ['key' => 'contact_address', 'value' => '123 Nguyễn Văn Cừ, Q.5, TP.HCM', 'description' => 'Địa chỉ liên hệ'],
            ['key' => 'free_ship_min_order', 'value' => '200000', 'description' => 'Giá trị đơn hàng tối thiểu được miễn phí giao hàng (VNĐ)'],
        ];

        foreach ($configs as $config) {
            Setting::updateOrCreate(
                ['key' => $config['key']],
                ['value' => $config['value'], 'description' => $config['description']]
            );
        }

        echo "Seeded " . count($configs) . " config entries.\n";
    }
}
