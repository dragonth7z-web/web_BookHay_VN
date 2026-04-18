<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    public function run()
    {
        // 0. Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('book_author')->truncate();
        Book::truncate();
        Category::truncate();
        Author::truncate();
        Publisher::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ── 1. Categories (10 Main Categories) ───────────────────────────
        $categoriesConfig = [
            ['name' => 'Sách Trong Nước', 'icon' => 'menu_book', 'text_color' => '#C92127', 'bg_gradient' => 'linear-gradient(135deg, #ff6b6b, #ee5a24)', 'sub' => [
                'Văn học', 'Thiếu nhi', 'Tâm lý - Kỹ năng sống', 'Tiểu sử - Hồi ký'
            ]],
            ['name' => 'Sách Nước Ngoài', 'icon' => 'public', 'text_color' => '#3b82f6', 'bg_gradient' => 'linear-gradient(135deg, #667eea, #764ba2)', 'sub' => [
                'Tiểu thuyết', 'Non-fiction', 'Khoa học', 'Lịch sử'
            ]],
            ['name' => 'Manga & Truyện Tranh', 'icon' => 'draw', 'text_color' => '#ef4444', 'bg_gradient' => 'linear-gradient(135deg, #f093fb, #f5576c)', 'sub' => [
                'Manga Nhật', 'Truyện tranh Việt', 'Comic', 'Webtoon'
            ]],
            ['name' => 'Sách Giáo Khoa & Tham Khảo', 'icon' => 'school', 'text_color' => '#10b981', 'bg_gradient' => 'linear-gradient(135deg, #11998e, #38ef7d)', 'sub' => [
                'Tiểu học', 'THCS', 'THPT', 'Đại học'
            ]],
            ['name' => 'Sách Ngoại Ngữ', 'icon' => 'translate', 'text_color' => '#8b5cf6', 'bg_gradient' => 'linear-gradient(135deg, #a18cd1, #fbc2eb)', 'sub' => [
                'Tiếng Anh', 'Tiếng Nhật', 'Tiếng Trung', 'Tiếng Hàn'
            ]],
            ['name' => 'Kinh Tế - Quản Trị', 'icon' => 'trending_up', 'text_color' => '#f59e0b', 'bg_gradient' => 'linear-gradient(135deg, #fceabb, #f8b500)', 'sub' => [
                'Lãnh đạo', 'Marketing', 'Đầu tư', 'Khởi nghiệp'
            ]],
            ['name' => 'Kỹ Năng Sống - Phát Triển Bản Thân', 'icon' => 'self_improvement', 'text_color' => '#ec4899', 'bg_gradient' => 'linear-gradient(135deg, #FF9A8B, #FF6A88)', 'sub' => [
                'Tư duy', 'Quản lý thời gian', 'Giao tiếp', 'Hạnh phúc'
            ]],
            ['name' => 'Văn Học - Nghệ Thuật', 'icon' => 'palette', 'text_color' => '#6366f1', 'bg_gradient' => 'linear-gradient(135deg, #818cf8, #c7d2fe)', 'sub' => [
                'Thơ ca', 'Hội họa', 'Âm nhạc', 'Điện ảnh'
            ]],
            ['name' => 'Khoa Học - Công Nghệ', 'icon' => 'biotech', 'text_color' => '#06b6d4', 'bg_gradient' => 'linear-gradient(135deg, #a8edea, #fed6e3)', 'sub' => [
                'Tin học - AI', 'Thiên văn', 'Vật lý', 'Sinh học'
            ]],
            ['name' => 'Tôn Giáo - Tâm Linh - Triết Học', 'icon' => 'psychology', 'text_color' => '#78350f', 'bg_gradient' => 'linear-gradient(135deg, #E2D1C3, #FDFCFB)', 'sub' => [
                'Phật giáo', 'Công giáo', 'Triết học', 'Tâm linh'
            ]],
        ];

        foreach ($categoriesConfig as $cat) {
            $parent = Category::firstOrCreate(
                ['name' => $cat['name']],
                [
                    'slug' => Str::slug($cat['name']),
                    'is_visible' => true,
                    'image' => $cat['icon'],
                    'icon' => $cat['icon'],
                    'text_color' => $cat['text_color'],
                    'bg_gradient' => $cat['bg_gradient'],
                ]
            );

            foreach ($cat['sub'] as $subName) {
                Category::firstOrCreate(
                    ['name' => $subName, 'parent_id' => $parent->id],
                    ['slug' => Str::slug($subName), 'is_visible' => true]
                );
            }
        }

        // Category Badges
        Category::whereIn('name', ['Văn học', 'Tiểu thuyết', 'Marketing'])->update(['badge_text' => 'HOT', 'badge_color' => '#C92127']);
        Category::whereIn('name', ['Manga Nhật', 'Tin học - AI', 'Đầu tư'])->update(['badge_text' => 'MỚI', 'badge_color' => '#3b82f6']);
        Category::whereIn('name', ['Tâm lý - Kỹ năng sống', 'Lãnh đạo'])->update(['badge_text' => 'BÁN CHẠY', 'badge_color' => '#10b981']);

        // ── 2. Publishers ────────────────────────────────────────────────
        $nxbs = [
            ['name' => 'NXB Trẻ', 'is_partner' => true, 'gradient' => 'linear-gradient(135deg, #FF6B6B, #FFD93D)', 'icon' => 'castle'],
            ['name' => 'NXB Kim Đồng', 'is_partner' => true, 'gradient' => 'linear-gradient(135deg, #4facfe, #00f2fe)', 'icon' => 'auto_awesome'],
            ['name' => 'Nhã Nam', 'is_partner' => true, 'gradient' => 'linear-gradient(135deg, #667eea, #764ba2)', 'icon' => 'menu_book'],
            ['name' => 'First News', 'is_partner' => true, 'gradient' => 'linear-gradient(135deg, #6a11cb, #2575fc)', 'icon' => 'stars'],
            ['name' => 'NXB Giáo Dục', 'is_partner' => true, 'gradient' => 'linear-gradient(135deg, #11998e, #38ef7d)', 'icon' => 'school'],
            ['name' => 'Alphabooks', 'is_partner' => true, 'gradient' => 'linear-gradient(135deg, #f093fb, #f5576c)', 'icon' => 'lightbulb'],
            ['name' => 'Thái Hà Books', 'is_partner' => true, 'gradient' => 'linear-gradient(135deg, #a18cd1, #fbc2eb)', 'icon' => 'spa'],
            ['name' => 'NXB Phụ Nữ', 'is_partner' => false, 'gradient' => '', 'icon' => ''],
            ['name' => 'Đông A', 'is_partner' => false, 'gradient' => '', 'icon' => ''],
            ['name' => 'NXB Tổng Hợp TP.HCM', 'is_partner' => true, 'gradient' => 'linear-gradient(135deg, #fccb90, #d57eeb)', 'icon' => 'location_city'],
            ['name' => 'Văn Việt', 'is_partner' => false, 'gradient' => '', 'icon' => ''],
            ['name' => 'NXB Hội Nhà Văn', 'is_partner' => false, 'gradient' => '', 'icon' => ''],
        ];
        foreach ($nxbs as $nxb) {
            Publisher::firstOrCreate(['name' => $nxb['name']], [
                'slug' => Str::slug($nxb['name']),
                'email' => strtolower(Str::slug($nxb['name'])) . '@company.com',
                'phone' => '024 ' . rand(1000000, 9999999),
                'address' => 'Hà Nội / TP.HCM',
                'is_partner' => $nxb['is_partner'],
                'partner_gradient' => $nxb['gradient'],
                'partner_icon' => $nxb['icon'],
                'logo' => 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($nxb['name']),
            ]);
        }

        // ── 3. Authors ──────────────────────────────────────────────────
        $authors = [
            ['name' => 'Nguyễn Nhật Ánh', 'country' => 'Việt Nam'],
            ['name' => 'Tô Hoài', 'country' => 'Việt Nam'],
            ['name' => 'Nam Cao', 'country' => 'Việt Nam'],
            ['name' => 'Thạch Lam', 'country' => 'Việt Nam'],
            ['name' => 'J.K. Rowling', 'country' => 'Anh'],
            ['name' => 'Dale Carnegie', 'country' => 'Mỹ'],
            ['name' => 'James Clear', 'country' => 'Mỹ'],
            ['name' => 'Morgan Housel', 'country' => 'Mỹ'],
            ['name' => 'Paulo Coelho', 'country' => 'Brazil'],
            ['name' => 'Haruki Murakami', 'country' => 'Nhật Bản'],
            ['name' => 'Osho', 'country' => 'Ấn Độ'],
            ['name' => 'Jack Ma', 'country' => 'Trung Quốc'],
            ['name' => 'Steve Jobs', 'country' => 'Mỹ'],
            ['name' => 'Ngô Bảo Châu', 'country' => 'Việt Nam'],
            ['name' => 'Trần Đăng Khoa', 'country' => 'Việt Nam'],
        ];
        foreach ($authors as $a) {
            Author::firstOrCreate(['name' => $a['name']], [
                'slug' => Str::slug($a['name']),
                'country' => $a['country'],
                'biography' => 'Tác giả nổi tiếng với nhiều tác phẩm để đời.',
            ]);
        }

        // ── 4. Generate Books ────────────────────────────────────────────
        $unsplashBooks = [
            'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=400&h=600',
            'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=400&h=600',
            'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=400&h=600',
            'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&q=80&w=400&h=600',
            'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&q=80&w=400&h=600',
            'https://images.unsplash.com/photo-1589998059171-988d887df646?auto=format&fit=crop&q=80&w=400&h=600',
            'https://images.unsplash.com/photo-1495640388908-05fa85288e61?auto=format&fit=crop&q=80&w=400&h=600',
            'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&q=80&w=400&h=600',
            'https://images.unsplash.com/photo-1524578271613-d550eacf6090?auto=format&fit=crop&q=80&w=400&h=600',
            'https://images.unsplash.com/photo-1476275466078-4007374efbbe?auto=format&fit=crop&q=80&w=400&h=600',
            'https://images.unsplash.com/photo-1519682337058-a94d519337bc?auto=format&fit=crop&q=80&w=400&h=600',
            'https://images.unsplash.com/photo-1535905557558-afc4877a26fc?auto=format&fit=crop&q=80&w=400&h=600',
        ];

        $subCategories = Category::whereNotNull('parent_id')->get();
        $allNXBs = Publisher::all();
        $allAuthors = Author::all();

        // 4a. Specific Series Books ---------------------------------------
        $seriesBooks = [
            'One Piece' => [
                'category' => 'Manga Nhật',
                'count' => 10,
                'prefix' => 'One Piece - Tập ',
                'author' => 'Eiichiro Oda', // Note: I should add this author if not exists
                'publisher' => 'NXB Kim Đồng',
                'price' => 30000,
            ],
            'Nghìn Lẻ Một Đêm' => [
                'category' => 'Tiểu thuyết',
                'count' => 5,
                'prefix' => 'Nghìn Lẻ Một Đêm - Quyển ',
                'author' => 'Nhiều tác giả',
                'publisher' => 'Nhã Nam',
                'price' => 120000,
            ],
            'Sách Giáo Khoa Lớp 1' => [
                'category' => 'Tiểu học',
                'count' => 8,
                'items' => ['Toán 1', 'Tiếng Việt 1 - Tập 1', 'Tiếng Việt 1 - Tập 2', 'Tự nhiên và Xã hội 1', 'Đạo đức 1', 'Âm nhạc 1', 'Mỹ thuật 1', 'Hoạt động trải nghiệm 1'],
                'author' => 'Nhiều tác giả',
                'publisher' => 'NXB Giáo Dục',
                'price' => 15000,
            ],
            'The Way' => [
                'category' => 'Tư duy',
                'count' => 3,
                'prefix' => 'The Way - Tập ',
                'author' => 'Nhiều tác giả',
                'publisher' => 'Thái Hà Books',
                'price' => 150000,
            ]
        ];

        // Ensure Oda is in authors
        $oda = Author::firstOrCreate(['name' => 'Eiichiro Oda'], [
            'slug' => 'eiichiro-oda',
            'country' => 'Nhật Bản',
            'biography' => 'Tác giả manga nổi tiếng thế giới với bộ truyện One Piece.'
        ]);

        foreach ($seriesBooks as $seriesName => $config) {
            $cat = Category::where('name', $config['category'])->first();
            $pub = Publisher::where('name', $config['publisher'])->first();
            $author = Author::where('name', $config['author'])->first() ?? $allAuthors->random();

            for ($i = 1; $i <= $config['count']; $i++) {
                $title = isset($config['items']) ? $config['items'][$i-1] : ($config['prefix'] . $i);
                
                Book::create([
                    'sku' => 'SERIES-' . strtoupper(Str::slug($seriesName)) . '-' . $i,
                    'title' => $title,
                    'slug' => Str::slug($title) . '-' . strtolower(Str::random(4)),
                    'category_id' => $cat->id ?? $subCategories->random()->id,
                    'publisher_id' => $pub->id ?? $allNXBs->random()->id,
                    'cost_price' => $config['price'] * 0.5,
                    'original_price' => $config['price'],
                    'sale_price' => $config['price'] * 0.9,
                    'stock' => rand(50, 200),
                    'sold_count' => rand(100, 500),
                    'description' => "Nội dung cuốn $title thuộc bộ sách $seriesName.",
                    'short_description' => "Tóm tắt cuốn $title.",
                    'cover_image' => 'https://ui-avatars.com/api/?name=' . urlencode(substr($title, 0, 20)) . '&size=400&background=random&color=fff&font-size=0.33&length=4',
                    'isbn' => fake()->isbn13(),
                    'pages' => rand(100, 300),
                    'weight' => 300,
                    'cover_type' => 'paperback',
                    'language' => 'Tiếng Việt',
                    'published_year' => 2023,
                    'status' => 'in_stock',
                    'rating_avg' => 4.8,
                    'rating_count' => rand(100, 1000),
                    'is_featured' => true,
                ])->authors()->sync([$author->id]);
            }
        }

        // 4b. Random General Books ----------------------------------------
        $bookCounter = 0;

        // Real Vietnamese book titles pool per subcategory
        $titlePool = [
            'Văn học'           => ['Cây Cam Ngọt Của Tôi', 'Mắt Biếc', 'Cho Tôi Xin Một Vé Đi Tuổi Thơ', 'Tôi Thấy Hoa Vàng Trên Cỏ Xanh', 'Đất Rừng Phương Nam', 'Bến Không Chồng', 'Nỗi Buồn Chiến Tranh', 'Số Đỏ', 'Chí Phèo', 'Lão Hạc', 'Vợ Nhặt', 'Tắt Đèn'],
            'Thiếu nhi'         => ['Dế Mèn Phiêu Lưu Ký', 'Hoàng Tử Bé', 'Doraemon Học Tốt', 'Thám Tử Lừng Danh Conan', 'Cậu Bé Rừng Xanh', 'Pinocchio', 'Alice Ở Xứ Sở Thần Tiên', 'Gulliver Du Ký', 'Truyện Cổ Grimm', 'Truyện Cổ Andersen', 'Bạch Tuyết Và Bảy Chú Lùn', 'Cô Bé Lọ Lem'],
            'Tâm lý - Kỹ năng sống' => ['Đắc Nhân Tâm', 'Nghĩ Giàu Làm Giàu', 'Người Giàu Có Nhất Thành Babylon', 'Bí Mật Của May Mắn', 'Sức Mạnh Của Thói Quen', 'Mindset - Tư Duy Thành Công', 'Dám Bị Ghét', 'Ikigai - Đời Đáng Sống', 'Cân Bằng Cảm Xúc', 'Trí Tuệ Cảm Xúc', 'Nghệ Thuật Sống Tối Giản', 'Buông Bỏ Để Hạnh Phúc'],
            'Tiểu sử - Hồi ký'  => ['Steve Jobs', 'Elon Musk', 'Tôi Là Malala', 'Nhật Ký Anne Frank', 'Long March', 'Câu Chuyện Của Tôi - Michelle Obama', 'Shoe Dog - Phil Knight', 'Becoming', 'Open - Andre Agassi', 'Tự Truyện Benjamin Franklin', 'Sapiens', 'Hồi Ký Một Đời Người'],
            'Tiểu thuyết'       => ['Nhà Giả Kim', 'Bố Già', 'Chúa Tể Những Chiếc Nhẫn', 'Harry Potter Và Hòn Đá Phù Thủy', 'Trăm Năm Cô Đơn', 'Tội Ác Và Hình Phạt', 'Chiến Tranh Và Hòa Bình', 'Đồi Gió Hú', 'Jane Eyre', 'Kiêu Hãnh Và Định Kiến', 'Anh Em Nhà Karamazov', 'Những Người Khốn Khổ'],
            'Non-fiction'       => ['Sapiens - Lược Sử Loài Người', 'Homo Deus', '21 Bài Học Cho Thế Kỷ 21', 'Thế Giới Phẳng', 'Tư Duy Nhanh Và Chậm', 'Outliers', 'Blink', 'Freakonomics', 'Nudge', 'The Tipping Point', 'Guns Germs And Steel', 'The Power of Now'],
            'Khoa học'          => ['Vũ Trụ Trong Vỏ Hạt Dẻ', 'Lược Sử Thời Gian', 'Vật Lý Vui', 'Thế Giới Của Sophie', 'Ý Thức Là Gì', 'Tại Sao Ngủ', 'Não Bộ Kể Chuyện', 'Sự Sống Bí Ẩn', 'Hành Tinh Xanh', 'Khoa Học Của Hạnh Phúc', 'Trí Tuệ Nhân Tạo', 'Tương Lai Của Nhân Loại'],
            'Lịch sử'           => ['Lịch Sử Việt Nam', 'Đế Quốc La Mã', 'Thế Chiến II', 'Lịch Sử Trung Quốc', 'Lịch Sử Nhật Bản', 'Cách Mạng Pháp', 'Lịch Sử Hoa Kỳ', 'Đế Chế Mông Cổ', 'Ai Cập Cổ Đại', 'Hy Lạp Cổ Đại', 'Lịch Sử Kinh Tế Thế Giới', 'Lịch Sử Khoa Học'],
            'Manga Nhật'        => ['Naruto - Tập 1', 'Dragon Ball - Tập 1', 'Bleach - Tập 1', 'Attack on Titan - Tập 1', 'Death Note - Tập 1', 'Fullmetal Alchemist - Tập 1', 'Demon Slayer - Tập 1', 'My Hero Academia - Tập 1', 'Hunter x Hunter - Tập 1', 'Fairy Tail - Tập 1', 'Black Clover - Tập 1', 'Jujutsu Kaisen - Tập 1'],
            'Truyện tranh Việt' => ['Thần Đồng Đất Việt - Tập 1', 'Long Thần Tướng - Tập 1', 'Địa Ngục Môn - Tập 1', 'Truyện Tranh Lịch Sử VN', 'Bảo Vệ Rừng Xanh', 'Siêu Nhân Việt', 'Cậu Bé Rồng', 'Huyền Thoại Biển Đông', 'Anh Hùng Dân Tộc', 'Truyện Cổ Tích VN', 'Sử Ký Bằng Tranh', 'Việt Sử Giai Thoại'],
            'Comic'             => ['Batman - The Dark Knight', 'Spider-Man - Homecoming', 'Iron Man - Extremis', 'Captain America', 'Thor - God of Thunder', 'X-Men - Days of Future Past', 'Avengers - Infinity War', 'Justice League', 'Superman - Red Son', 'Wonder Woman', 'The Flash', 'Green Lantern'],
            'Webtoon'           => ['Tower of God - Tập 1', 'The God of High School', 'Noblesse', 'Solo Leveling - Tập 1', 'True Beauty', 'Lore Olympus', 'Unordinary', 'Eleceed', 'Omniscient Reader', 'Sweet Home', 'Bastard', 'I Love Yoo'],
            'Tiểu học'          => ['Toán 1', 'Tiếng Việt 1 - Tập 1', 'Tự Nhiên Xã Hội 1', 'Đạo Đức 1', 'Toán 2', 'Tiếng Việt 2', 'Toán 3', 'Tiếng Việt 3', 'Toán 4', 'Tiếng Việt 4', 'Toán 5', 'Tiếng Việt 5'],
            'THCS'              => ['Toán 6', 'Ngữ Văn 6', 'Lịch Sử 6', 'Địa Lý 6', 'Toán 7', 'Ngữ Văn 7', 'Vật Lý 7', 'Hóa Học 8', 'Sinh Học 8', 'Toán 9', 'Ngữ Văn 9', 'Vật Lý 9'],
            'THPT'              => ['Toán 10', 'Ngữ Văn 10', 'Vật Lý 10', 'Hóa Học 10', 'Sinh Học 10', 'Toán 11', 'Ngữ Văn 11', 'Toán 12', 'Ngữ Văn 12', 'Vật Lý 12', 'Hóa Học 12', 'Sinh Học 12'],
            'Đại học'           => ['Giáo Trình Toán Cao Cấp', 'Giáo Trình Vật Lý Đại Cương', 'Giáo Trình Hóa Học', 'Lập Trình C++', 'Cơ Sở Dữ Liệu', 'Mạng Máy Tính', 'Kinh Tế Vi Mô', 'Kinh Tế Vĩ Mô', 'Luật Dân Sự', 'Y Học Cơ Sở', 'Triết Học Mác-Lênin', 'Tư Tưởng Hồ Chí Minh'],
            'Tiếng Anh'         => ['English Grammar In Use', 'Oxford Word Skills', 'IELTS Cambridge 18', 'TOEIC 900', 'Vocabulary In Use', 'Cutting Edge', 'New Headway', 'American English File', 'Business English', 'English For Specific Purposes', 'Pronunciation Practice', 'Phrasal Verbs In Use'],
            'Tiếng Nhật'        => ['Minna No Nihongo 1', 'Minna No Nihongo 2', 'Genki I', 'Genki II', 'JLPT N5 Tổng Hợp', 'JLPT N4 Tổng Hợp', 'JLPT N3 Tổng Hợp', 'Kanji Master N3', 'Tiếng Nhật Thương Mại', 'Hán Tự Nhật Bản', 'Ngữ Pháp Tiếng Nhật', 'Từ Vựng Tiếng Nhật'],
            'Tiếng Trung'       => ['Hán Ngữ Chuẩn 1', 'Hán Ngữ Chuẩn 2', 'HSK 1 Tổng Hợp', 'HSK 2 Tổng Hợp', 'HSK 3 Tổng Hợp', 'Từ Điển Hán Việt', 'Ngữ Pháp Tiếng Trung', 'Tiếng Trung Thương Mại', 'Hán Tự Cơ Bản', 'Tiếng Trung Giao Tiếp', 'Luyện Nghe Tiếng Trung', 'Đọc Hiểu Tiếng Trung'],
            'Tiếng Hàn'         => ['Tiếng Hàn Tổng Hợp 1', 'Tiếng Hàn Tổng Hợp 2', 'TOPIK I Tổng Hợp', 'TOPIK II Tổng Hợp', 'Từ Vựng Tiếng Hàn', 'Ngữ Pháp Tiếng Hàn', 'Tiếng Hàn Giao Tiếp', 'Hàn Quốc Văn Hóa', 'K-Drama Vocabulary', 'Tiếng Hàn Thương Mại', 'Luyện Viết Tiếng Hàn', 'Phát Âm Tiếng Hàn'],
            'Lãnh đạo'          => ['Từ Tốt Đến Vĩ Đại', 'Lãnh Đạo Không Chức Danh', 'Nghệ Thuật Lãnh Đạo', 'Dám Lãnh Đạo', 'Lãnh Đạo Bằng Câu Hỏi', 'Nhà Lãnh Đạo Phục Vụ', 'Lãnh Đạo Cấp Độ 5', 'Tư Duy Lãnh Đạo', 'Lãnh Đạo Trong Khủng Hoảng', 'Lãnh Đạo Đội Nhóm', 'Lãnh Đạo Chiến Lược', 'Lãnh Đạo Sáng Tạo'],
            'Marketing'         => ['Marketing 4.0', 'Marketing 5.0', 'Vị Thế Thương Hiệu', 'Nghệ Thuật Bán Hàng', 'Content Marketing', 'Digital Marketing', 'SEO Thực Chiến', 'Social Media Marketing', 'Email Marketing', 'Growth Hacking', 'Brand Building', 'Customer Experience'],
            'Đầu tư'            => ['Nhà Đầu Tư Thông Minh', 'Cha Giàu Cha Nghèo', 'Dạy Con Làm Giàu', 'Chứng Khoán Cơ Bản', 'Phân Tích Kỹ Thuật', 'Đầu Tư Bất Động Sản', 'Crypto Cơ Bản', 'Quản Lý Tài Chính Cá Nhân', 'Tự Do Tài Chính', 'Đầu Tư Giá Trị', 'Warren Buffett Và Phân Tích Báo Cáo Tài Chính', 'Bước Đi Ngẫu Nhiên Trên Phố Wall'],
            'Khởi nghiệp'       => ['Zero To One', 'The Lean Startup', 'Khởi Nghiệp Tinh Gọn', 'Startup Nation', 'Blitzscaling', 'The Hard Thing About Hard Things', 'Rework', 'Tư Duy Khởi Nghiệp', 'Từ Ý Tưởng Đến Kinh Doanh', 'Startup Việt Nam', 'Gọi Vốn Đầu Tư', 'Xây Dựng Đội Nhóm Startup'],
            'Tư duy'            => ['Tư Duy Phản Biện', 'Tư Duy Hệ Thống', 'Tư Duy Sáng Tạo', 'Tư Duy Thiết Kế', 'Tư Duy Tích Cực', 'Tư Duy Chiến Lược', 'Tư Duy Toán Học', 'Tư Duy Khoa Học', 'Tư Duy Kinh Doanh', 'Tư Duy Lập Trình', 'Tư Duy Đột Phá', 'Tư Duy Phát Triển'],
            'Quản lý thời gian' => ['Getting Things Done', 'Deep Work', 'Eat That Frog', 'The One Thing', 'Essentialism', 'Atomic Habits', '4 Giờ Làm Việc Mỗi Tuần', 'Làm Chủ Thời Gian', 'Pomodoro Technique', 'Time Blocking', 'Quản Lý Năng Lượng', 'Sống Có Chủ Đích'],
            'Giao tiếp'         => ['Nghệ Thuật Nói Chuyện', 'Giao Tiếp Phi Bạo Lực', 'Lắng Nghe Chủ Động', 'Thuyết Trình Hiệu Quả', 'Viết Để Thuyết Phục', 'Ngôn Ngữ Cơ Thể', 'Giao Tiếp Trong Kinh Doanh', 'Kỹ Năng Đàm Phán', 'Xây Dựng Mối Quan Hệ', 'Giao Tiếp Liên Văn Hóa', 'Kỹ Năng Phỏng Vấn', 'Nghệ Thuật Thuyết Phục'],
            'Hạnh phúc'         => ['Hạnh Phúc Là Gì', 'Nghệ Thuật Sống', 'Sức Mạnh Của Hiện Tại', 'Thiền Định Cơ Bản', 'Yoga Và Sức Khỏe', 'Mindfulness', 'Chánh Niệm', 'Sống Chậm Lại', 'Buông Bỏ', 'Yêu Bản Thân', 'Chữa Lành Tâm Hồn', 'Hành Trình Nội Tâm'],
            'Thơ ca'            => ['Truyện Kiều', 'Thơ Xuân Diệu', 'Thơ Hàn Mặc Tử', 'Thơ Nguyễn Du', 'Thơ Tố Hữu', 'Thơ Chế Lan Viên', 'Thơ Huy Cận', 'Thơ Lưu Quang Vũ', 'Thơ Bùi Giáng', 'Thơ Nguyễn Bính', 'Thơ Đường', 'Thơ Haiku Nhật Bản'],
            'Hội họa'           => ['Lịch Sử Nghệ Thuật', 'Kỹ Thuật Vẽ Cơ Bản', 'Màu Sắc Trong Hội Họa', 'Vẽ Chân Dung', 'Vẽ Phong Cảnh', 'Vẽ Tĩnh Vật', 'Nghệ Thuật Trừu Tượng', 'Nghệ Thuật Hiện Đại', 'Danh Họa Thế Giới', 'Nghệ Thuật Việt Nam', 'Kỹ Thuật Sơn Dầu', 'Kỹ Thuật Màu Nước'],
            'Âm nhạc'           => ['Lý Thuyết Âm Nhạc Cơ Bản', 'Học Đàn Guitar', 'Học Đàn Piano', 'Học Đàn Violin', 'Lịch Sử Âm Nhạc', 'Nhạc Lý Nâng Cao', 'Hòa Âm Phối Khí', 'Sáng Tác Âm Nhạc', 'Âm Nhạc Dân Gian VN', 'Nhạc Jazz Cơ Bản', 'Nhạc Cổ Điển', 'Âm Nhạc Và Cảm Xúc'],
            'Điện ảnh'          => ['Ngôn Ngữ Điện Ảnh', 'Kịch Bản Phim', 'Đạo Diễn Phim', 'Quay Phim Cơ Bản', 'Dựng Phim', 'Lịch Sử Điện Ảnh', 'Điện Ảnh Việt Nam', 'Hollywood Và Thế Giới', 'Phân Tích Phim', 'Diễn Xuất', 'Âm Thanh Trong Phim', 'Hiệu Ứng Hình Ảnh'],
            'Tin học - AI'      => ['Lập Trình Python Cơ Bản', 'Machine Learning Cơ Bản', 'Deep Learning', 'Trí Tuệ Nhân Tạo', 'Data Science', 'Lập Trình Web', 'Cơ Sở Dữ Liệu', 'Mạng Máy Tính', 'An Ninh Mạng', 'Cloud Computing', 'DevOps Cơ Bản', 'Blockchain Cơ Bản'],
            'Thiên văn'         => ['Vũ Trụ Học', 'Hệ Mặt Trời', 'Các Vì Sao', 'Lỗ Đen', 'Thiên Hà', 'Vật Lý Thiên Văn', 'Lịch Sử Thiên Văn', 'Kính Thiên Văn', 'Khám Phá Vũ Trụ', 'Sao Chổi Và Thiên Thạch', 'Hành Tinh Ngoài Hệ Mặt Trời', 'Tương Lai Vũ Trụ'],
            'Vật lý'            => ['Vật Lý Đại Cương', 'Cơ Học Lượng Tử', 'Thuyết Tương Đối', 'Vật Lý Hạt Nhân', 'Điện Từ Học', 'Quang Học', 'Nhiệt Động Lực Học', 'Vật Lý Chất Rắn', 'Vật Lý Plasma', 'Vật Lý Thiên Văn', 'Vật Lý Lý Thuyết', 'Vật Lý Thực Nghiệm'],
            'Sinh học'          => ['Sinh Học Tế Bào', 'Di Truyền Học', 'Tiến Hóa', 'Sinh Thái Học', 'Vi Sinh Vật Học', 'Sinh Học Phân Tử', 'Giải Phẫu Học', 'Sinh Lý Học', 'Miễn Dịch Học', 'Công Nghệ Sinh Học', 'Sinh Học Biển', 'Bảo Tồn Đa Dạng Sinh Học'],
            'Phật giáo'         => ['Kinh Pháp Cú', 'Đường Xưa Mây Trắng', 'Thiền Định Phật Giáo', 'Tứ Diệu Đế', 'Bát Chánh Đạo', 'Kinh Địa Tạng', 'Kinh Quan Âm', 'Phật Giáo Và Khoa Học', 'Thiền Vipassana', 'Phật Pháp Căn Bản', 'Lịch Sử Phật Giáo', 'Phật Giáo Việt Nam'],
            'Công giáo'         => ['Kinh Thánh', 'Giáo Lý Công Giáo', 'Lịch Sử Giáo Hội', 'Thần Học Cơ Bản', 'Đức Tin Và Lý Trí', 'Các Thánh Tử Đạo VN', 'Mẹ Maria', 'Cầu Nguyện', 'Phụng Vụ', 'Đạo Đức Kitô Giáo', 'Kinh Thánh Cho Trẻ Em', 'Sống Đức Tin'],
            'Triết học'         => ['Triết Học Nhập Môn', 'Triết Học Hy Lạp', 'Triết Học Phương Đông', 'Triết Học Hiện Đại', 'Đạo Đức Học', 'Nhận Thức Luận', 'Siêu Hình Học', 'Triết Học Chính Trị', 'Triết Học Ngôn Ngữ', 'Triết Học Khoa Học', 'Triết Học Tôn Giáo', 'Triết Học Việt Nam'],
            'Tâm linh'          => ['Sức Mạnh Của Hiện Tại', 'Luật Hấp Dẫn', 'Bí Mật', 'Năng Lượng Vũ Trụ', 'Thiền Định Nâng Cao', 'Chakra Và Năng Lượng', 'Phong Thủy Cơ Bản', 'Tử Vi Đẩu Số', 'Kinh Dịch', 'Yoga Tâm Linh', 'Hành Trình Tâm Linh', 'Thức Tỉnh Tâm Linh'],
        ];

        foreach ($subCategories as $subCat) {
            $numBooksInCat = 12; // Đủ fill 2 hàng (5 cols) + dư
            for ($i = 0; $i < $numBooksInCat; $i++) {
                $bookCounter++;
                $originalPrice = rand(50, 400) * 1000;
                $salePrice = round($originalPrice * (rand(70, 95) / 100));

                // Dùng title pool nếu có, fallback generic
                $titles = $titlePool[$subCat->name] ?? [];
                if (isset($titles[$i])) {
                    $title = $titles[$i];
                } else {
                    $title = "Cuốn Sách Về " . $subCat->name . " " . ($i + 1);
                }

                $book = Book::create([
                    'sku'               => 'BOOK-' . str_pad($bookCounter, 5, '0', STR_PAD_LEFT),
                    'title'             => $title,
                    'slug'              => Str::slug($title) . '-' . strtolower(Str::random(4)),
                    'category_id'       => $subCat->id,
                    'publisher_id'      => $allNXBs->random()->id,
                    'cost_price'        => round($salePrice * 0.6),
                    'original_price'    => $originalPrice,
                    'sale_price'        => $salePrice,
                    'stock'             => rand(10, 500),
                    'sold_count'        => rand(50, 5000),
                    'description'       => "Đây là mô tả chi tiết cho cuốn sách \"$title\". Nội dung hấp dẫn, mang lại nhiều giá trị kiến thức và giải trí cho người đọc.",
                    'short_description' => "Tóm tắt ngắn gọn về cuốn sách $title.",
                    'cover_image'       => $unsplashBooks[array_rand($unsplashBooks)],
                    'isbn'              => '978-' . rand(100, 999) . '-' . rand(10, 99) . '-' . rand(10000, 99999) . '-' . rand(0, 9),
                    'pages'             => rand(150, 600),
                    'weight'            => rand(200, 700),
                    'cover_type'        => rand(0, 1) ? 'hardcover' : 'paperback',
                    'language'          => 'Tiếng Việt',
                    'published_year'    => rand(2015, 2026),
                    'status'            => 'in_stock',
                    'rating_avg'        => rand(35, 50) / 10,
                    'rating_count'      => rand(10, 2000),
                    'is_featured'       => ($i < 3),
                ]);

                $book->authors()->sync($allAuthors->random(rand(1, 2))->pluck('id'));
            }
        }

        echo "Seeded $bookCounter books into " . $subCategories->count() . " subcategories across " . Category::whereNull('parent_id')->count() . " main categories.\n";
    }
}
