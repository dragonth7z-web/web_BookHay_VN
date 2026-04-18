<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;

class CollectionSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Collection::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $collections = [
            [
                'title'    => 'Sách Bán Chạy',
                'subtitle' => 'Top 100 quyển sách được yêu thích nhất.',
                'badge'    => 'TrendingNow',
                'image'    => 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&q=80&w=500&h=300',
                'url'      => '/sieu-thi-sach?sort=selling',
            ],
            [
                'title'    => 'Quà Tặng Ý Nghĩa',
                'subtitle' => 'Tinh hoa trí tuệ dành tặng người thân.',
                'badge'    => 'Gifts',
                'image'    => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=500&h=300',
                'url'      => '/sieu-thi-sach?sort=popular',
            ],
            [
                'title'    => 'Văn Học Kinh Điển',
                'subtitle' => 'Sống mãi với thời gian.',
                'badge'    => 'Classic',
                'image'    => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&q=80&w=500&h=300',
                'url'      => '/sieu-thi-sach/danh-muc/van-hoc',
            ],
            [
                'title'    => 'Phát Triển Bản Thân',
                'subtitle' => 'Kỹ năng sống cho cuộc đời hạnh phúc.',
                'badge'    => 'LevelUp',
                'image'    => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&q=80&w=500&h=300',
                'url'      => '/sieu-thi-sach/danh-muc/tam-ly-ky-nang-song',
            ],
            [
                'title'    => 'Khoa Học Kỹ Thuật',
                'subtitle' => 'Khám phá thế giới và vũ trụ bao la.',
                'badge'    => 'Science',
                'image'    => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&q=80&w=500&h=300',
                'url'      => '/sieu-thi-sach/danh-muc/khoa-hoc',
            ],
            [
                'title'    => 'Tiểu Thuyết Tình Yêu',
                'subtitle' => 'Những câu chuyện tình yêu lãng mạn, ngọt ngào.',
                'badge'    => 'Romance',
                'image'    => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=500&h=300',
                'url'      => '/sieu-thi-sach/danh-muc/tieu-thuyet',
            ],
            [
                'title'    => 'Truyện Tranh Manga',
                'subtitle' => 'Thế giới truyện tranh Nhật Bản siêu hấp dẫn.',
                'badge'    => 'Manga',
                'image'    => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=500&h=300',
                'url'      => '/sieu-thi-sach/danh-muc/manga',
            ],
            [
                'title'    => 'Sách Lịch Sử',
                'subtitle' => 'Quay ngược thời gian tìm hiểu cội nguồn.',
                'badge'    => 'History',
                'image'    => 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?auto=format&fit=crop&q=80&w=500&h=300',
                'url'      => '/sieu-thi-sach/danh-muc/lich-su',
            ],
            [
                'title'    => 'Sách Ngoại Ngữ',
                'subtitle' => 'Chinh phục mọi ngôn ngữ trên thế giới.',
                'badge'    => 'Language',
                'image'    => 'https://images.unsplash.com/photo-1546410531-bea4cada85a8?auto=format&fit=crop&q=80&w=500&h=300',
                'url'      => '/sieu-thi-sach/danh-muc/ngoai-ngu',
            ],
            [
                'title'    => 'Kinh Tế Khởi Nghiệp',
                'subtitle' => 'Bài học kinh doanh từ những người thành công.',
                'badge'    => 'Business',
                'image'    => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80&w=500&h=300',
                'url'      => '/sieu-thi-sach/danh-muc/kinh-te-khoi-nghiep',
            ]
        ];

        foreach ($collections as $index => $data) {
            Collection::create([
                'title'      => $data['title'],
                'subtitle'   => $data['subtitle'],
                'badge'      => $data['badge'],
                'image'      => $data['image'],
                'url'        => $data['url'],
                'is_visible' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
