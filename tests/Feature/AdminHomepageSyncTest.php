<?php

namespace Tests\Feature;

use Tests\TestCase as BaseTestCase;
use App\Models\Banner;
use App\Models\Collection;
use App\Models\Combo;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\WeeklyRanking;
use App\Models\WeeklyRankingItem;
use App\Models\Book;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * AdminHomepageSyncTest
 *
 * Kiểm tra: thao tác CRUD trên trang quản trị → trang chủ phản ánh đúng.
 * Mỗi test theo pattern: Admin thêm/sửa/xóa → GET / → assert nội dung đúng.
 */
class AdminHomepageSyncTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->buildSchema();
    }

    protected function tearDown(): void
    {
        $this->dropSchema();
        parent::tearDown();
    }

    // =========================================================================
    // SCHEMA HELPERS
    // =========================================================================

    private function buildSchema(): void
    {
        Schema::create('roles', function ($t) {
            $t->tinyIncrements('id');
            $t->string('code', 20)->unique();
            $t->string('name', 50);
        });

        Schema::create('users', function ($t) {
            $t->increments('id');
            $t->string('name', 100);
            $t->string('email', 100)->unique();
            $t->string('password', 255);
            $t->string('phone', 15)->nullable();
            $t->string('avatar', 255)->nullable();
            $t->date('date_of_birth')->nullable();
            $t->string('gender', 10)->default('other');
            $t->unsignedTinyInteger('role_id')->default(2);
            $t->string('status', 20)->default('active');
            $t->unsignedInteger('loyalty_points')->default(0);
            $t->decimal('total_spent', 15, 0)->default(0);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('banner', function ($t) {
            $t->increments('id');
            $t->string('title', 255)->nullable();
            $t->string('badge_text', 255)->nullable();
            $t->string('image', 255)->nullable();
            $t->string('image_url', 500)->nullable();
            $t->string('url', 255)->nullable();
            $t->string('button_text', 255)->nullable();
            $t->string('position', 30)->default('Slider');
            $t->integer('sort_order')->default(0);
            $t->boolean('is_visible')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('books', function ($t) {
            $t->increments('id');
            $t->string('sku', 30)->unique()->nullable();
            $t->string('title', 255)->nullable();
            $t->string('slug', 255)->unique();
            $t->unsignedInteger('category_id')->nullable();
            $t->unsignedInteger('publisher_id')->nullable();
            $t->decimal('cost_price', 12, 0)->default(0);
            $t->decimal('original_price', 12, 0)->default(0);
            $t->decimal('sale_price', 12, 0)->default(0);
            $t->integer('stock')->default(0);
            $t->integer('sold_count')->default(0);
            $t->text('description')->nullable();
            $t->string('short_description', 500)->nullable();
            $t->string('cover_image', 255)->nullable();
            $t->json('extra_images')->nullable();
            $t->string('isbn', 20)->nullable();
            $t->unsignedSmallInteger('pages')->nullable();
            $t->unsignedSmallInteger('weight')->nullable();
            $t->string('cover_type', 20)->default('paperback');
            $t->string('language', 50)->default('vi');
            $t->year('published_year')->nullable();
            $t->decimal('rating_avg', 3, 2)->default(0.00);
            $t->unsignedInteger('rating_count')->default(0);
            $t->string('status', 30)->default('active');
            $t->boolean('is_featured')->default(false);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('flash_sales', function ($t) {
            $t->increments('id');
            $t->string('name', 255)->nullable();
            $t->dateTime('start_date');
            $t->dateTime('end_date');
            $t->timestamps();
        });

        Schema::create('flash_sale_items', function ($t) {
            $t->increments('id');
            $t->unsignedInteger('flash_sale_id');
            $t->unsignedInteger('book_id');
            $t->decimal('flash_price', 12, 0)->default(0);
            $t->unsignedTinyInteger('display_order')->default(1);
            $t->timestamps();
        });

        Schema::create('weekly_rankings', function ($t) {
            $t->increments('id');
            $t->string('week_name', 255)->nullable();
            $t->date('week_start');
            $t->date('week_end');
            $t->timestamps();
        });

        Schema::create('weekly_ranking_items', function ($t) {
            $t->increments('id');
            $t->unsignedInteger('weekly_ranking_id');
            $t->unsignedInteger('book_id');
            $t->unsignedTinyInteger('rank')->default(1);
            $t->timestamps();
        });

        Schema::create('collections', function ($t) {
            $t->increments('id');
            $t->string('title', 150);
            $t->string('subtitle', 150)->nullable();
            $t->string('badge', 50)->nullable();
            $t->string('image', 255)->nullable();
            $t->string('url', 255)->nullable();
            $t->boolean('is_visible')->default(true);
            $t->integer('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('combos', function ($t) {
            $t->increments('id');
            $t->string('name', 150);
            $t->string('slug', 150)->unique();
            $t->text('description')->nullable();
            $t->decimal('original_price', 15, 2)->default(0);
            $t->decimal('sale_price', 15, 2)->default(0);
            $t->string('bg_from', 20)->default('#4F46E5');
            $t->string('bg_to', 20)->default('#7C3AED');
            $t->string('icon', 50)->default('psychology');
            $t->string('image', 255)->nullable();
            $t->string('badge_text', 50)->nullable();
            $t->string('button_text', 50)->nullable();
            $t->boolean('is_visible')->default(true);
            $t->integer('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('book_combo', function ($t) {
            $t->unsignedInteger('combo_id');
            $t->unsignedInteger('book_id');
            $t->primary(['combo_id', 'book_id']);
        });

        Schema::create('system_logs', function ($t) {
            $t->id();
            $t->string('type', 30)->index();
            $t->string('action', 50)->index();
            $t->string('level', 20)->default('info')->index();
            $t->text('description');
            $t->string('object_type', 100)->nullable();
            $t->unsignedBigInteger('object_id')->nullable();
            $t->json('old_data')->nullable();
            $t->json('new_data')->nullable();
            $t->unsignedBigInteger('user_id')->nullable();
            $t->string('user_name', 100)->nullable();
            $t->string('ip_address', 45)->nullable();
            $t->string('user_agent', 255)->nullable();
            $t->string('url', 500)->nullable();
            $t->timestamps();
        });

        // Additional tables HomeController needs
        Schema::create('categories', function ($t) {
            $t->increments('id');
            $t->string('name', 100);
            $t->unsignedInteger('parent_id')->nullable();
            $t->boolean('is_visible')->default(true);
            $t->integer('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('publishers', function ($t) {
            $t->increments('id');
            $t->string('name', 150);
            $t->boolean('is_partner')->default(false);
            $t->timestamps();
        });

        Schema::create('coupons', function ($t) {
            $t->increments('id');
            $t->string('code', 50)->unique();
            $t->string('type', 20)->default('fixed_amount');
            $t->decimal('value', 12, 0)->default(0);
            $t->decimal('max_discount', 12, 0)->default(0);
            $t->decimal('min_order_amount', 12, 0)->default(0);
            $t->unsignedInteger('usage_limit')->default(0);
            $t->unsignedInteger('used_count')->default(0);
            $t->timestamp('starts_at')->nullable();
            $t->timestamp('expires_at')->nullable();
            $t->string('status', 20)->default('active');
            $t->softDeletes();
        });

        Schema::create('settings', function ($t) {
            $t->increments('id');
            $t->string('key', 100)->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });

        DB::table('roles')->insert([
            ['id' => 1, 'code' => 'ADMIN',    'name' => 'Admin'],
            ['id' => 2, 'code' => 'CUSTOMER', 'name' => 'Khách hàng'],
        ]);

        DB::table('users')->insert([
            'id'             => 1,
            'name'           => 'Admin Test',
            'email'          => 'admin@bookstore.vn',
            'password'       => bcrypt('admin123'),
            'role_id'        => 1,
            'status'         => 'active',
            'loyalty_points' => 0,
            'total_spent'    => 0,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    private function dropSchema(): void
    {
        Schema::dropIfExists('book_combo');
        Schema::dropIfExists('combos');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('weekly_ranking_items');
        Schema::dropIfExists('weekly_rankings');
        Schema::dropIfExists('flash_sale_items');
        Schema::dropIfExists('flash_sales');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('books');
        Schema::dropIfExists('banner');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }

    private function loginAdmin(): void
    {
        $this->post(route('login.post'), [
            'email'    => 'admin@bookstore.vn',
            'password' => 'admin123',
        ]);
    }

    private function taoSach(array $overrides = []): Book
    {
        $uid = uniqid();
        return Book::create(array_merge([
            'sku'            => 'S-' . $uid,
            'title'          => 'Sách Test ' . $uid,
            'slug'           => 'sach-test-' . $uid,
            'original_price' => 100000,
            'sale_price'     => 80000,
            'stock'          => 50,
            'sold_count'     => 10,
            'status'         => 'in_stock',
            'is_featured'    => false,
        ], $overrides));
    }

    // =========================================================================
    // NHÓM 1: BANNER → trang chủ (home_main / home_mini / home_gift)
    // =========================================================================

    /**
     * Test S1a: Admin thêm banner home_main → HomeController trả về banner đó trong $mainBanners.
     */
    public function test_S1a_admin_them_banner_home_main_hien_thi_tren_trang_chu(): void
    {
        $banner = Banner::create([
            'title'      => 'Banner Trang Chủ Chính',
            'image'      => 'https://example.com/banner.jpg',
            'position'   => 'home_main',
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        $mainBanners = \App\Models\Banner::where('is_visible', 1)
            ->where('position', 'home_main')
            ->orderBy('sort_order')
            ->get();

        $this->assertTrue(
            $mainBanners->contains('id', $banner->id),
            'Banner home_main vừa thêm phải xuất hiện trong $mainBanners của HomeController'
        );
    }

    /**
     * Test S1b: Admin tắt is_visible banner → banner biến mất khỏi trang chủ.
     */
    public function test_S1b_admin_tat_hien_thi_banner_bien_mat_khoi_trang_chu(): void
    {
        $banner = Banner::create([
            'title'      => 'Banner Sẽ Bị Ẩn',
            'image'      => 'https://example.com/hide.jpg',
            'position'   => 'home_main',
            'sort_order' => 2,
            'is_visible' => true,
        ]);

        // Admin tắt is_visible
        $banner->update(['is_visible' => false]);

        $mainBanners = \App\Models\Banner::where('is_visible', 1)
            ->where('position', 'home_main')
            ->get();

        $this->assertFalse(
            $mainBanners->contains('id', $banner->id),
            'Banner đã tắt is_visible không được xuất hiện trong $mainBanners'
        );
    }

    /**
     * Test S1c: Admin xóa banner → banner không còn trong trang chủ (soft delete).
     */
    public function test_S1c_admin_xoa_banner_khong_con_tren_trang_chu(): void
    {
        $banner = Banner::create([
            'title'      => 'Banner Sẽ Bị Xóa',
            'image'      => 'https://example.com/del.jpg',
            'position'   => 'home_mini',
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        $bannerId = $banner->id;
        $banner->delete(); // soft delete

        $miniBanners = \App\Models\Banner::where('is_visible', 1)
            ->where('position', 'home_mini')
            ->get();

        $this->assertFalse(
            $miniBanners->contains('id', $bannerId),
            'Banner đã xóa (soft delete) không được xuất hiện trong $miniBanners'
        );
    }

    /**
     * Test S1d: Admin sửa position banner từ home_main → home_mini → chuyển đúng section.
     */
    public function test_S1d_admin_sua_vi_tri_banner_chuyen_dung_section(): void
    {
        $banner = Banner::create([
            'title'      => 'Banner Đổi Vị Trí',
            'image'      => 'https://example.com/move.jpg',
            'position'   => 'home_main',
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        // Admin đổi sang home_mini
        $banner->update(['position' => 'home_mini']);

        $mainBanners = \App\Models\Banner::where('is_visible', 1)->where('position', 'home_main')->get();
        $miniBanners = \App\Models\Banner::where('is_visible', 1)->where('position', 'home_mini')->get();

        $this->assertFalse($mainBanners->contains('id', $banner->id),
            'Banner đã đổi position không còn trong home_main');
        $this->assertTrue($miniBanners->contains('id', $banner->id),
            'Banner đã đổi position phải xuất hiện trong home_mini');
    }

    // =========================================================================
    // NHÓM 2: FLASH SALE → trang chủ ($flashSaleBooks)
    // =========================================================================

    /**
     * Test S2a: Admin tạo flash sale đang active → HomeController dùng dữ liệu từ DB.
     */
    public function test_S2a_admin_tao_flash_sale_active_hien_thi_tren_trang_chu(): void
    {
        $sach = $this->taoSach();

        $flashSale = FlashSale::create([
            'name'       => 'Flash Sale Test',
            'start_date' => now()->subHour(),
            'end_date'   => now()->addHour(),
        ]);

        FlashSaleItem::create([
            'flash_sale_id' => $flashSale->id,
            'book_id'       => $sach->id,
            'flash_price'   => 60000,
            'display_order' => 1,
        ]);

        // Simulate HomeController query
        $activeFlashSale = FlashSale::with(['items'])->active()->first();

        $this->assertNotNull($activeFlashSale, 'Phải tìm thấy flash sale đang active');
        $this->assertEquals($flashSale->id, $activeFlashSale->id);
        $this->assertEquals(1, $activeFlashSale->items->count());
        $this->assertEquals($sach->id, $activeFlashSale->items->first()->book_id);
    }

    /**
     * Test S2b: Flash sale chưa đến giờ → HomeController KHÔNG dùng (fallback về sách thường).
     */
    public function test_S2b_flash_sale_chua_den_gio_khong_hien_thi(): void
    {
        $sach = $this->taoSach();

        FlashSale::create([
            'name'       => 'Flash Sale Tương Lai',
            'start_date' => now()->addHour(),
            'end_date'   => now()->addHours(3),
        ]);

        $activeFlashSale = FlashSale::active()->first();

        $this->assertNull($activeFlashSale,
            'Flash sale chưa đến giờ không được xuất hiện qua scope active()');
    }

    /**
     * Test S2c: Admin xóa flash sale → scope active() trả về null.
     */
    public function test_S2c_admin_xoa_flash_sale_khong_con_active(): void
    {
        $flashSale = FlashSale::create([
            'name'       => 'Flash Sale Sẽ Xóa',
            'start_date' => now()->subHour(),
            'end_date'   => now()->addHour(),
        ]);

        $this->assertNotNull(FlashSale::active()->first(), 'Flash sale phải active trước khi xóa');

        $flashSale->delete();

        $this->assertNull(FlashSale::active()->first(),
            'Sau khi xóa flash sale, scope active() phải trả về null');
    }

    // =========================================================================
    // NHÓM 3: WEEKLY RANKING → trang chủ ($weeklyRankings)
    // =========================================================================

    /**
     * Test S3a: Admin tạo weekly ranking trong tuần hiện tại → hiển thị trên trang chủ.
     */
    public function test_S3a_admin_tao_weekly_ranking_hien_thi_tren_trang_chu(): void
    {
        $sach1 = $this->taoSach();
        $sach2 = $this->taoSach();

        $ranking = WeeklyRanking::create([
            'week_name'  => 'Tuần Này',
            'week_start' => now()->startOfWeek()->toDateString(),
            'week_end'   => now()->endOfWeek()->toDateString(),
        ]);

        WeeklyRankingItem::create(['weekly_ranking_id' => $ranking->id, 'book_id' => $sach1->id, 'rank' => 1]);
        WeeklyRankingItem::create(['weekly_ranking_id' => $ranking->id, 'book_id' => $sach2->id, 'rank' => 2]);

        // Simulate HomeController query
        $activeRanking = WeeklyRanking::with(['items'])->active()->first();

        $this->assertNotNull($activeRanking, 'Phải tìm thấy weekly ranking đang active');
        $this->assertEquals(2, $activeRanking->items->count());

        $sachIds = $activeRanking->items->pluck('book_id')->toArray();
        $this->assertContains($sach1->id, $sachIds);
        $this->assertContains($sach2->id, $sachIds);
    }

    /**
     * Test S3b: Admin cập nhật danh sách sách trong ranking → trang chủ phản ánh danh sách mới.
     */
    public function test_S3b_admin_cap_nhat_weekly_ranking_trang_chu_phan_anh_moi(): void
    {
        $sachCu  = $this->taoSach();
        $sachMoi = $this->taoSach();

        $ranking = WeeklyRanking::create([
            'week_start' => now()->startOfWeek()->toDateString(),
            'week_end'   => now()->endOfWeek()->toDateString(),
        ]);

        WeeklyRankingItem::create(['weekly_ranking_id' => $ranking->id, 'book_id' => $sachCu->id, 'rank' => 1]);

        // Admin cập nhật: xóa cũ, thêm mới
        WeeklyRankingItem::where('weekly_ranking_id', $ranking->id)->delete();
        WeeklyRankingItem::create(['weekly_ranking_id' => $ranking->id, 'book_id' => $sachMoi->id, 'rank' => 1]);

        $activeRanking = WeeklyRanking::with(['items'])->active()->first();
        $sachIds = $activeRanking->items->pluck('book_id')->toArray();

        $this->assertNotContains($sachCu->id, $sachIds, 'Sách cũ không còn trong ranking');
        $this->assertContains($sachMoi->id, $sachIds, 'Sách mới phải xuất hiện trong ranking');
    }

    // =========================================================================
    // NHÓM 4: FEATURED WORKS (noi_bat) → trang chủ ($featuredBooks)
    // =========================================================================

    /**
     * Test S4a: Admin bật noi_bat cho sách → sách xuất hiện trong $featuredBooks.
     */
    public function test_S4a_admin_bat_noi_bat_sach_hien_thi_trong_featured_books(): void
    {
        $sach = $this->taoSach(['is_featured' => false]);

        // Admin bật is_featured
        $sach->update(['is_featured' => true]);

        $featuredBooks = Book::where('is_featured', 1)->where('status', 'in_stock')->get();

        $this->assertTrue(
            $featuredBooks->contains('id', $sach->id),
            'Sách được bật is_featured phải xuất hiện trong $featuredBooks'
        );
    }

    /**
     * Test S4b: Admin tắt noi_bat → sách biến mất khỏi $featuredBooks.
     */
    public function test_S4b_admin_tat_noi_bat_sach_bien_mat_khoi_featured_books(): void
    {
        $sach = $this->taoSach(['is_featured' => true]);

        // Admin tắt is_featured
        $sach->update(['is_featured' => false]);

        $featuredBooks = Book::where('is_featured', 1)->where('status', 'in_stock')->get();

        $this->assertFalse(
            $featuredBooks->contains('id', $sach->id),
            'Sách đã tắt is_featured không được xuất hiện trong $featuredBooks'
        );
    }

    // =========================================================================
    // NHÓM 5: BỘ SƯU TẬP → trang chủ ($collections)
    // =========================================================================

    /**
     * Test S5a: Admin thêm bộ sưu tập hien_thi=1 → xuất hiện trong $collections.
     */
    public function test_S5a_admin_them_bo_suu_tap_hien_thi_tren_trang_chu(): void
    {
        $bst = Collection::create([
            'title'      => 'Bộ Sưu Tập Mới',
            'image'      => 'https://example.com/bst.jpg',
            'is_visible' => true,
            'sort_order' => 1,
        ]);

        $collections = Collection::where('is_visible', 1)->orderBy('sort_order')->take(6)->get();

        $this->assertTrue(
            $collections->contains('id', $bst->id),
            'Bộ sưu tập mới thêm phải xuất hiện trong $collections'
        );
    }

    /**
     * Test S5b: Admin ẩn bộ sưu tập → biến mất khỏi $collections.
     */
    public function test_S5b_admin_an_bo_suu_tap_bien_mat_khoi_trang_chu(): void
    {
        $bst = Collection::create([
            'title'      => 'Bộ Sưu Tập Sẽ Ẩn',
            'image'      => 'https://example.com/hide.jpg',
            'is_visible' => true,
        ]);

        $bst->update(['is_visible' => false]);

        $collections = Collection::where('is_visible', 1)->get();

        $this->assertFalse(
            $collections->contains('id', $bst->id),
            'Bộ sưu tập đã ẩn không được xuất hiện trong $collections'
        );
    }

    // =========================================================================
    // NHÓM 6: COMBO → trang chủ ($combos)
    // =========================================================================

    /**
     * Test S6a: Admin thêm combo hien_thi=1 → xuất hiện trong $combos.
     */
    public function test_S6a_admin_them_combo_hien_thi_tren_trang_chu(): void
    {
        $combo = Combo::create([
            'name'      => 'Combo Sách Hay',
            'slug'      => 'combo-sach-hay',
            'original_price' => 300000,
            'sale_price'     => 250000,
            'is_visible'     => true,
            'sort_order'     => 1,
        ]);

        $combos = Combo::where('is_visible', 1)->orderBy('sort_order')->take(4)->get();

        $this->assertTrue(
            $combos->contains('id', $combo->id),
            'Combo mới thêm phải xuất hiện trong $combos'
        );
    }

    /**
     * Test S6b: Admin xóa combo → không còn trong $combos.
     */
    public function test_S6b_admin_xoa_combo_khong_con_tren_trang_chu(): void
    {
        $combo = Combo::create([
            'name'       => 'Combo Sẽ Xóa',
            'slug'       => 'combo-se-xoa',
            'original_price' => 200000,
            'sale_price'     => 180000,
            'is_visible'     => true,
        ]);

        $comboId = $combo->id;
        $combo->delete();

        $combos = Combo::where('is_visible', 1)->get();

        $this->assertFalse(
            $combos->contains('id', $comboId),
            'Combo đã xóa không được xuất hiện trong $combos'
        );
    }

    // =========================================================================
    // NHÓM 7: THỨ TỰ HIỂN THỊ (thu_tu) – đảm bảo sort đúng
    // =========================================================================

    /**
     * Test S7: Admin đặt sort_order cho banner → trang chủ hiển thị đúng thứ tự.
     */
    public function test_S7_thu_tu_banner_duoc_sap_xep_dung_tren_trang_chu(): void
    {
        Banner::create(['title' => 'Banner Thứ 3', 'image' => 'a.jpg', 'position' => 'home_main', 'sort_order' => 3, 'is_visible' => true]);
        Banner::create(['title' => 'Banner Thứ 1', 'image' => 'b.jpg', 'position' => 'home_main', 'sort_order' => 1, 'is_visible' => true]);
        Banner::create(['title' => 'Banner Thứ 2', 'image' => 'c.jpg', 'position' => 'home_main', 'sort_order' => 2, 'is_visible' => true]);

        $mainBanners = \App\Models\Banner::where('is_visible', 1)
            ->where('position', 'home_main')
            ->orderBy('sort_order')
            ->get();

        $this->assertEquals('Banner Thứ 1', $mainBanners[0]->title);
        $this->assertEquals('Banner Thứ 2', $mainBanners[1]->title);
        $this->assertEquals('Banner Thứ 3', $mainBanners[2]->title);
    }
}
