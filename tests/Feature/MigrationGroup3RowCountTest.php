<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Property Test: Bảo toàn row count và enum values cho Nhóm 3 (Group 3 tables)
 *
 * Property 1: Bảo toàn số lượng row sau migration
 * Property 2: Enum values được convert đúng
 * Kiểm tra row count và enum values sau migration cho `books`, `orders`, `notifications`
 *
 * Validates: Requirements 1.3, 2.1, 2.2, 2.3, 2.4, 2.5
 */
class MigrationGroup3RowCountTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        $this->dropSchema();
        parent::tearDown();
    }

    // =========================================================================
    // Schema helpers
    // =========================================================================

    private function createSchema(): void
    {
        // --- Nhóm 1 dependencies ---

        Schema::create('roles', function ($table) {
            $table->tinyIncrements('id');
            $table->string('code', 20)->unique();
            $table->string('name', 50);
            $table->string('description', 255)->nullable();
        });

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

        // --- Nhóm 2 dependencies ---

        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('email', 255)->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('password', 255);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->unsignedTinyInteger('role_id');
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->unsignedInteger('loyalty_points')->default(0);
            $table->decimal('total_spent', 15, 0)->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('categories', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
        });

        // --- Nhóm 3 tables ---

        Schema::create('books', function ($table) {
            $table->increments('id');
            $table->string('sku', 50)->unique()->nullable();
            $table->string('title', 255);
            $table->string('slug', 255)->unique()->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('publisher_id')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('cost_price', 12, 0)->default(0);
            $table->decimal('original_price', 12, 0)->default(0);
            $table->decimal('sale_price', 12, 0)->default(0);
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('sold_count')->default(0);
            $table->string('cover_image', 255)->nullable();
            $table->text('extra_images')->nullable();
            $table->unsignedSmallInteger('pages')->nullable();
            $table->unsignedSmallInteger('weight')->nullable();
            $table->enum('cover_type', ['hardcover', 'paperback'])->nullable();
            $table->string('language', 50)->nullable();
            $table->unsignedSmallInteger('published_year')->nullable();
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->enum('status', ['in_stock', 'out_of_stock', 'discontinued'])->default('in_stock');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('set null');
        });

        Schema::create('carts', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('orders', function ($table) {
            $table->increments('id');
            $table->string('order_number', 50)->unique()->nullable();
            $table->unsignedInteger('user_id');
            $table->string('recipient_name', 255)->nullable();
            $table->string('recipient_phone', 20)->nullable();
            $table->text('shipping_address')->nullable();
            $table->decimal('subtotal', 15, 0)->default(0);
            $table->decimal('shipping_fee', 12, 0)->default(0);
            $table->decimal('discount_amount', 12, 0)->default(0);
            $table->decimal('total', 15, 0)->default(0);
            $table->unsignedInteger('coupon_id')->nullable();
            $table->string('transaction_ref', 255)->nullable();
            $table->enum('payment_method', ['cod', 'vnpay', 'momo', 'bank_transfer'])->default('cod');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->enum('status', ['pending', 'confirmed', 'shipping', 'delivered', 'completed', 'cancelled', 'returned'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
        });

        Schema::create('purchase_orders', function ($table) {
            $table->increments('id');
            $table->string('po_number', 50)->unique()->nullable();
            $table->unsignedInteger('publisher_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->decimal('total_amount', 15, 0)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('shipping_addresses', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('recipient_name', 255)->nullable();
            $table->string('recipient_phone', 20)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('ward', 100)->nullable();
            $table->text('address_detail')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('login_histories', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('ip_address', 45)->nullable();
            $table->string('device', 255)->nullable();
            $table->string('status', 20)->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('search_histories', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('keyword', 255)->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('notifications', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->enum('type', ['order', 'promotion', 'system'])->default('system');
            $table->string('title', 255)->nullable();
            $table->text('content')->nullable();
            $table->string('url', 500)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    private function dropSchema(): void
    {
        Schema::disableForeignKeyConstraints();
        // Nhóm 3 (reverse order)
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('search_histories');
        Schema::dropIfExists('login_histories');
        Schema::dropIfExists('shipping_addresses');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('books');
        // Nhóm 2
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
        // Nhóm 1
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('roles');
        Schema::enableForeignKeyConstraints();
    }

    // =========================================================================
    // Seed helpers
    // =========================================================================

    private function seedDependencies(): array
    {
        DB::table('roles')->insert(['code' => 'CUSTOMER', 'name' => 'Customer', 'description' => null]);
        $roleId = DB::table('roles')->where('code', 'CUSTOMER')->value('id');

        DB::table('publishers')->insert([
            'name' => 'NXB Kim Đồng', 'address' => null, 'phone' => null,
            'is_partner' => false, 'partner_icon' => null, 'partner_gradient' => null,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $publisherId = DB::table('publishers')->value('id');

        DB::table('coupons')->insert([
            'code' => 'SALE10', 'name' => 'Giảm 10%', 'type' => 'percentage',
            'value' => 10, 'max_discount' => 50000, 'min_order_amount' => 100000,
            'usage_limit' => 100, 'used_count' => 0,
            'starts_at' => now(), 'expires_at' => now()->addDays(30), 'status' => 'active',
        ]);
        $couponId = DB::table('coupons')->value('id');

        DB::table('categories')->insert([
            'id' => 1, 'parent_id' => null, 'name' => 'Văn học',
            'description' => null, 'image' => null, 'sort_order' => 1,
            'is_visible' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $categoryId = 1;

        DB::table('users')->insert([
            'name' => 'Test User', 'email' => 'test@example.com', 'phone' => null,
            'password' => bcrypt('secret'), 'date_of_birth' => null,
            'gender' => 'other', 'role_id' => $roleId, 'status' => 'active',
            'loyalty_points' => 0, 'total_spent' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $userId = DB::table('users')->value('id');

        return compact('roleId', 'publisherId', 'couponId', 'categoryId', 'userId');
    }

    private function seedBooks(int $categoryId, int $publisherId): int
    {
        DB::table('books')->insert([
            [
                'sku' => 'BOOK001', 'title' => 'Dế Mèn Phiêu Lưu Ký', 'slug' => 'de-men-phieu-luu-ky',
                'category_id' => $categoryId, 'publisher_id' => $publisherId,
                'cost_price' => 30000, 'original_price' => 50000, 'sale_price' => 45000,
                'stock' => 100, 'sold_count' => 20,
                'cover_type' => 'paperback', 'status' => 'in_stock', 'is_featured' => true,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'sku' => 'BOOK002', 'title' => 'Số Đỏ', 'slug' => 'so-do',
                'category_id' => $categoryId, 'publisher_id' => $publisherId,
                'cost_price' => 40000, 'original_price' => 70000, 'sale_price' => 65000,
                'stock' => 0, 'sold_count' => 500,
                'cover_type' => 'hardcover', 'status' => 'out_of_stock', 'is_featured' => false,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'sku' => 'BOOK003', 'title' => 'Chí Phèo', 'slug' => 'chi-pheo',
                'category_id' => $categoryId, 'publisher_id' => $publisherId,
                'cost_price' => 20000, 'original_price' => 35000, 'sale_price' => 30000,
                'stock' => 0, 'sold_count' => 1000,
                'cover_type' => 'paperback', 'status' => 'discontinued', 'is_featured' => false,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        return 3;
    }

    private function seedOrders(int $userId, int $couponId): int
    {
        DB::table('orders')->insert([
            [
                'order_number' => 'ORD001', 'user_id' => $userId,
                'recipient_name' => 'Nguyễn Văn A', 'recipient_phone' => '0901000001',
                'shipping_address' => '123 Đường ABC', 'subtotal' => 90000,
                'shipping_fee' => 30000, 'discount_amount' => 0, 'total' => 120000,
                'coupon_id' => null, 'transaction_ref' => null,
                'payment_method' => 'cod', 'payment_status' => 'unpaid', 'status' => 'pending',
                'notes' => null, 'cancel_reason' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'order_number' => 'ORD002', 'user_id' => $userId,
                'recipient_name' => 'Trần Thị B', 'recipient_phone' => '0901000002',
                'shipping_address' => '456 Đường XYZ', 'subtotal' => 200000,
                'shipping_fee' => 0, 'discount_amount' => 10000, 'total' => 190000,
                'coupon_id' => $couponId, 'transaction_ref' => 'TXN123',
                'payment_method' => 'vnpay', 'payment_status' => 'paid', 'status' => 'confirmed',
                'notes' => null, 'cancel_reason' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'order_number' => 'ORD003', 'user_id' => $userId,
                'recipient_name' => 'Lê Văn C', 'recipient_phone' => '0901000003',
                'shipping_address' => '789 Đường DEF', 'subtotal' => 150000,
                'shipping_fee' => 30000, 'discount_amount' => 0, 'total' => 180000,
                'coupon_id' => null, 'transaction_ref' => null,
                'payment_method' => 'momo', 'payment_status' => 'paid', 'status' => 'shipping',
                'notes' => null, 'cancel_reason' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'order_number' => 'ORD004', 'user_id' => $userId,
                'recipient_name' => 'Phạm Thị D', 'recipient_phone' => '0901000004',
                'shipping_address' => '321 Đường GHI', 'subtotal' => 300000,
                'shipping_fee' => 0, 'discount_amount' => 0, 'total' => 300000,
                'coupon_id' => null, 'transaction_ref' => 'TXN456',
                'payment_method' => 'bank_transfer', 'payment_status' => 'refunded', 'status' => 'returned',
                'notes' => null, 'cancel_reason' => 'Sách bị lỗi',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        return 4;
    }

    private function seedNotifications(int $userId): int
    {
        DB::table('notifications')->insert([
            [
                'user_id' => $userId, 'type' => 'order',
                'title' => 'Đơn hàng đã được xác nhận', 'content' => 'ORD001 đã xác nhận.',
                'url' => '/orders/1', 'is_read' => false, 'read_at' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $userId, 'type' => 'promotion',
                'title' => 'Khuyến mãi mùa hè', 'content' => 'Giảm 20% tất cả sách.',
                'url' => '/promotions', 'is_read' => true, 'read_at' => now(),
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'user_id' => $userId, 'type' => 'system',
                'title' => 'Bảo trì hệ thống', 'content' => 'Hệ thống bảo trì lúc 2h sáng.',
                'url' => null, 'is_read' => false, 'read_at' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        return 3;
    }

    // =========================================================================
    // Tests — Property 1: Bảo toàn số lượng row sau migration
    // =========================================================================

    /**
     * Property 1: Bảng `books`, `orders`, `notifications` tồn tại với tên tiếng Anh mới.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_group3_tables_exist_with_english_names(): void
    {
        $tables = ['books', 'carts', 'orders', 'purchase_orders', 'shipping_addresses', 'login_histories', 'search_histories', 'notifications'];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Bảng '{$table}' phải tồn tại sau migration Nhóm 3.");
        }
    }

    /**
     * Property 1: Row count của `books` được bảo toàn sau khi seed.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_books_row_count_preserved(): void
    {
        $deps = $this->seedDependencies();
        $expected = $this->seedBooks($deps['categoryId'], $deps['publisherId']);

        $actual = DB::table('books')->count();

        $this->assertEquals(
            $expected,
            $actual,
            "Bảng 'books': row count phải là {$expected} sau khi seed, nhưng thực tế là {$actual}."
        );
    }

    /**
     * Property 1: Row count của `orders` được bảo toàn sau khi seed.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_orders_row_count_preserved(): void
    {
        $deps = $this->seedDependencies();
        $expected = $this->seedOrders($deps['userId'], $deps['couponId']);

        $actual = DB::table('orders')->count();

        $this->assertEquals(
            $expected,
            $actual,
            "Bảng 'orders': row count phải là {$expected} sau khi seed, nhưng thực tế là {$actual}."
        );
    }

    /**
     * Property 1: Row count của `notifications` được bảo toàn sau khi seed.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_notifications_row_count_preserved(): void
    {
        $deps = $this->seedDependencies();
        $expected = $this->seedNotifications($deps['userId']);

        $actual = DB::table('notifications')->count();

        $this->assertEquals(
            $expected,
            $actual,
            "Bảng 'notifications': row count phải là {$expected} sau khi seed, nhưng thực tế là {$actual}."
        );
    }

    /**
     * Property 1: Row count ổn định qua nhiều lần đọc liên tiếp.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_row_count_stable_across_multiple_reads(): void
    {
        $deps = $this->seedDependencies();
        $this->seedBooks($deps['categoryId'], $deps['publisherId']);
        $this->seedOrders($deps['userId'], $deps['couponId']);
        $this->seedNotifications($deps['userId']);

        foreach (['books', 'orders', 'notifications'] as $table) {
            $first  = DB::table($table)->count();
            $second = DB::table($table)->count();
            $third  = DB::table($table)->count();

            $this->assertEquals($first, $second, "Bảng '{$table}': row count phải ổn định (lần 1 vs lần 2).");
            $this->assertEquals($first, $third,  "Bảng '{$table}': row count phải ổn định (lần 1 vs lần 3).");
        }
    }

    /**
     * Property 1: Insert N rows tăng count đúng N.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_insert_n_rows_increases_count_by_exactly_n(): void
    {
        $deps = $this->seedDependencies();

        $countBefore = DB::table('books')->count();

        DB::table('books')->insert([
            ['sku' => 'EXTRA001', 'title' => 'Book X', 'slug' => 'book-x', 'category_id' => $deps['categoryId'], 'publisher_id' => $deps['publisherId'], 'cost_price' => 10000, 'original_price' => 20000, 'sale_price' => 18000, 'stock' => 10, 'sold_count' => 0, 'cover_type' => 'paperback', 'status' => 'in_stock', 'is_featured' => false, 'created_at' => now(), 'updated_at' => now()],
            ['sku' => 'EXTRA002', 'title' => 'Book Y', 'slug' => 'book-y', 'category_id' => $deps['categoryId'], 'publisher_id' => $deps['publisherId'], 'cost_price' => 15000, 'original_price' => 25000, 'sale_price' => 22000, 'stock' => 5, 'sold_count' => 0, 'cover_type' => 'hardcover', 'status' => 'in_stock', 'is_featured' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $countAfter = DB::table('books')->count();

        $this->assertEquals(
            $countBefore + 2,
            $countAfter,
            "Bảng 'books': sau khi insert 2 rows, count phải tăng đúng 2."
        );
    }

    /**
     * Property 1: Bảng rỗng trước khi seed.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_tables_empty_before_seed(): void
    {
        foreach (['books', 'orders', 'notifications'] as $table) {
            $this->assertEquals(0, DB::table($table)->count(), "Bảng '{$table}' phải rỗng trước khi seed.");
        }
    }

    // =========================================================================
    // Tests — Property 2: Enum values được convert đúng (books)
    // =========================================================================

    /**
     * Property 2: Cột `status` của `books` chỉ chứa giá trị tiếng Anh.
     *
     * Validates: Requirements 2.1
     */
    public function test_property2_books_status_enum_values_are_english(): void
    {
        $deps = $this->seedDependencies();
        $this->seedBooks($deps['categoryId'], $deps['publisherId']);

        $validStatuses = ['in_stock', 'out_of_stock', 'discontinued'];
        $rows = DB::table('books')->select('id', 'status')->get();

        foreach ($rows as $row) {
            $this->assertContains(
                $row->status,
                $validStatuses,
                "Book id={$row->id}: status='{$row->status}' phải là một trong [in_stock, out_of_stock, discontinued]."
            );
        }
    }

    /**
     * Property 2: Cột `cover_type` của `books` chỉ chứa giá trị tiếng Anh.
     *
     * Validates: Requirements 2.1
     */
    public function test_property2_books_cover_type_enum_values_are_english(): void
    {
        $deps = $this->seedDependencies();
        $this->seedBooks($deps['categoryId'], $deps['publisherId']);

        $validCoverTypes = ['hardcover', 'paperback'];
        $rows = DB::table('books')->whereNotNull('cover_type')->select('id', 'cover_type')->get();

        foreach ($rows as $row) {
            $this->assertContains(
                $row->cover_type,
                $validCoverTypes,
                "Book id={$row->id}: cover_type='{$row->cover_type}' phải là một trong [hardcover, paperback]."
            );
        }
    }

    /**
     * Property 2: Không tồn tại giá trị tiếng Việt trong cột `status` của `books`.
     *
     * Validates: Requirements 2.1
     */
    public function test_property2_no_vietnamese_book_status_values(): void
    {
        $deps = $this->seedDependencies();
        $this->seedBooks($deps['categoryId'], $deps['publisherId']);

        $vietnameseStatuses = ['Còn hàng', 'Hết hàng', 'Ngừng kinh doanh'];

        foreach ($vietnameseStatuses as $viet) {
            $count = DB::table('books')->where('status', $viet)->count();
            $this->assertEquals(0, $count, "Không được tồn tại giá trị status='{$viet}' (tiếng Việt) trong bảng 'books'.");
        }
    }

    /**
     * Property 2: Không tồn tại giá trị tiếng Việt trong cột `cover_type` của `books`.
     *
     * Validates: Requirements 2.1
     */
    public function test_property2_no_vietnamese_cover_type_values(): void
    {
        $deps = $this->seedDependencies();
        $this->seedBooks($deps['categoryId'], $deps['publisherId']);

        $vietnameseCoverTypes = ['Bìa cứng', 'Bìa mềm'];

        foreach ($vietnameseCoverTypes as $viet) {
            $count = DB::table('books')->where('cover_type', $viet)->count();
            $this->assertEquals(0, $count, "Không được tồn tại giá trị cover_type='{$viet}' (tiếng Việt) trong bảng 'books'.");
        }
    }

    /**
     * Property 2: Mỗi giá trị status hợp lệ của `books` đều có thể insert thành công.
     *
     * Validates: Requirements 2.1
     */
    public function test_property2_all_valid_book_status_values_can_be_inserted(): void
    {
        $deps = $this->seedDependencies();
        $statuses = ['in_stock', 'out_of_stock', 'discontinued'];

        foreach ($statuses as $i => $status) {
            DB::table('books')->insert([
                'sku' => "STATUS{$i}", 'title' => "Book {$status}", 'slug' => "book-{$status}",
                'category_id' => $deps['categoryId'], 'publisher_id' => $deps['publisherId'],
                'cost_price' => 10000, 'original_price' => 20000, 'sale_price' => 18000,
                'stock' => 10, 'sold_count' => 0,
                'cover_type' => 'paperback', 'status' => $status, 'is_featured' => false,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        foreach ($statuses as $status) {
            $count = DB::table('books')->where('status', $status)->count();
            $this->assertEquals(1, $count, "Phải có đúng 1 book với status='{$status}'.");
        }
    }

    /**
     * Property 2: Mỗi giá trị cover_type hợp lệ của `books` đều có thể insert thành công.
     *
     * Validates: Requirements 2.1
     */
    public function test_property2_all_valid_cover_type_values_can_be_inserted(): void
    {
        $deps = $this->seedDependencies();
        $coverTypes = ['hardcover', 'paperback'];

        foreach ($coverTypes as $i => $coverType) {
            DB::table('books')->insert([
                'sku' => "COVER{$i}", 'title' => "Book {$coverType}", 'slug' => "book-{$coverType}",
                'category_id' => $deps['categoryId'], 'publisher_id' => $deps['publisherId'],
                'cost_price' => 10000, 'original_price' => 20000, 'sale_price' => 18000,
                'stock' => 10, 'sold_count' => 0,
                'cover_type' => $coverType, 'status' => 'in_stock', 'is_featured' => false,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        foreach ($coverTypes as $coverType) {
            $count = DB::table('books')->where('cover_type', $coverType)->count();
            $this->assertEquals(1, $count, "Phải có đúng 1 book với cover_type='{$coverType}'.");
        }
    }

    // =========================================================================
    // Tests — Property 2: Enum values được convert đúng (orders)
    // =========================================================================

    /**
     * Property 2: Cột `status` của `orders` chỉ chứa giá trị tiếng Anh.
     *
     * Validates: Requirements 2.2
     */
    public function test_property2_orders_status_enum_values_are_english(): void
    {
        $deps = $this->seedDependencies();
        $this->seedOrders($deps['userId'], $deps['couponId']);

        $validStatuses = ['pending', 'confirmed', 'shipping', 'delivered', 'completed', 'cancelled', 'returned'];
        $rows = DB::table('orders')->select('id', 'status')->get();

        foreach ($rows as $row) {
            $this->assertContains(
                $row->status,
                $validStatuses,
                "Order id={$row->id}: status='{$row->status}' phải là một trong các giá trị hợp lệ."
            );
        }
    }

    /**
     * Property 2: Cột `payment_status` của `orders` chỉ chứa giá trị tiếng Anh.
     *
     * Validates: Requirements 2.3
     */
    public function test_property2_orders_payment_status_enum_values_are_english(): void
    {
        $deps = $this->seedDependencies();
        $this->seedOrders($deps['userId'], $deps['couponId']);

        $validPaymentStatuses = ['unpaid', 'paid', 'refunded'];
        $rows = DB::table('orders')->select('id', 'payment_status')->get();

        foreach ($rows as $row) {
            $this->assertContains(
                $row->payment_status,
                $validPaymentStatuses,
                "Order id={$row->id}: payment_status='{$row->payment_status}' phải là một trong [unpaid, paid, refunded]."
            );
        }
    }

    /**
     * Property 2: Cột `payment_method` của `orders` chỉ chứa giá trị tiếng Anh.
     *
     * Validates: Requirements 2.4
     */
    public function test_property2_orders_payment_method_enum_values_are_english(): void
    {
        $deps = $this->seedDependencies();
        $this->seedOrders($deps['userId'], $deps['couponId']);

        $validPaymentMethods = ['cod', 'vnpay', 'momo', 'bank_transfer'];
        $rows = DB::table('orders')->select('id', 'payment_method')->get();

        foreach ($rows as $row) {
            $this->assertContains(
                $row->payment_method,
                $validPaymentMethods,
                "Order id={$row->id}: payment_method='{$row->payment_method}' phải là một trong [cod, vnpay, momo, bank_transfer]."
            );
        }
    }

    /**
     * Property 2: Không tồn tại giá trị tiếng Việt trong các cột enum của `orders`.
     *
     * Validates: Requirements 2.2, 2.3, 2.4
     */
    public function test_property2_no_vietnamese_order_enum_values(): void
    {
        $deps = $this->seedDependencies();
        $this->seedOrders($deps['userId'], $deps['couponId']);

        $vietnameseOrderStatuses = ['Chờ xác nhận', 'Đã xác nhận', 'Đang giao', 'Đã giao', 'Hoàn thành', 'Đã hủy', 'Hoàn trả'];
        foreach ($vietnameseOrderStatuses as $viet) {
            $this->assertEquals(0, DB::table('orders')->where('status', $viet)->count(), "Không được tồn tại status='{$viet}' (tiếng Việt).");
        }

        $vietnamesePaymentStatuses = ['Chưa thanh toán', 'Đã thanh toán', 'Đã hoàn tiền'];
        foreach ($vietnamesePaymentStatuses as $viet) {
            $this->assertEquals(0, DB::table('orders')->where('payment_status', $viet)->count(), "Không được tồn tại payment_status='{$viet}' (tiếng Việt).");
        }

        $vietnamesePaymentMethods = ['Tiền mặt', 'Chuyển khoản', 'Ví điện tử'];
        foreach ($vietnamesePaymentMethods as $viet) {
            $this->assertEquals(0, DB::table('orders')->where('payment_method', $viet)->count(), "Không được tồn tại payment_method='{$viet}' (tiếng Việt).");
        }
    }

    /**
     * Property 2: Tất cả 7 giá trị status hợp lệ của `orders` đều có thể insert thành công.
     *
     * Validates: Requirements 2.2
     */
    public function test_property2_all_valid_order_status_values_can_be_inserted(): void
    {
        $deps = $this->seedDependencies();
        $statuses = ['pending', 'confirmed', 'shipping', 'delivered', 'completed', 'cancelled', 'returned'];

        foreach ($statuses as $i => $status) {
            DB::table('orders')->insert([
                'order_number' => "TEST{$i}", 'user_id' => $deps['userId'],
                'subtotal' => 100000, 'shipping_fee' => 0, 'discount_amount' => 0, 'total' => 100000,
                'payment_method' => 'cod', 'payment_status' => 'unpaid', 'status' => $status,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        foreach ($statuses as $status) {
            $count = DB::table('orders')->where('status', $status)->count();
            $this->assertEquals(1, $count, "Phải có đúng 1 order với status='{$status}'.");
        }
    }

    /**
     * Property 2: Tất cả 3 giá trị payment_status hợp lệ của `orders` đều có thể insert thành công.
     *
     * Validates: Requirements 2.3
     */
    public function test_property2_all_valid_payment_status_values_can_be_inserted(): void
    {
        $deps = $this->seedDependencies();
        $paymentStatuses = ['unpaid', 'paid', 'refunded'];

        foreach ($paymentStatuses as $i => $paymentStatus) {
            DB::table('orders')->insert([
                'order_number' => "PS{$i}", 'user_id' => $deps['userId'],
                'subtotal' => 100000, 'shipping_fee' => 0, 'discount_amount' => 0, 'total' => 100000,
                'payment_method' => 'cod', 'payment_status' => $paymentStatus, 'status' => 'pending',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        foreach ($paymentStatuses as $paymentStatus) {
            $count = DB::table('orders')->where('payment_status', $paymentStatus)->count();
            $this->assertEquals(1, $count, "Phải có đúng 1 order với payment_status='{$paymentStatus}'.");
        }
    }

    /**
     * Property 2: Tất cả 4 giá trị payment_method hợp lệ của `orders` đều có thể insert thành công.
     *
     * Validates: Requirements 2.4
     */
    public function test_property2_all_valid_payment_method_values_can_be_inserted(): void
    {
        $deps = $this->seedDependencies();
        $paymentMethods = ['cod', 'vnpay', 'momo', 'bank_transfer'];

        foreach ($paymentMethods as $i => $method) {
            DB::table('orders')->insert([
                'order_number' => "PM{$i}", 'user_id' => $deps['userId'],
                'subtotal' => 100000, 'shipping_fee' => 0, 'discount_amount' => 0, 'total' => 100000,
                'payment_method' => $method, 'payment_status' => 'unpaid', 'status' => 'pending',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        foreach ($paymentMethods as $method) {
            $count = DB::table('orders')->where('payment_method', $method)->count();
            $this->assertEquals(1, $count, "Phải có đúng 1 order với payment_method='{$method}'.");
        }
    }

    // =========================================================================
    // Tests — Property 2: Enum values được convert đúng (notifications)
    // =========================================================================

    /**
     * Property 2: Cột `type` của `notifications` chỉ chứa giá trị tiếng Anh.
     *
     * Validates: Requirements 2.5
     */
    public function test_property2_notifications_type_enum_values_are_english(): void
    {
        $deps = $this->seedDependencies();
        $this->seedNotifications($deps['userId']);

        $validTypes = ['order', 'promotion', 'system'];
        $rows = DB::table('notifications')->select('id', 'type')->get();

        foreach ($rows as $row) {
            $this->assertContains(
                $row->type,
                $validTypes,
                "Notification id={$row->id}: type='{$row->type}' phải là một trong [order, promotion, system]."
            );
        }
    }

    /**
     * Property 2: Không tồn tại giá trị tiếng Việt trong cột `type` của `notifications`.
     *
     * Validates: Requirements 2.5
     */
    public function test_property2_no_vietnamese_notification_type_values(): void
    {
        $deps = $this->seedDependencies();
        $this->seedNotifications($deps['userId']);

        $vietnameseTypes = ['Đơn hàng', 'Khuyến mãi', 'Hệ thống'];

        foreach ($vietnameseTypes as $viet) {
            $count = DB::table('notifications')->where('type', $viet)->count();
            $this->assertEquals(0, $count, "Không được tồn tại type='{$viet}' (tiếng Việt) trong bảng 'notifications'.");
        }
    }

    /**
     * Property 2: Tất cả 3 giá trị type hợp lệ của `notifications` đều có thể insert thành công.
     *
     * Validates: Requirements 2.5
     */
    public function test_property2_all_valid_notification_type_values_can_be_inserted(): void
    {
        $deps = $this->seedDependencies();
        $types = ['order', 'promotion', 'system'];

        foreach ($types as $type) {
            DB::table('notifications')->insert([
                'user_id' => $deps['userId'],
                'type' => $type,
                'title' => "Test {$type}",
                'content' => "Content for {$type}",
                'url' => null,
                'is_read' => false,
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($types as $type) {
            $count = DB::table('notifications')->where('type', $type)->count();
            $this->assertEquals(1, $count, "Phải có đúng 1 notification với type='{$type}'.");
        }
    }

    /**
     * Property 2: Seed data chứa đủ tất cả enum values cho books, orders, notifications.
     * Kiểm tra tổng hợp: mỗi enum value xuất hiện ít nhất 1 lần sau khi seed.
     *
     * Validates: Requirements 2.1, 2.2, 2.3, 2.4, 2.5
     */
    public function test_property2_seeded_data_covers_all_enum_values(): void
    {
        $deps = $this->seedDependencies();
        $this->seedBooks($deps['categoryId'], $deps['publisherId']);
        $this->seedOrders($deps['userId'], $deps['couponId']);
        $this->seedNotifications($deps['userId']);

        // books.status: in_stock, out_of_stock, discontinued
        foreach (['in_stock', 'out_of_stock', 'discontinued'] as $status) {
            $this->assertGreaterThanOrEqual(1, DB::table('books')->where('status', $status)->count(), "books.status='{$status}' phải có ít nhất 1 row.");
        }

        // books.cover_type: hardcover, paperback
        foreach (['hardcover', 'paperback'] as $coverType) {
            $this->assertGreaterThanOrEqual(1, DB::table('books')->where('cover_type', $coverType)->count(), "books.cover_type='{$coverType}' phải có ít nhất 1 row.");
        }

        // orders.payment_method: cod, vnpay, momo, bank_transfer
        foreach (['cod', 'vnpay', 'momo', 'bank_transfer'] as $method) {
            $this->assertGreaterThanOrEqual(1, DB::table('orders')->where('payment_method', $method)->count(), "orders.payment_method='{$method}' phải có ít nhất 1 row.");
        }

        // notifications.type: order, promotion, system
        foreach (['order', 'promotion', 'system'] as $type) {
            $this->assertGreaterThanOrEqual(1, DB::table('notifications')->where('type', $type)->count(), "notifications.type='{$type}' phải có ít nhất 1 row.");
        }
    }
}
