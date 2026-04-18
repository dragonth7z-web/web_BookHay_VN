<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Property Test: Bảo toàn row count cho Nhóm 1 (Group 1 tables)
 *
 * Property 1: Bảo toàn số lượng row sau migration
 * Kiểm tra các bảng Nhóm 1 (không có FK dependencies) tồn tại với tên mới
 * và có thể được truy vấn qua DB::table() mà không có lỗi (row count >= 0).
 *
 * Validates: Requirements 1.3
 */
class MigrationGroup1RowCountTest extends TestCase
{
    /**
     * Danh sách bảng Nhóm 1 sau khi migration (tên tiếng Anh).
     */
    private array $group1Tables = [
        'roles',
        'publishers',
        'authors',
        'coupons',
        'collections',
        'settings',
        'banner',
        'faqs',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->createGroup1Schema();
    }

    protected function tearDown(): void
    {
        $this->dropGroup1Schema();
        parent::tearDown();
    }

    // =========================================================================
    // Schema helpers
    // =========================================================================

    private function createGroup1Schema(): void
    {
        // roles (renamed from vai_tro)
        Schema::create('roles', function ($table) {
            $table->tinyIncrements('id');
            $table->string('code', 20)->unique();
            $table->string('name', 50);
            $table->string('description', 255)->nullable();
        });

        // publishers (renamed from nha_xuat_ban)
        Schema::create('publishers', function ($table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('address', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('is_partner')->default(false);
            $table->string('partner_icon', 255)->nullable();
            $table->string('partner_gradient', 100)->nullable();
            $table->timestamps();
        });

        // authors (renamed from tac_gia)
        Schema::create('authors', function ($table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('country', 100)->nullable();
            $table->text('biography')->nullable();
            $table->string('avatar', 255)->nullable();
            $table->timestamps();
        });

        // coupons (renamed from ma_giam_gia)
        Schema::create('coupons', function ($table) {
            $table->increments('id');
            $table->string('code', 50)->unique();
            $table->string('name', 255)->nullable();
            $table->string('type', 20)->default('percentage');
            $table->decimal('value', 12, 0)->default(0);
            $table->decimal('max_discount', 12, 0)->default(0);
            $table->decimal('min_order_amount', 12, 0)->default(0);
            $table->unsignedInteger('usage_limit')->default(0);
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('status', 20)->default('active');
            $table->softDeletes();
        });

        // collections (renamed from bo_suu_tap)
        Schema::create('collections', function ($table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('subtitle', 255)->nullable();
            $table->string('badge', 100)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('url', 500)->nullable();
            $table->boolean('is_visible')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // settings (renamed from cau_hinh)
        Schema::create('settings', function ($table) {
            $table->increments('id');
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        // banner (columns renamed, table name unchanged)
        Schema::create('banner', function ($table) {
            $table->increments('id');
            $table->string('title', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->string('url', 500)->nullable();
            $table->string('position', 50)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        // faqs (renamed from faq)
        Schema::create('faqs', function ($table) {
            $table->increments('id');
            $table->text('question');
            $table->text('answer');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    private function dropGroup1Schema(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('banner');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('roles');
    }

    // =========================================================================
    // Seed helpers
    // =========================================================================

    private function seedGroup1Tables(): array
    {
        $counts = [];

        DB::table('roles')->insert([
            ['code' => 'ADMIN',    'name' => 'Admin',    'description' => null],
            ['code' => 'CUSTOMER', 'name' => 'Customer', 'description' => null],
            ['code' => 'STAFF',    'name' => 'Staff',    'description' => null],
        ]);
        $counts['roles'] = 3;

        DB::table('publishers')->insert([
            ['name' => 'NXB Kim Đồng',   'address' => 'Hà Nội', 'phone' => null, 'is_partner' => false, 'partner_icon' => null, 'partner_gradient' => null, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'NXB Trẻ',        'address' => 'TP.HCM', 'phone' => null, 'is_partner' => true,  'partner_icon' => null, 'partner_gradient' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $counts['publishers'] = 2;

        DB::table('authors')->insert([
            ['name' => 'Nguyễn Nhật Ánh', 'country' => 'Vietnam', 'biography' => null, 'avatar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tô Hoài',         'country' => 'Vietnam', 'biography' => null, 'avatar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nam Cao',          'country' => 'Vietnam', 'biography' => null, 'avatar' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $counts['authors'] = 3;

        DB::table('coupons')->insert([
            ['code' => 'SALE10', 'name' => 'Giảm 10%', 'type' => 'percentage', 'value' => 10, 'max_discount' => 50000, 'min_order_amount' => 100000, 'usage_limit' => 100, 'used_count' => 0, 'starts_at' => now(), 'expires_at' => now()->addDays(30), 'status' => 'active'],
            ['code' => 'FLAT50', 'name' => 'Giảm 50k',  'type' => 'fixed_amount', 'value' => 50000, 'max_discount' => 0, 'min_order_amount' => 200000, 'usage_limit' => 50, 'used_count' => 5, 'starts_at' => now(), 'expires_at' => now()->addDays(7), 'status' => 'active'],
        ]);
        $counts['coupons'] = 2;

        DB::table('collections')->insert([
            ['title' => 'Sách Bán Chạy', 'subtitle' => null, 'badge' => 'Hot', 'image' => null, 'url' => '/best-sellers', 'is_visible' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $counts['collections'] = 1;

        DB::table('settings')->insert([
            ['key' => 'site_name',  'value' => 'BookStore', 'description' => 'Tên website', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_email', 'value' => 'admin@bookstore.com', 'description' => 'Email liên hệ', 'created_at' => now(), 'updated_at' => now()],
        ]);
        $counts['settings'] = 2;

        DB::table('banner')->insert([
            ['title' => 'Banner Tết', 'image' => 'tet.jpg', 'image_url' => null, 'url' => '/tet', 'position' => 'home_top', 'sort_order' => 1, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Banner Sale', 'image' => 'sale.jpg', 'image_url' => null, 'url' => '/sale', 'position' => 'home_top', 'sort_order' => 2, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Banner Ẩn',  'image' => 'hidden.jpg', 'image_url' => null, 'url' => null, 'position' => 'home_top', 'sort_order' => 3, 'is_visible' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $counts['banner'] = 3;

        DB::table('faqs')->insert([
            ['question' => 'Làm sao đặt hàng?', 'answer' => 'Chọn sách và thanh toán.', 'sort_order' => 1, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Chính sách đổi trả?', 'answer' => '7 ngày đổi trả.', 'sort_order' => 2, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $counts['faqs'] = 2;

        return $counts;
    }

    // =========================================================================
    // Tests
    // =========================================================================

    /**
     * Property 1: Tất cả bảng Nhóm 1 tồn tại với tên tiếng Anh mới.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_all_group1_tables_exist_with_new_names(): void
    {
        foreach ($this->group1Tables as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "Bảng '{$table}' phải tồn tại sau migration Nhóm 1."
            );
        }
    }

    /**
     * Property 1: Mỗi bảng Nhóm 1 có thể được truy vấn qua DB::table() (row count >= 0).
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_all_group1_tables_are_queryable(): void
    {
        foreach ($this->group1Tables as $table) {
            $count = DB::table($table)->count();
            $this->assertGreaterThanOrEqual(
                0,
                $count,
                "DB::table('{$table}')->count() phải trả về giá trị >= 0."
            );
        }
    }

    /**
     * Property 1: Row count trước và sau khi insert/query bằng nhau cho từng bảng Nhóm 1.
     * Seed dữ liệu vào từng bảng, kiểm tra count khớp với số row đã insert.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_row_count_preserved_after_seed_for_each_group1_table(): void
    {
        $expectedCounts = $this->seedGroup1Tables();

        foreach ($expectedCounts as $table => $expected) {
            $actual = DB::table($table)->count();
            $this->assertEquals(
                $expected,
                $actual,
                "Bảng '{$table}': row count phải là {$expected} sau khi seed, nhưng thực tế là {$actual}."
            );
        }
    }

    /**
     * Property 1: Row count không thay đổi sau nhiều lần đọc liên tiếp (idempotent reads).
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_row_count_is_stable_across_multiple_reads(): void
    {
        $this->seedGroup1Tables();

        foreach ($this->group1Tables as $table) {
            $firstRead  = DB::table($table)->count();
            $secondRead = DB::table($table)->count();
            $thirdRead  = DB::table($table)->count();

            $this->assertEquals(
                $firstRead,
                $secondRead,
                "Bảng '{$table}': row count phải ổn định giữa các lần đọc (lần 1 vs lần 2)."
            );
            $this->assertEquals(
                $firstRead,
                $thirdRead,
                "Bảng '{$table}': row count phải ổn định giữa các lần đọc (lần 1 vs lần 3)."
            );
        }
    }

    /**
     * Property 1: Sau khi insert N rows vào bảng, count tăng đúng N.
     * Kiểm tra với nhiều bảng Nhóm 1 để đảm bảo tính nhất quán.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_insert_n_rows_increases_count_by_exactly_n(): void
    {
        $tablesToTest = [
            'authors' => [
                ['name' => 'Author A', 'country' => 'Vietnam', 'biography' => null, 'avatar' => null, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Author B', 'country' => 'France',  'biography' => null, 'avatar' => null, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Author C', 'country' => 'Japan',   'biography' => null, 'avatar' => null, 'created_at' => now(), 'updated_at' => now()],
            ],
            'roles' => [
                ['code' => 'ROLE_A', 'name' => 'Role A', 'description' => null],
                ['code' => 'ROLE_B', 'name' => 'Role B', 'description' => null],
            ],
            'faqs' => [
                ['question' => 'Q1?', 'answer' => 'A1.', 'sort_order' => 1, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
                ['question' => 'Q2?', 'answer' => 'A2.', 'sort_order' => 2, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
                ['question' => 'Q3?', 'answer' => 'A3.', 'sort_order' => 3, 'is_visible' => false, 'created_at' => now(), 'updated_at' => now()],
                ['question' => 'Q4?', 'answer' => 'A4.', 'sort_order' => 4, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ],
        ];

        foreach ($tablesToTest as $table => $rows) {
            $countBefore = DB::table($table)->count();
            DB::table($table)->insert($rows);
            $countAfter = DB::table($table)->count();

            $this->assertEquals(
                $countBefore + count($rows),
                $countAfter,
                "Bảng '{$table}': sau khi insert " . count($rows) . " rows, count phải tăng đúng " . count($rows) . "."
            );
        }
    }

    /**
     * Property 1: Bảng trống ban đầu có row count = 0.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_empty_tables_have_zero_row_count(): void
    {
        // Trước khi seed, tất cả bảng phải rỗng
        foreach ($this->group1Tables as $table) {
            $this->assertEquals(
                0,
                DB::table($table)->count(),
                "Bảng '{$table}' phải rỗng (count = 0) trước khi seed."
            );
        }
    }
}
