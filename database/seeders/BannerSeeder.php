<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Banner::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ── Main Banners (Hero Slider) ──────────────────────────────────
        $mainBanners = [
            [
                'title'       => 'Đại Tiệc Sách — Sale Tới 50%',
                'badge_text'  => 'Khuyến Mãi Lớn',
                'image'       => 'https://images.unsplash.com/photo-1507842217343-58387272b84d?auto=format&fit=crop&q=80&w=1200&h=500',
                'url'         => '/sieu-thi-sach/tim-kiem',
                'button_text' => 'Mua Ngay',
                'position'    => 'home_main',
                'sort_order'  => 1,
                'is_visible'  => true,
            ],
            [
                'title'       => 'Combo Sách Hay — Quà Tặng Ý Nghĩa',
                'badge_text'  => 'Hot Combo',
                'image'       => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&q=80&w=1200&h=500',
                'url'         => '/sieu-thi-sach/tim-kiem',
                'button_text' => 'Khám Phá Combo',
                'position'    => 'home_main',
                'sort_order'  => 2,
                'is_visible'  => true,
            ],
            [
                'title'       => 'Tủ Sách Văn Học Mới Nhất 2026',
                'badge_text'  => 'Mới Ra Mắt',
                'image'       => 'https://images.unsplash.com/photo-1491843331063-83ca734da794?auto=format&fit=crop&q=80&w=1200&h=500',
                'url'         => '/sieu-thi-sach/tim-kiem',
                'button_text' => 'Xem Ngay',
                'position'    => 'home_main',
                'sort_order'  => 3,
                'is_visible'  => true,
            ],
            [
                'title'       => 'Sách Kinh Tế Bán Chạy Nhất',
                'badge_text'  => 'Hot Deal',
                'image'       => 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?auto=format&fit=crop&q=80&w=1200&h=500',
                'url'         => '/sieu-thi-sach/tim-kiem',
                'button_text' => 'Mua Ngay',
                'position'    => 'home_main',
                'sort_order'  => 4,
                'is_visible'  => true,
            ],
            [
                'title'       => 'Truyện Tranh Manga Giá Tốt',
                'badge_text'  => 'Flash Sale',
                'image'       => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=1200&h=500',
                'url'         => '/sieu-thi-sach/danh-muc/manga',
                'button_text' => 'Bắt Đầu Mua',
                'position'    => 'home_main',
                'sort_order'  => 5,
                'is_visible'  => true,
            ],
            [
                'title'       => 'Sách Ngoại Ngữ Luyện Thi',
                'badge_text'  => 'Giảm 30%',
                'image'       => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&q=80&w=1200&h=500',
                'url'         => '/sieu-thi-sach/danh-muc/ngoai-ngu',
                'button_text' => 'Tham Khảo',
                'position'    => 'home_main',
                'sort_order'  => 6,
                'is_visible'  => true,
            ],
            [
                'title'       => 'Kho Tàng Truyện Cổ Tích',
                'badge_text'  => 'Trẻ Em',
                'image'       => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=1200&h=500',
                'url'         => '/sieu-thi-sach/tim-kiem',
                'button_text' => 'Mua Cho Bé',
                'position'    => 'home_main',
                'sort_order'  => 7,
                'is_visible'  => true,
            ],
            [
                'title'       => 'Văn Bảng Thuyết Trình Hay',
                'badge_text'  => 'Chuyên Môn',
                'image'       => 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&q=80&w=1200&h=500',
                'url'         => '/sieu-thi-sach/tim-kiem',
                'button_text' => 'Khám Phá',
                'position'    => 'home_main',
                'sort_order'  => 8,
                'is_visible'  => true,
            ],
            [
                'title'       => 'Sách Khoa Học Thần Bí',
                'badge_text'  => 'Độc Lạ',
                'image'       => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&q=80&w=1200&h=500',
                'url'         => '/sieu-thi-sach/tim-kiem',
                'button_text' => 'Xem Liền',
                'position'    => 'home_main',
                'sort_order'  => 9,
                'is_visible'  => true,
            ],
            [
                'title'       => 'Giải Trí Cùng Tiểu Thuyết',
                'badge_text'  => 'Mọt Sách',
                'image'       => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&q=80&w=1200&h=500',
                'url'         => '/sieu-thi-sach/danh-muc/tieu-thuyet',
                'button_text' => 'Đọc Thử',
                'position'    => 'home_main',
                'sort_order'  => 10,
                'is_visible'  => true,
            ],
        ];

        // ── Mini Banners (Right sidebar) ────────────────────────────────
        $miniBanners = [
            [
                'title'      => 'Flash Sale Hôm Nay',
                'image'      => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=400&h=500',
                'url'        => '/sieu-thi-sach/tim-kiem',
                'position'   => 'home_mini',
                'sort_order' => 1,
                'is_visible' => true,
            ],
            [
                'title'      => 'Sách Bán Chạy',
                'image'      => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&q=80&w=400&h=500',
                'url'        => '/sieu-thi-sach/tim-kiem',
                'position'   => 'home_mini',
                'sort_order' => 2,
                'is_visible' => true,
            ],
            [
                'title'      => 'Sách Thể Loại Mới',
                'image'      => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&q=80&w=400&h=500',
                'url'        => '/sieu-thi-sach/tim-kiem',
                'position'   => 'home_mini',
                'sort_order' => 3,
                'is_visible' => true,
            ],
            [
                'title'      => 'Ưu Đãi Đặc Quyền',
                'image'      => 'https://images.unsplash.com/photo-1543269664-76bc3997d9ea?auto=format&fit=crop&q=80&w=400&h=500',
                'url'        => '/sieu-thi-sach/tim-kiem',
                'position'   => 'home_mini',
                'sort_order' => 4,
                'is_visible' => true,
            ],
            [
                'title'      => 'Phát Hành Sách',
                'image'      => 'https://images.unsplash.com/photo-1476275466078-4007374efbbe?auto=format&fit=crop&q=80&w=400&h=500',
                'url'        => '/sieu-thi-sach/tim-kiem',
                'position'   => 'home_mini',
                'sort_order' => 5,
                'is_visible' => true,
            ],
            [
                'title'      => 'Sách Trinh Thám',
                'image'      => 'https://images.unsplash.com/photo-1535905557558-afc4877a26fc?auto=format&fit=crop&q=80&w=400&h=500',
                'url'        => '/sieu-thi-sach/tim-kiem',
                'position'   => 'home_mini',
                'sort_order' => 6,
                'is_visible' => true,
            ],
            [
                'title'      => 'Sách Nuôi Dạy Trẻ',
                'image'      => 'https://images.unsplash.com/photo-1519682337058-a94d519337bc?auto=format&fit=crop&q=80&w=400&h=500',
                'url'        => '/sieu-thi-sach/tim-kiem',
                'position'   => 'home_mini',
                'sort_order' => 7,
                'is_visible' => true,
            ],
            [
                'title'      => 'Tâm Lý Con Nguyệt',
                'image'      => 'https://images.unsplash.com/photo-1495640388908-05fa85288e61?auto=format&fit=crop&q=80&w=400&h=500',
                'url'        => '/sieu-thi-sach/tim-kiem',
                'position'   => 'home_mini',
                'sort_order' => 8,
                'is_visible' => true,
            ],
            [
                'title'      => 'Best Seller Tuần',
                'image'      => 'https://images.unsplash.com/photo-1589998059171-988d887df646?auto=format&fit=crop&q=80&w=400&h=500',
                'url'        => '/sieu-thi-sach/tim-kiem',
                'position'   => 'home_mini',
                'sort_order' => 9,
                'is_visible' => true,
            ],
            [
                'title'      => 'Nâng Tầm Bạn Thân',
                'image'      => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&q=80&w=400&h=500',
                'url'        => '/sieu-thi-sach/tim-kiem',
                'position'   => 'home_mini',
                'sort_order' => 10,
                'is_visible' => true,
            ],
        ];

        foreach (array_merge($mainBanners, $miniBanners) as $banner) {
            $banner['image_url'] = $banner['image']; // Mirror for compatibility
            Banner::create($banner);
        }

        echo "Seeded " . count($mainBanners) . " main banners + " . count($miniBanners) . " mini banners.\n";
    }
}
