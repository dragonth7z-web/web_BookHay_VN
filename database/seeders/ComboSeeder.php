<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Combo;
use App\Models\Book;
use Illuminate\Support\Str;

class ComboSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Combo::truncate();
        \Illuminate\Support\Facades\DB::table('book_combo')->truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $combosData = [
            // --- GENRE COMBOS ---
            [
                'type' => 'genre_combo',
                'name' => 'Combo Sách Kinh Tế Start-up',
                'description' => 'Những cuốn sách gối đầu giường cho các nhà khởi nghiệp trẻ.',
                'badge' => 'KHỞI NGHIỆP',
                'btn' => 'Mua Combo',
                'icon' => 'trending_up',
                'bg_from' => '#10b981', // emerald
                'bg_to' => '#059669',
                'img' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&q=80&w=400&h=400',
                'cat_search' => 'Kinh Tế - Quản Trị'
            ],
            [
                'type' => 'genre_combo',
                'name' => 'Combo Best-Seller Văn Học',
                'description' => 'Tuyển tập những tác phẩm văn học nổi bật và bán chạy nhất năm.',
                'badge' => 'BEST SELLER',
                'btn' => 'Sở Hữu Ngay',
                'icon' => 'auto_stories',
                'bg_from' => '#ec4899', // pink
                'bg_to' => '#be185d',
                'img' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&q=80&w=400&h=400',
                'cat_search' => 'Văn Học - Nghệ Thuật'
            ],
            [
                'type' => 'genre_combo',
                'name' => 'Combo Khoa Học Công Nghệ',
                'description' => 'Dành cho những người đam mê tìm hiểu công nghệ và trí tuệ nhân tạo (AI).',
                'badge' => 'KHOA HỌC',
                'btn' => 'Khám Phá',
                'icon' => 'biotech',
                'bg_from' => '#3b82f6', // blue
                'bg_to' => '#1d4ed8',
                'img' => 'https://images.unsplash.com/photo-1535905557558-afc4877a26fc?auto=format&fit=crop&q=80&w=400&h=400',
                'cat_search' => 'Khoa Học - Công Nghệ'
            ],
            // --- SERIES ---
            [
                'type' => 'series',
                'name' => 'One Piece - Đảo Hải Tặc',
                'description' => 'Hành trình trở thành Vua Hải Tặc của Luffy và đồng đội. Trọn bộ 10 tập đầu tiên.',
                'badge' => 'HOT MANGA',
                'btn' => 'Mua Trọn Bộ',
                'icon' => 'sailing',
                'bg_from' => '#f59e0b',
                'bg_to' => '#d97706',
                'img' => 'https://images.unsplash.com/photo-1578632738908-452193213d86?auto=format&fit=crop&q=80&w=400&h=400',
                'search' => 'One Piece - Tập '
            ],
            [
                'type' => 'series',
                'name' => 'Nghìn Lẻ Một Đêm',
                'description' => 'Những câu chuyện huyền bí của nàng Scheherazade. Trọn bộ 5 quyển đặc biệt.',
                'badge' => 'KINH ĐIỂN',
                'btn' => 'Sở Hữu Ngay',
                'icon' => 'auto_stories',
                'bg_from' => '#6366f1',
                'bg_to' => '#4338ca',
                'img' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&q=80&w=400&h=400',
                'search' => 'Nghìn Lẻ Một Đêm - Quyển '
            ],
            [
                'type' => 'series',
                'name' => 'Bộ Sách Giáo Khoa Lớp 1',
                'description' => 'Đầy đủ tất cả các môn học theo chương trình mới nhất. Chuẩn bị hành trang cho bé.',
                'badge' => 'GIÁO DỤC',
                'btn' => 'Mua Cho Bé',
                'icon' => 'school',
                'bg_from' => '#8b5cf6',
                'bg_to' => '#6d28d9',
                'img' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&q=80&w=400&h=400',
                'search' => 'SERIES-SACH-GIAO-KHOA-LOP-1'
            ],
            // --- THÊM MỚI ---
            [
                'type' => 'genre_combo',
                'name' => 'Combo Phát Triển Bản Thân',
                'description' => 'Những cuốn sách giúp bạn nâng cao tư duy, kỹ năng và thói quen tích cực.',
                'badge' => 'BEST SELLER',
                'btn' => 'Khám Phá',
                'icon' => 'self_improvement',
                'bg_from' => '#f97316',
                'bg_to' => '#ea580c',
                'img' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&q=80&w=400&h=400',
                'cat_search' => 'Kỹ Năng Sống'
            ],
            [
                'type' => 'genre_combo',
                'name' => 'Combo Tâm Lý - Sức Khỏe Tinh Thần',
                'description' => 'Bộ sách giúp bạn hiểu bản thân, vượt qua lo âu và sống cân bằng hơn.',
                'badge' => 'HOT',
                'btn' => 'Xem Ngay',
                'icon' => 'psychology',
                'bg_from' => '#14b8a6',
                'bg_to' => '#0d9488',
                'img' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&q=80&w=400&h=400',
                'cat_search' => 'Tâm Lý'
            ],
            [
                'type' => 'genre_combo',
                'name' => 'Combo Lịch Sử - Địa Lý',
                'description' => 'Khám phá lịch sử Việt Nam và thế giới qua những trang sách hấp dẫn.',
                'badge' => 'KIẾN THỨC',
                'btn' => 'Tìm Hiểu',
                'icon' => 'public',
                'bg_from' => '#a16207',
                'bg_to' => '#854d0e',
                'img' => 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?auto=format&fit=crop&q=80&w=400&h=400',
                'cat_search' => 'Lịch Sử'
            ],
            [
                'type' => 'author_combo',
                'name' => 'Combo Thiếu Nhi - Truyện Tranh',
                'description' => 'Những bộ truyện tranh và sách thiếu nhi được yêu thích nhất dành cho bé.',
                'badge' => 'CHO BÉ',
                'btn' => 'Mua Cho Bé',
                'icon' => 'child_care',
                'bg_from' => '#e879f9',
                'bg_to' => '#c026d3',
                'img' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=400&h=400',
                'cat_search' => 'Thiếu Nhi'
            ],
        ];

        foreach ($combosData as $index => $data) {
            // Find books for this combo
            if (isset($data['search'])) {
                if (Str::startsWith($data['search'], 'SERIES-')) {
                    $books = Book::where('sku', 'like', $data['search'] . '%')->get();
                } else {
                    $books = Book::where('title', 'like', $data['search'] . '%')->get();
                }
            } elseif (isset($data['cat_search'])) {
                 // Genre Combos: get 3-5 random books from that category tree
                 $catIds = \App\Models\Category::where('name', 'like', '%' . $data['cat_search'] . '%')
                                               ->orWhereHas('parent', function($q) use ($data) {
                                                   $q->where('name', 'like', '%' . $data['cat_search'] . '%');
                                               })->pluck('id');
                 if ($catIds->count() > 0) {
                     $books = Book::whereIn('category_id', $catIds)->inRandomOrder()->take(rand(3, 5))->get();
                 } else {
                     $books = Book::inRandomOrder()->take(4)->get();
                 }
            } else {
                 $books = Book::inRandomOrder()->take(4)->get();
            }

            if ($books->isEmpty()) {
                // Fallback to random if search fails (shouldn't happen with our seeders)
                $books = Book::inRandomOrder()->take(5)->get();
            }

            $original_price = $books->sum('original_price');
            $sale_price = $books->sum('sale_price') * ($data['type'] === 'genre_combo' ? 0.85 : 0.95); // 15% off for genre combo, 5% off for series

            $combo = Combo::create([
                'type' => $data['type'],
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'badge_text' => $data['badge'],
                'button_text' => $data['btn'],
                'description' => $data['description'],
                'original_price' => $original_price,
                'sale_price' => $sale_price,
                'bg_from' => $data['bg_from'],
                'bg_to' => $data['bg_to'],
                'icon' => $data['icon'],
                'image' => $data['img'],
                'is_visible' => true,
                'sort_order' => $index + 1,
            ]);

            $combo->books()->attach($books->pluck('id'));
        }
    }
}
