<?php

namespace Tests\Unit;

use App\Repositories\BookRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Property-based test for BookRepository::decrementStock().
 *
 * Property 8: BookRepository::decrementStock() bảo toàn tổng
 * Với mọi `quantity` hợp lệ:
 *   - stock_mới  = stock_cũ  - quantity
 *   - sold_count_mới = sold_count_cũ + quantity
 *   - (stock + sold_count) không đổi
 *
 * **Validates: Requirements 4.1, 4.2**
 */
class BookRepositoryDecrementStockTest extends TestCase
{
    private BookRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
        $this->repo = new BookRepository();
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
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('set null');
        });
    }

    private function dropSchema(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('books');
        Schema::dropIfExists('users');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('roles');
        Schema::enableForeignKeyConstraints();
    }

    // =========================================================================
    // Seed helper
    // =========================================================================

    private function seedBook(int $stock, int $soldCount): int
    {
        static $counter = 0;
        $counter++;

        return DB::table('books')->insertGetId([
            'sku'            => "BOOK-{$counter}",
            'title'          => "Test Book {$counter}",
            'slug'           => "test-book-{$counter}",
            'category_id'    => null,
            'publisher_id'   => null,
            'stock'          => $stock,
            'sold_count'     => $soldCount,
            'cost_price'     => 10000,
            'original_price' => 20000,
            'sale_price'     => 18000,
            'cover_type'     => 'paperback',
            'status'         => 'in_stock',
            'is_featured'    => false,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    // =========================================================================
    // Property 8: decrementStock() bảo toàn tổng
    // =========================================================================

    /**
     * Property 8: stock_mới = stock_cũ - quantity.
     *
     * **Validates: Requirements 4.1, 4.2**
     */
    public function test_property8_stock_decreases_by_quantity(): void
    {
        $quantities = [1, 2, 5, 10, 50];

        foreach ($quantities as $qty) {
            $initialStock = 100;
            $initialSold  = 20;

            $bookId = $this->seedBook($initialStock, $initialSold);

            $this->repo->decrementStock($bookId, $qty);

            $book = DB::table('books')->where('id', $bookId)->first();

            $this->assertEquals(
                $initialStock - $qty,
                $book->stock,
                "stock should decrease by {$qty}: expected " . ($initialStock - $qty) . ", got {$book->stock}"
            );
        }
    }

    /**
     * Property 8: sold_count_mới = sold_count_cũ + quantity.
     *
     * **Validates: Requirements 4.1, 4.2**
     */
    public function test_property8_sold_count_increases_by_quantity(): void
    {
        $quantities = [1, 2, 5, 10, 50];

        foreach ($quantities as $qty) {
            $initialStock = 100;
            $initialSold  = 20;

            $bookId = $this->seedBook($initialStock, $initialSold);

            $this->repo->decrementStock($bookId, $qty);

            $book = DB::table('books')->where('id', $bookId)->first();

            $this->assertEquals(
                $initialSold + $qty,
                $book->sold_count,
                "sold_count should increase by {$qty}: expected " . ($initialSold + $qty) . ", got {$book->sold_count}"
            );
        }
    }

    /**
     * Property 8: (stock + sold_count) invariant — tổng không đổi sau mỗi lần gọi.
     *
     * **Validates: Requirements 4.1, 4.2**
     */
    public function test_property8_sum_stock_plus_sold_count_is_preserved(): void
    {
        $cases = [
            ['stock' => 100, 'sold' => 0,  'qty' => 1],
            ['stock' => 100, 'sold' => 0,  'qty' => 50],
            ['stock' => 50,  'sold' => 30, 'qty' => 10],
            ['stock' => 200, 'sold' => 5,  'qty' => 100],
            ['stock' => 1,   'sold' => 99, 'qty' => 1],
        ];

        foreach ($cases as $case) {
            $bookId = $this->seedBook($case['stock'], $case['sold']);

            $sumBefore = $case['stock'] + $case['sold'];

            $this->repo->decrementStock($bookId, $case['qty']);

            $book     = DB::table('books')->where('id', $bookId)->first();
            $sumAfter = $book->stock + $book->sold_count;

            $this->assertEquals(
                $sumBefore,
                $sumAfter,
                "stock + sold_count invariant violated for qty={$case['qty']}: " .
                "before={$sumBefore}, after={$sumAfter}"
            );
        }
    }

    /**
     * Property 8: decrementStock() with quantity=1 (minimum valid quantity).
     *
     * **Validates: Requirements 4.1, 4.2**
     */
    public function test_property8_decrement_by_one(): void
    {
        $bookId = $this->seedBook(10, 5);

        $this->repo->decrementStock($bookId, 1);

        $book = DB::table('books')->where('id', $bookId)->first();

        $this->assertEquals(9, $book->stock);
        $this->assertEquals(6, $book->sold_count);
    }

    /**
     * Property 8: decrementStock() with quantity equal to full stock.
     *
     * **Validates: Requirements 4.1, 4.2**
     */
    public function test_property8_decrement_full_stock(): void
    {
        $bookId = $this->seedBook(50, 10);

        $this->repo->decrementStock($bookId, 50);

        $book = DB::table('books')->where('id', $bookId)->first();

        $this->assertEquals(0, $book->stock);
        $this->assertEquals(60, $book->sold_count);
        $this->assertEquals(60, $book->stock + $book->sold_count);
    }

    /**
     * Property 8: multiple sequential decrements preserve the invariant cumulatively.
     *
     * **Validates: Requirements 4.1, 4.2**
     */
    public function test_property8_multiple_sequential_decrements_preserve_invariant(): void
    {
        $initialStock = 100;
        $initialSold  = 0;
        $bookId       = $this->seedBook($initialStock, $initialSold);

        $quantities   = [3, 7, 10, 5, 15];
        $totalDecrement = 0;

        foreach ($quantities as $qty) {
            $this->repo->decrementStock($bookId, $qty);
            $totalDecrement += $qty;

            $book = DB::table('books')->where('id', $bookId)->first();

            $this->assertEquals(
                $initialStock - $totalDecrement,
                $book->stock,
                "After cumulative decrement of {$totalDecrement}, stock should be " . ($initialStock - $totalDecrement)
            );
            $this->assertEquals(
                $initialSold + $totalDecrement,
                $book->sold_count,
                "After cumulative decrement of {$totalDecrement}, sold_count should be " . ($initialSold + $totalDecrement)
            );
            $this->assertEquals(
                $initialStock + $initialSold,
                $book->stock + $book->sold_count,
                "Invariant (stock + sold_count) must remain constant"
            );
        }
    }
}
