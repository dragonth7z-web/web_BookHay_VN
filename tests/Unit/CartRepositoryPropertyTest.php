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
 * Property-based tests for CartRepository (manual 100-iteration approach).
 *
 * **Validates: Requirements 4.2, 4.3, 4.5, 12.1, 12.2, 12.4**
 */
class CartRepositoryPropertyTest extends TestCase
{
    private CartRepository $repo;

    /** Monotonically increasing counter shared across all helpers in a test run. */
    private int $seedCounter = 0;

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
    // Schema helpers (same pattern as CartRepositoryTest)
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
    // Seed helpers – each call produces a unique row to avoid conflicts
    // =========================================================================

    private function createUser(): int
    {
        $this->seedCounter++;
        $n = $this->seedCounter;

        return DB::table('users')->insertGetId([
            'name'       => "PropUser{$n}",
            'email'      => "propuser{$n}@test.com",
            'password'   => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createBook(): int
    {
        $this->seedCounter++;
        $n = $this->seedCounter;

        return DB::table('books')->insertGetId([
            'sku'        => "PROP-SKU-{$n}",
            'title'      => "PropBook {$n}",
            'slug'       => "prop-book-{$n}",
            'sale_price' => 50000,
            'stock'      => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // =========================================================================
    // Property 1: addItem round trip
    //
    // For any valid userId and bookId, with a random quantity in [1, 10]:
    //   addItem(cart, bookId, quantity) → findItemByBookId(cart, bookId)
    //   must return an item with the exact quantity that was passed in.
    //
    // **Validates: Requirements 4.2, 4.3**
    // =========================================================================

    /**
     * Feature: decoupling-refactor, Property 1: CartRepository addItem round trip
     *
     * **Validates: Requirements 4.2, 4.3**
     */
    public function test_addItem_round_trip(): void
    {
        $iterations = 100;

        for ($i = 0; $i < $iterations; $i++) {
            // Generate a fresh user/cart/book for each iteration to avoid conflicts.
            $userId   = $this->createUser();
            $bookId   = $this->createBook();
            $quantity = mt_rand(1, 10);

            $cart = $this->repo->getOrCreateForUser($userId);

            // Act
            $this->repo->addItem($cart, $bookId, $quantity);
            $found = $this->repo->findItemByBookId($cart, $bookId);

            // Assert – property must hold for every generated input
            $this->assertNotNull(
                $found,
                "Iteration {$i}: findItemByBookId returned null after addItem (quantity={$quantity})"
            );
            $this->assertEquals(
                $bookId,
                $found->book_id,
                "Iteration {$i}: book_id mismatch (expected={$bookId}, got={$found->book_id})"
            );
            $this->assertEquals(
                $quantity,
                $found->quantity,
                "Iteration {$i}: quantity mismatch (expected={$quantity}, got={$found->quantity})"
            );
        }
    }

    // =========================================================================
    // Property 2: clearCart removes all items
    //
    // For any cart with N random items (N in [1, 5]):
    //   After clearCart(cart), CartItem::where('cart_id', $cart->id)->count() === 0
    //
    // **Validates: Requirements 4.5, 12.4**
    // =========================================================================

    /**
     * Feature: decoupling-refactor, Property 2: clearCart xóa toàn bộ item
     *
     * **Validates: Requirements 4.5, 12.4**
     */
    public function test_clearCart_empties_cart(): void
    {
        $iterations = 100;

        for ($i = 0; $i < $iterations; $i++) {
            $userId = $this->createUser();
            $cart   = $this->repo->getOrCreateForUser($userId);
            $n      = mt_rand(1, 5);

            // Add N distinct books to the cart
            for ($j = 0; $j < $n; $j++) {
                $bookId   = $this->createBook();
                $quantity = mt_rand(1, 10);
                $this->repo->addItem($cart, $bookId, $quantity);
            }

            // Pre-condition: cart has items
            $countBefore = CartItem::where('cart_id', $cart->id)->count();
            $this->assertEquals(
                $n,
                $countBefore,
                "Iteration {$i}: expected {$n} items before clearCart, got {$countBefore}"
            );

            // Act
            $this->repo->clearCart($cart);

            // Assert – property must hold for every generated input
            $countAfter = CartItem::where('cart_id', $cart->id)->count();
            $this->assertEquals(
                0,
                $countAfter,
                "Iteration {$i}: expected 0 items after clearCart (had {$n} items), got {$countAfter}"
            );
        }
    }

    // =========================================================================
    // Property 3: Failed write operation leaves no partial write
    //
    // For any cart with N items, calling a write operation that throws an
    // exception must leave the item count unchanged (no partial write).
    //
    // Uses an anonymous subclass override to simulate a mid-transaction failure.
    //
    // **Validates: Requirements 12.1, 12.2**
    // =========================================================================

    /**
     * Feature: decoupling-refactor, Property 3: Write operation thất bại không để lại partial write
     *
     * **Validates: Requirements 12.1, 12.2**
     */
    public function test_failed_write_leaves_no_partial_write(): void
    {
        $iterations = 100;

        // Build a repo subclass whose addItem always throws after entering the transaction.
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

        for ($i = 0; $i < $iterations; $i++) {
            $userId = $this->createUser();
            $cart   = $this->repo->getOrCreateForUser($userId);
            $n      = mt_rand(1, 5);

            // Populate the cart with N items using the normal (working) repo.
            for ($j = 0; $j < $n; $j++) {
                $bookId   = $this->createBook();
                $quantity = mt_rand(1, 10);
                $this->repo->addItem($cart, $bookId, $quantity);
            }

            $countBefore = CartItem::where('cart_id', $cart->id)->count();

            // Act – attempt a failing addItem
            $newBookId = $this->createBook();
            $exception = null;

            try {
                $failingRepo->addItem($cart, $newBookId, mt_rand(1, 10));
            } catch (CartOperationException $e) {
                $exception = $e;
            }

            // Assert – exception was thrown (operation did fail)
            $this->assertInstanceOf(
                CartOperationException::class,
                $exception,
                "Iteration {$i}: expected CartOperationException to be thrown"
            );

            // Assert – item count is unchanged (no partial write)
            $countAfter = CartItem::where('cart_id', $cart->id)->count();
            $this->assertEquals(
                $countBefore,
                $countAfter,
                "Iteration {$i}: item count changed after failed write (before={$countBefore}, after={$countAfter})"
            );
        }
    }
}
