<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tests orphan data prevention.
 *
 * SQLite FK cascade requires PRAGMA foreign_keys = ON.
 * flash_sale_items has onDelete('cascade') per migration.
 * order_items has onDelete('cascade') per migration.
 *
 * Without PRAGMA foreign_keys = ON, SQLite does NOT enforce FK constraints.
 * These tests enable FK enforcement and verify cascade behavior.
 */
class OrphanDataTest extends BaseAdminTestCase
{
    // -------------------------------------------------------------------------
    // Schema
    // -------------------------------------------------------------------------

    protected function createSchema(): void
    {
        Schema::create('roles', function ($t) {
            $t->tinyIncrements('id');
            $t->string('code', 20)->unique();
            $t->string('name', 50);
            $t->string('description', 255)->nullable();
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

        Schema::create('categories', function ($t) {
            $t->increments('id');
            $t->unsignedInteger('parent_id')->nullable();
            $t->string('name', 100);
            $t->string('slug', 150)->unique();
            $t->text('description')->nullable();
            $t->string('image', 255)->nullable();
            $t->smallInteger('sort_order')->default(0);
            $t->boolean('is_visible')->default(true);
            $t->softDeletes();
        });

        Schema::create('publishers', function ($t) {
            $t->increments('id');
            $t->string('name', 200);
            $t->string('slug', 200)->unique();
            $t->string('logo', 255)->nullable();
            $t->text('description')->nullable();
            $t->string('website', 255)->nullable();
            $t->boolean('is_partner')->default(false);
            $t->timestamps();
        });

        Schema::create('books', function ($t) {
            $t->increments('id');
            $t->string('sku', 30)->unique();
            $t->string('title', 255);
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
            $t->string('language', 50)->default('English');
            $t->year('published_year')->nullable();
            $t->decimal('rating_avg', 3, 2)->default(0.00);
            $t->unsignedInteger('rating_count')->default(0);
            $t->string('status', 30)->default('in_stock');
            $t->boolean('is_featured')->default(false);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('book_author', function ($t) {
            $t->unsignedInteger('book_id');
            $t->unsignedInteger('author_id');
            $t->primary(['book_id', 'author_id']);
        });

        Schema::create('flash_sales', function ($t) {
            $t->increments('id');
            $t->string('name', 255)->nullable();
            $t->dateTime('start_date');
            $t->dateTime('end_date');
            $t->timestamps();
        });

        // flash_sale_items with cascade delete (mirrors production migration)
        Schema::create('flash_sale_items', function ($t) {
            $t->increments('id');
            $t->unsignedInteger('flash_sale_id');
            $t->unsignedInteger('book_id');
            $t->decimal('flash_price', 12, 0)->default(0);
            $t->unsignedTinyInteger('display_order')->default(1);
            $t->timestamps();

            $t->foreign('flash_sale_id')
                ->references('id')
                ->on('flash_sales')
                ->onDelete('cascade');
        });

        Schema::create('orders', function ($t) {
            $t->increments('id');
            $t->string('order_number', 20)->unique();
            $t->unsignedBigInteger('user_id');
            $t->string('recipient_name', 100);
            $t->string('recipient_phone', 15);
            $t->text('shipping_address');
            $t->decimal('subtotal', 12, 0)->default(0);
            $t->decimal('shipping_fee', 12, 0)->default(0);
            $t->decimal('discount_amount', 12, 0)->default(0);
            $t->decimal('total', 12, 0)->default(0);
            $t->unsignedInteger('coupon_id')->nullable();
            $t->string('transaction_ref', 200)->nullable();
            $t->string('payment_method', 20)->default('cod');
            $t->string('payment_status', 20)->default('unpaid');
            $t->string('status', 30)->default('pending');
            $t->text('notes')->nullable();
            $t->text('cancel_reason')->nullable();
            $t->timestamps();
        });

        // order_items with cascade delete (mirrors production migration)
        Schema::create('order_items', function ($t) {
            $t->increments('id');
            $t->unsignedInteger('order_id');
            $t->unsignedInteger('book_id');
            $t->string('book_title_snapshot', 255);
            $t->string('book_image_snapshot', 255)->nullable();
            $t->unsignedSmallInteger('quantity');
            $t->decimal('unit_price', 12, 0);
            $t->decimal('subtotal', 12, 0);

            $t->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
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
    }

    protected function dropSchema(): void
    {
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('flash_sale_items');
        Schema::dropIfExists('flash_sales');
        Schema::dropIfExists('book_author');
        Schema::dropIfExists('books');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement('PRAGMA foreign_keys = ON');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function insertBook(): int
    {
        static $n = 0;
        $n++;
        return DB::table('books')->insertGetId([
            'sku'        => 'SKU-ORP-' . $n,
            'title'      => 'Book ' . $n,
            'slug'       => 'book-orp-' . $n,
            'stock'      => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function insertFlashSale(): int
    {
        return DB::table('flash_sales')->insertGetId([
            'name'       => 'Flash Sale',
            'start_date' => now()->subHour(),
            'end_date'   => now()->addHour(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function insertFlashSaleItem(int $flashSaleId, int $bookId, int $order = 1): int
    {
        return DB::table('flash_sale_items')->insertGetId([
            'flash_sale_id' => $flashSaleId,
            'book_id'       => $bookId,
            'flash_price'   => 99000,
            'display_order' => $order,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    private function insertOrder(): int
    {
        return DB::table('orders')->insertGetId([
            'order_number'     => 'ORD-' . uniqid(),
            'user_id'          => 1,
            'recipient_name'   => 'Test User',
            'recipient_phone'  => '0900000000',
            'shipping_address' => '123 Test St',
            'total'            => 100000,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    private function insertOrderItem(int $orderId, int $bookId): int
    {
        return DB::table('order_items')->insertGetId([
            'order_id'            => $orderId,
            'book_id'             => $bookId,
            'book_title_snapshot' => 'Test Book',
            'quantity'            => 1,
            'unit_price'          => 50000,
            'subtotal'            => 50000,
        ]);
    }

    // -------------------------------------------------------------------------
    // Tests
    // -------------------------------------------------------------------------

    public function test_delete_flash_sale_cascades_to_items(): void
    {
        // flash_sale_items has onDelete('cascade') in migration
        $flashSaleId = $this->insertFlashSale();
        $bookId      = $this->insertBook();
        $itemId      = $this->insertFlashSaleItem($flashSaleId, $bookId);

        $this->assertDatabaseHas('flash_sale_items', ['id' => $itemId]);

        // Delete the parent flash_sale
        DB::table('flash_sales')->where('id', $flashSaleId)->delete();

        // Items should be cascade-deleted
        $this->assertDatabaseMissing('flash_sale_items', ['id' => $itemId]);
    }

    public function test_delete_order_with_items_behavior(): void
    {
        // order_items has onDelete('cascade') in migration
        $orderId = $this->insertOrder();
        $bookId  = $this->insertBook();
        $itemId  = $this->insertOrderItem($orderId, $bookId);

        $this->assertDatabaseHas('order_items', ['id' => $itemId]);

        // Delete the parent order
        DB::table('orders')->where('id', $orderId)->delete();

        // Items should be cascade-deleted
        $this->assertDatabaseMissing('order_items', ['id' => $itemId]);
    }

    public function test_flash_sale_items_belong_to_valid_flash_sale(): void
    {
        $flashSaleId = $this->insertFlashSale();
        $bookId      = $this->insertBook();
        $itemId      = $this->insertFlashSaleItem($flashSaleId, $bookId);

        $item = DB::table('flash_sale_items')->find($itemId);
        $this->assertNotNull($item);

        $flashSale = DB::table('flash_sales')->find($item->flash_sale_id);
        $this->assertNotNull($flashSale, 'flash_sale_item must reference an existing flash_sale');
    }

    public function test_order_items_belong_to_valid_order(): void
    {
        $orderId = $this->insertOrder();
        $bookId  = $this->insertBook();
        $itemId  = $this->insertOrderItem($orderId, $bookId);

        $item = DB::table('order_items')->find($itemId);
        $this->assertNotNull($item);

        $order = DB::table('orders')->find($item->order_id);
        $this->assertNotNull($order, 'order_item must reference an existing order');
    }

    public function test_no_orphan_flash_sale_items_after_parent_delete(): void
    {
        // flash_sale_items has cascade delete — no orphans should remain
        $flashSaleId = $this->insertFlashSale();
        $bookId1     = $this->insertBook();
        $bookId2     = $this->insertBook();

        $this->insertFlashSaleItem($flashSaleId, $bookId1, 1);
        $this->insertFlashSaleItem($flashSaleId, $bookId2, 2);

        $this->assertEquals(2, DB::table('flash_sale_items')->where('flash_sale_id', $flashSaleId)->count());

        // Delete parent
        DB::table('flash_sales')->where('id', $flashSaleId)->delete();

        // No orphan items remain
        $orphans = DB::table('flash_sale_items')->where('flash_sale_id', $flashSaleId)->count();
        $this->assertEquals(0, $orphans, 'No orphan flash_sale_items should remain after parent delete');
    }
}
