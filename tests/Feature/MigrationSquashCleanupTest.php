<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Property-based tests for Migration Squash & Cleanup spec.
 *
 * Properties verified:
 *   Property 1 — Table count preserved after migrate:fresh
 *   Property 2 — Schema equivalence: English column names
 *   Property 3 — Enum values match specification (MySQL only)
 *   Property 4 — FK constraints enforced after migrate:fresh
 *   Property 6 — Idempotence of migrate:fresh
 */
class MigrationSquashCleanupTest extends TestCase
{
    use RefreshDatabase;

    private bool $isMysql;

    protected function setUp(): void
    {
        parent::setUp();
        $this->isMysql = DB::getDriverName() === 'mysql';
    }

    // -------------------------------------------------------------------------
    // Property 1: Table count preserved after migrate:fresh
    // Validates: Requirement 8.1
    // -------------------------------------------------------------------------

    #[\PHPUnit\Framework\Attributes\Test]
    public function property1_table_count_after_migrate_fresh(): void
    {
        if ($this->isMysql) {
            // MySQL: count all tables except `migrations`
            $tables = collect(DB::select('SHOW TABLES'))
                ->map(fn($row) => array_values((array) $row)[0])
                ->reject(fn($t) => $t === 'migrations')
                ->count();

            // 42 application tables (43 total - 1 migrations table)
            $this->assertEquals(42, $tables,
                "Property 1 FAILED: Expected 42 application tables after migrate:fresh, got {$tables}.");
        } else {
            // SQLite: use Schema::getTables() (Laravel 11+)
            $tables = collect(Schema::getTables())
                ->pluck('name')
                ->reject(fn($t) => $t === 'migrations')
                ->count();

            // SQLite doesn't support FULLTEXT, YEAR, etc. — table count may differ
            $this->assertGreaterThan(0, $tables,
                'Property 1 FAILED: No tables found after migrate:fresh.');
        }
    }

    // -------------------------------------------------------------------------
    // Property 2: Schema equivalence — English column names
    // Validates: Requirements 5.2, 5.5
    // -------------------------------------------------------------------------

    #[\PHPUnit\Framework\Attributes\Test]
    public function property2_books_table_has_english_columns(): void
    {
        $required = ['id', 'sku', 'title', 'slug', 'category_id', 'publisher_id',
                     'cost_price', 'original_price', 'sale_price', 'stock', 'sold_count',
                     'description', 'short_description', 'cover_image', 'isbn', 'pages',
                     'weight', 'cover_type', 'language', 'rating_avg', 'rating_count',
                     'status', 'is_featured', 'created_at', 'updated_at', 'deleted_at'];

        $columns = Schema::getColumnListing('books');

        foreach ($required as $col) {
            $this->assertContains($col, $columns, "Property 2 FAILED: books.{$col} missing.");
        }

        // Ensure no Vietnamese column names remain
        $vietnamese = ['ten_sach', 'gia_ban', 'trang_thai', 'loai_bia', 'mo_ta'];
        foreach ($vietnamese as $col) {
            $this->assertNotContains($col, $columns,
                "Property 2 FAILED: Vietnamese column books.{$col} still exists.");
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function property2_users_table_has_english_columns(): void
    {
        $required = ['id', 'name', 'email', 'phone', 'password', 'avatar',
                     'date_of_birth', 'gender', 'role_id', 'status',
                     'loyalty_points', 'total_spent', 'created_at', 'updated_at', 'deleted_at'];

        $columns = Schema::getColumnListing('users');

        foreach ($required as $col) {
            $this->assertContains($col, $columns, "Property 2 FAILED: users.{$col} missing.");
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function property2_orders_table_has_english_columns(): void
    {
        $required = ['id', 'order_number', 'user_id', 'recipient_name', 'recipient_phone',
                     'shipping_address', 'subtotal', 'shipping_fee', 'discount_amount', 'total',
                     'coupon_id', 'payment_method', 'payment_status', 'status',
                     'notes', 'cancel_reason', 'created_at', 'updated_at'];

        $columns = Schema::getColumnListing('orders');

        foreach ($required as $col) {
            $this->assertContains($col, $columns, "Property 2 FAILED: orders.{$col} missing.");
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function property2_roles_table_has_english_columns(): void
    {
        $required = ['id', 'code', 'name', 'description'];
        $columns = Schema::getColumnListing('roles');

        foreach ($required as $col) {
            $this->assertContains($col, $columns, "Property 2 FAILED: roles.{$col} missing.");
        }
    }

    // -------------------------------------------------------------------------
    // Property 3: Enum values match specification (MySQL only)
    // Validates: Requirement 5.3
    // -------------------------------------------------------------------------

    #[\PHPUnit\Framework\Attributes\Test]
    public function property3_users_gender_enum_values(): void
    {
        if (!$this->isMysql) {
            $this->markTestSkipped('Property 3 (enum introspection) requires MySQL.');
        }

        $row = DB::selectOne("SHOW COLUMNS FROM users LIKE 'gender'");
        $this->assertNotNull($row, 'Property 3 FAILED: users.gender column not found.');
        $this->assertStringContainsString("enum('male','female','other')", $row->Type,
            "Property 3 FAILED: users.gender enum values incorrect. Got: {$row->Type}");
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function property3_users_status_enum_values(): void
    {
        if (!$this->isMysql) {
            $this->markTestSkipped('Property 3 (enum introspection) requires MySQL.');
        }

        $row = DB::selectOne("SHOW COLUMNS FROM users LIKE 'status'");
        $this->assertNotNull($row, 'Property 3 FAILED: users.status column not found.');
        $this->assertStringContainsString("enum('active','suspended')", $row->Type,
            "Property 3 FAILED: users.status enum values incorrect. Got: {$row->Type}");
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function property3_books_status_enum_values(): void
    {
        if (!$this->isMysql) {
            $this->markTestSkipped('Property 3 (enum introspection) requires MySQL.');
        }

        $row = DB::selectOne("SHOW COLUMNS FROM books LIKE 'status'");
        $this->assertNotNull($row, 'Property 3 FAILED: books.status column not found.');
        $this->assertStringContainsString("enum('in_stock','out_of_stock','discontinued')", $row->Type,
            "Property 3 FAILED: books.status enum values incorrect. Got: {$row->Type}");
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function property3_books_cover_type_enum_values(): void
    {
        if (!$this->isMysql) {
            $this->markTestSkipped('Property 3 (enum introspection) requires MySQL.');
        }

        $row = DB::selectOne("SHOW COLUMNS FROM books LIKE 'cover_type'");
        $this->assertNotNull($row, 'Property 3 FAILED: books.cover_type column not found.');
        $this->assertStringContainsString("enum('hardcover','paperback')", $row->Type,
            "Property 3 FAILED: books.cover_type enum values incorrect. Got: {$row->Type}");
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function property3_orders_status_enum_values(): void
    {
        if (!$this->isMysql) {
            $this->markTestSkipped('Property 3 (enum introspection) requires MySQL.');
        }

        $row = DB::selectOne("SHOW COLUMNS FROM orders LIKE 'status'");
        $this->assertNotNull($row, 'Property 3 FAILED: orders.status column not found.');
        $expected = "enum('pending','confirmed','shipping','delivered','completed','cancelled','returned')";
        $this->assertStringContainsString($expected, $row->Type,
            "Property 3 FAILED: orders.status enum values incorrect. Got: {$row->Type}");
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function property3_books_fulltext_index_exists(): void
    {
        if (!$this->isMysql) {
            $this->markTestSkipped('Property 3 (FULLTEXT index) requires MySQL.');
        }

        $indexes = DB::select(
            "SHOW INDEX FROM books WHERE Key_name = 'idx_search' AND Index_type = 'FULLTEXT'"
        );
        $this->assertNotEmpty($indexes,
            'Property 3 FAILED: FULLTEXT index idx_search on books table not found.');
    }

    // -------------------------------------------------------------------------
    // Property 4: FK constraints enforced after migrate:fresh
    // Validates: Requirements 5.4, 8.2
    // -------------------------------------------------------------------------

    #[\PHPUnit\Framework\Attributes\Test]
    public function property4_books_category_id_fk_enforced(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        DB::table('books')->insert([
            'sku'            => 'TEST-FK-001',
            'title'          => 'FK Test Book',
            'slug'           => 'fk-test-book',
            'category_id'    => 99999, // does not exist
            'original_price' => 0,
            'sale_price'     => 0,
            'cost_price'     => 0,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function property4_users_role_id_fk_enforced(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        DB::table('users')->insert([
            'name'     => 'FK Test User',
            'email'    => 'fktest@example.com',
            'password' => bcrypt('password'),
            'role_id'  => 99, // does not exist in roles
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function property4_order_items_order_id_fk_enforced(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        DB::table('order_items')->insert([
            'order_id'            => 99999, // does not exist
            'book_id'             => 1,
            'book_title_snapshot' => 'Test',
            'quantity'            => 1,
            'unit_price'          => 100000,
            'subtotal'            => 100000,
        ]);
    }

    // -------------------------------------------------------------------------
    // Property 6: Idempotence of migrate:fresh
    // Validates: Requirements 5.5, 8.4
    // -------------------------------------------------------------------------

    #[\PHPUnit\Framework\Attributes\Test]
    public function property6_migrate_fresh_is_idempotent(): void
    {
        if (!$this->isMysql) {
            $this->markTestSkipped('Property 6 (migrate:fresh idempotence) requires MySQL — SQLite in-memory cannot VACUUM within a transaction.');
        }

        // Run a second migrate:fresh (first was done by RefreshDatabase)
        $exitCode = Artisan::call('migrate:fresh');

        $this->assertEquals(0, $exitCode,
            'Property 6 FAILED: Second migrate:fresh returned non-zero exit code.');

        $tables = collect(DB::select('SHOW TABLES'))
            ->map(fn($row) => array_values((array) $row)[0])
            ->reject(fn($t) => $t === 'migrations')
            ->count();

        $this->assertEquals(42, $tables,
            "Property 6 FAILED: Table count after second migrate:fresh is {$tables}, expected 42.");
    }
}
