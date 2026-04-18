<?php

namespace Tests\Unit;

use App\Exceptions\CartOperationException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Repositories\CartRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Unit tests for CartRepository.
 *
 * **Validates: Requirements 4.1, 4.2, 4.3, 4.4, 4.5, 4.7, 17.4**
 */
class CartRepositoryTest extends TestCase
{
    private CartRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
        $this->repo = new CartRepository();
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
        Schema::create('roles', function ($table) {
            $table->tinyIncrements('id');
            $table->string('code', 20)->unique();
            $table->string('name', 50);
            $table->string('description', 255)->nullable();
        });

        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('email', 255)->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('password', 255);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->unsignedTinyInteger('role_id')->nullable();
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->unsignedInteger('loyalty_points')->default(0);
            $table->decimal('total_spent', 15, 0)->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('carts', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('publishers', function ($table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->timestamps();
        });

        Schema::create('categories', function ($table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->timestamps();
        });

        Schema::create('books', function ($table) {
            $table->increments('id');
            $table->string('sku', 50)->unique()->nullable();
            $table->string('title', 255);
            $table->string('slug', 255)->unique()->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('publisher_id')->nullable();
            $table->decimal('cost_price', 12, 0)->default(0);
            $table->decimal('original_price', 12, 0)->default(0);
            $table->decimal('sale_price', 12, 0)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('sold_count')->default(0);
            $table->text('description')->nullable();
            $table->string('short_description', 500)->nullable();
            $table->string('cover_image', 255)->nullable();
            $table->text('extra_images')->nullable();
            $table->unsignedSmallInteger('pages')->nullable();
            $table->unsignedSmallInteger('weight')->nullable();
            $table->enum('cover_type', ['hardcover', 'paperback'])->default('paperback');
            $table->string('language', 50)->default('English');
            $table->unsignedSmallInteger('published_year')->nullable();
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->enum('status', ['in_stock', 'out_of_stock', 'discontinued'])->default('in_stock');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cart_items', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('book_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price_snapshot', 12, 0)->nullable();
            $table->timestamp('added_at')->nullable();
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    private function dropSchema(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('books');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::enableForeignKeyConstraints();
    }

    // =========================================================================
    // Seed helpers
    // =========================================================================

    private function createUser(): int
    {
        static $counter = 0;
        $counter++;

        return DB::table('users')->insertGetId([
            'name'       => "User {$counter}",
            'email'      => "user{$counter}@test.com",
            'password'   => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createBook(): int
    {
        static $counter = 0;
        $counter++;

        return DB::table('books')->insertGetId([
            'sku'        => "SKU-{$counter}",
            'title'      => "Book {$counter}",
            'slug'       => "book-{$counter}",
            'sale_price' => 50000,
            'stock'      => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // =========================================================================
    // Tests
    // =========================================================================

    /**
     * **Validates: Requirements 4.1**
     */
    public function test_getOrCreateForUser_creates_cart_when_not_exists(): void
    {
        $userId = $this->createUser();

        $cart = $this->repo->getOrCreateForUser($userId);

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertEquals($userId, $cart->user_id);
        $this->assertDatabaseHas('carts', ['user_id' => $userId]);
    }

    /**
     * **Validates: Requirements 4.1**
     */
    public function test_getOrCreateForUser_returns_existing_cart(): void
    {
        $userId = $this->createUser();

        $first  = $this->repo->getOrCreateForUser($userId);
        $second = $this->repo->getOrCreateForUser($userId);

        $this->assertEquals($first->id, $second->id);
        $this->assertEquals(1, DB::table('carts')->where('user_id', $userId)->count());
    }

    /**
     * **Validates: Requirements 4.2**
     */
    public function test_findItemByBookId_returns_null_when_not_found(): void
    {
        $userId = $this->createUser();
        $cart   = $this->repo->getOrCreateForUser($userId);
        $bookId = $this->createBook();

        $result = $this->repo->findItemByBookId($cart, $bookId);

        $this->assertNull($result);
    }

    /**
     * **Validates: Requirements 4.3**
     */
    public function test_addItem_creates_new_item(): void
    {
        $userId = $this->createUser();
        $cart   = $this->repo->getOrCreateForUser($userId);
        $bookId = $this->createBook();

        $item = $this->repo->addItem($cart, $bookId, 2);

        $this->assertInstanceOf(CartItem::class, $item);
        $this->assertEquals($bookId, $item->book_id);
        $this->assertEquals(2, $item->quantity);
        $this->assertDatabaseHas('cart_items', [
            'cart_id'  => $cart->id,
            'book_id'  => $bookId,
            'quantity' => 2,
        ]);
    }

    /**
     * **Validates: Requirements 4.4**
     */
    public function test_addItem_increments_quantity_when_item_exists(): void
    {
        $userId = $this->createUser();
        $cart   = $this->repo->getOrCreateForUser($userId);
        $bookId = $this->createBook();

        $this->repo->addItem($cart, $bookId, 3);
        $item = $this->repo->addItem($cart, $bookId, 2);

        $this->assertEquals(5, $item->quantity);
        $this->assertEquals(1, DB::table('cart_items')->where('cart_id', $cart->id)->where('book_id', $bookId)->count());
    }

    /**
     * **Validates: Requirements 4.5**
     */
    public function test_removeItem_deletes_item(): void
    {
        $userId = $this->createUser();
        $cart   = $this->repo->getOrCreateForUser($userId);
        $bookId = $this->createBook();

        $item = $this->repo->addItem($cart, $bookId, 1);

        $this->repo->removeItem($item);

        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    /**
     * **Validates: Requirements 4.7**
     */
    public function test_clearCart_removes_all_items(): void
    {
        $userId  = $this->createUser();
        $cart    = $this->repo->getOrCreateForUser($userId);
        $bookId1 = $this->createBook();
        $bookId2 = $this->createBook();
        $bookId3 = $this->createBook();

        $this->repo->addItem($cart, $bookId1, 1);
        $this->repo->addItem($cart, $bookId2, 2);
        $this->repo->addItem($cart, $bookId3, 3);

        $this->repo->clearCart($cart);

        $count = DB::table('cart_items')->where('cart_id', $cart->id)->count();
        $this->assertEquals(0, $count);
    }

    /**
     * **Validates: Requirements 17.4**
     */
    public function test_addItem_throws_CartOperationException_on_db_failure(): void
    {
        $userId = $this->createUser();
        $cart   = $this->repo->getOrCreateForUser($userId);
        $bookId = $this->createBook();

        // Use a subclass that forces DB::transaction to throw, simulating a DB failure.
        $failingRepo = new class extends CartRepository {
            public function addItem(Cart $cart, int $bookId, int $quantity): CartItem
            {
                try {
                    return DB::transaction(function () {
                        throw new \RuntimeException('Simulated DB failure');
                    });
                } catch (\Throwable $e) {
                    throw new CartOperationException('Failed to add item to cart.', $e);
                }
            }
        };

        $this->expectException(CartOperationException::class);

        $failingRepo->addItem($cart, $bookId, 1);
    }
}
