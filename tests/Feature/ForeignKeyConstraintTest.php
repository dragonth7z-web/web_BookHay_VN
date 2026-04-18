<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Property Test: FK constraints hoạt động sau rename (Property 3)
 *
 * Property 3: FK constraints hoạt động sau rename
 * Thử insert row vi phạm FK vào `cart_items`, `order_items`, `reviews` — phải bị từ chối
 *
 * Validates: Requirements 1.4, 1.5
 */
class ForeignKeyConstraintTest extends TestCase
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
        // --- Nhóm 1 ---
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

        // --- Nhóm 2 ---
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

        // --- Nhóm 3 ---
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

        // --- Nhóm 4 ---
        Schema::create('cart_items', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('book_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price_snapshot', 12, 0)->default(0);
            $table->timestamp('added_at')->nullable();
            $table->timestamps();
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });

        Schema::create('order_items', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('book_id')->nullable();
            $table->string('book_title_snapshot', 255)->nullable();
            $table->string('book_image_snapshot', 255)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 0)->default(0);
            $table->decimal('subtotal', 15, 0)->default(0);
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books');
        });

        Schema::create('reviews', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('book_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('content')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }

    private function dropSchema(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('books');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('roles');
        Schema::enableForeignKeyConstraints();
    }

    // =========================================================================
    // Seed helpers
    // =========================================================================

    private function seedBaseData(): array
    {
        DB::table('roles')->insert(['code' => 'CUSTOMER', 'name' => 'Customer', 'description' => null]);
        $roleId = DB::table('roles')->where('code', 'CUSTOMER')->value('id');

        DB::table('publishers')->insert([
            'name' => 'NXB Test', 'address' => null, 'phone' => null,
            'is_partner' => false, 'partner_icon' => null, 'partner_gradient' => null,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $publisherId = DB::table('publishers')->value('id');

        DB::table('categories')->insert([
            'parent_id' => null, 'name' => 'Test Category',
            'description' => null, 'image' => null, 'sort_order' => 1,
            'is_visible' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $categoryId = DB::table('categories')->value('id');

        DB::table('users')->insert([
            'name' => 'Test User', 'email' => 'fk_test@example.com', 'phone' => null,
            'password' => bcrypt('secret'), 'date_of_birth' => null,
            'gender' => 'other', 'role_id' => $roleId, 'status' => 'active',
            'loyalty_points' => 0, 'total_spent' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $userId = DB::table('users')->value('id');

        DB::table('books')->insert([
            'sku' => 'FK-BOOK-001', 'title' => 'FK Test Book', 'slug' => 'fk-test-book',
            'category_id' => $categoryId, 'publisher_id' => $publisherId,
            'cost_price' => 10000, 'original_price' => 20000, 'sale_price' => 18000,
            'stock' => 100, 'sold_count' => 0,
            'cover_type' => 'paperback', 'status' => 'in_stock', 'is_featured' => false,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $bookId = DB::table('books')->value('id');

        DB::table('carts')->insert([
            'user_id' => $userId, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $cartId = DB::table('carts')->value('id');

        DB::table('orders')->insert([
            'order_number' => 'FK-ORD-001', 'user_id' => $userId,
            'recipient_name' => 'Test', 'recipient_phone' => '0900000000',
            'shipping_address' => '123 Test St', 'subtotal' => 18000,
            'shipping_fee' => 30000, 'discount_amount' => 0, 'total' => 48000,
            'coupon_id' => null, 'transaction_ref' => null,
            'payment_method' => 'cod', 'payment_status' => 'unpaid', 'status' => 'pending',
            'notes' => null, 'cancel_reason' => null,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $orderId = DB::table('orders')->value('id');

        return compact('roleId', 'publisherId', 'categoryId', 'userId', 'bookId', 'cartId', 'orderId');
    }

    // =========================================================================
    // Property 3: FK constraints hoạt động sau rename
    // =========================================================================

    /**
     * Property 3: Insert vào `cart_items` với cart_id không tồn tại phải bị từ chối.
     *
     * Validates: Requirements 1.4, 1.5
     */
    public function test_property3_cart_items_rejects_nonexistent_cart_id(): void
    {
        $data = $this->seedBaseData();
        $nonExistentCartId = 99999;

        $this->expectException(QueryException::class);

        DB::table('cart_items')->insert([
            'cart_id'        => $nonExistentCartId,
            'book_id'        => $data['bookId'],
            'quantity'       => 1,
            'price_snapshot' => 18000,
            'added_at'       => now(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    /**
     * Property 3: Insert vào `cart_items` với book_id không tồn tại phải bị từ chối.
     *
     * Validates: Requirements 1.4, 1.5
     */
    public function test_property3_cart_items_rejects_nonexistent_book_id(): void
    {
        $data = $this->seedBaseData();
        $nonExistentBookId = 99999;

        $this->expectException(QueryException::class);

        DB::table('cart_items')->insert([
            'cart_id'        => $data['cartId'],
            'book_id'        => $nonExistentBookId,
            'quantity'       => 1,
            'price_snapshot' => 18000,
            'added_at'       => now(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    /**
     * Property 3: Insert hợp lệ vào `cart_items` phải thành công.
     *
     * Validates: Requirements 1.4, 1.5
     */
    public function test_property3_cart_items_accepts_valid_fk_values(): void
    {
        $data = $this->seedBaseData();

        DB::table('cart_items')->insert([
            'cart_id'        => $data['cartId'],
            'book_id'        => $data['bookId'],
            'quantity'       => 2,
            'price_snapshot' => 18000,
            'added_at'       => now(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $this->assertEquals(1, DB::table('cart_items')->count());
    }

    /**
     * Property 3: Insert vào `order_items` với order_id không tồn tại phải bị từ chối.
     *
     * Validates: Requirements 1.4, 1.5
     */
    public function test_property3_order_items_rejects_nonexistent_order_id(): void
    {
        $data = $this->seedBaseData();
        $nonExistentOrderId = 99999;

        $this->expectException(QueryException::class);

        DB::table('order_items')->insert([
            'order_id'             => $nonExistentOrderId,
            'book_id'              => $data['bookId'],
            'book_title_snapshot'  => 'FK Test Book',
            'book_image_snapshot'  => null,
            'quantity'             => 1,
            'unit_price'           => 18000,
            'subtotal'             => 18000,
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);
    }

    /**
     * Property 3: Insert vào `order_items` với book_id không tồn tại phải bị từ chối.
     *
     * Validates: Requirements 1.4, 1.5
     */
    public function test_property3_order_items_rejects_nonexistent_book_id(): void
    {
        $data = $this->seedBaseData();
        $nonExistentBookId = 99999;

        $this->expectException(QueryException::class);

        DB::table('order_items')->insert([
            'order_id'             => $data['orderId'],
            'book_id'              => $nonExistentBookId,
            'book_title_snapshot'  => 'Ghost Book',
            'book_image_snapshot'  => null,
            'quantity'             => 1,
            'unit_price'           => 18000,
            'subtotal'             => 18000,
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);
    }

    /**
     * Property 3: Insert hợp lệ vào `order_items` phải thành công.
     *
     * Validates: Requirements 1.4, 1.5
     */
    public function test_property3_order_items_accepts_valid_fk_values(): void
    {
        $data = $this->seedBaseData();

        DB::table('order_items')->insert([
            'order_id'             => $data['orderId'],
            'book_id'              => $data['bookId'],
            'book_title_snapshot'  => 'FK Test Book',
            'book_image_snapshot'  => null,
            'quantity'             => 1,
            'unit_price'           => 18000,
            'subtotal'             => 18000,
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);

        $this->assertEquals(1, DB::table('order_items')->count());
    }

    /**
     * Property 3: Insert vào `reviews` với book_id không tồn tại phải bị từ chối.
     *
     * Validates: Requirements 1.4, 1.5
     */
    public function test_property3_reviews_rejects_nonexistent_book_id(): void
    {
        $data = $this->seedBaseData();
        $nonExistentBookId = 99999;

        $this->expectException(QueryException::class);

        DB::table('reviews')->insert([
            'book_id'    => $nonExistentBookId,
            'user_id'    => $data['userId'],
            'order_id'   => null,
            'rating'     => 5,
            'content'    => 'Great book!',
            'status'     => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Property 3: Insert vào `reviews` với user_id không tồn tại phải bị từ chối.
     *
     * Validates: Requirements 1.4, 1.5
     */
    public function test_property3_reviews_rejects_nonexistent_user_id(): void
    {
        $data = $this->seedBaseData();
        $nonExistentUserId = 99999;

        $this->expectException(QueryException::class);

        DB::table('reviews')->insert([
            'book_id'    => $data['bookId'],
            'user_id'    => $nonExistentUserId,
            'order_id'   => null,
            'rating'     => 5,
            'content'    => 'Great book!',
            'status'     => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Property 3: Insert vào `reviews` với order_id không tồn tại phải bị từ chối.
     *
     * Validates: Requirements 1.4, 1.5
     */
    public function test_property3_reviews_rejects_nonexistent_order_id(): void
    {
        $data = $this->seedBaseData();
        $nonExistentOrderId = 99999;

        $this->expectException(QueryException::class);

        DB::table('reviews')->insert([
            'book_id'    => $data['bookId'],
            'user_id'    => $data['userId'],
            'order_id'   => $nonExistentOrderId,
            'rating'     => 4,
            'content'    => 'Good book!',
            'status'     => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Property 3: Insert hợp lệ vào `reviews` phải thành công.
     *
     * Validates: Requirements 1.4, 1.5
     */
    public function test_property3_reviews_accepts_valid_fk_values(): void
    {
        $data = $this->seedBaseData();

        DB::table('reviews')->insert([
            'book_id'    => $data['bookId'],
            'user_id'    => $data['userId'],
            'order_id'   => $data['orderId'],
            'rating'     => 5,
            'content'    => 'Excellent!',
            'status'     => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertEquals(1, DB::table('reviews')->count());
    }

    /**
     * Property 3: `reviews` chấp nhận order_id = NULL (nullable FK).
     *
     * Validates: Requirements 1.4, 1.5
     */
    public function test_property3_reviews_accepts_null_order_id(): void
    {
        $data = $this->seedBaseData();

        DB::table('reviews')->insert([
            'book_id'    => $data['bookId'],
            'user_id'    => $data['userId'],
            'order_id'   => null,
            'rating'     => 3,
            'content'    => 'Decent.',
            'status'     => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertEquals(1, DB::table('reviews')->count());
    }
}
