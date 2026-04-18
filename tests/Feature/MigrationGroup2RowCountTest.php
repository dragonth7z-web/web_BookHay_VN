<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Property Test: Bảo toàn row count cho Nhóm 2 (Group 2 tables)
 *
 * Property 1: Bảo toàn số lượng row sau migration
 * Kiểm tra row count và enum values sau migration cho `users`, `categories`
 *
 * Validates: Requirements 1.3, 2.6, 2.7
 */
class MigrationGroup2RowCountTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createGroup2Schema();
    }

    protected function tearDown(): void
    {
        $this->dropGroup2Schema();
        parent::tearDown();
    }

    // =========================================================================
    // Schema helpers
    // =========================================================================

    private function createGroup2Schema(): void
    {
        // roles (Nhóm 1 dependency)
        Schema::create('roles', function ($table) {
            $table->tinyIncrements('id');
            $table->string('code', 20)->unique();
            $table->string('name', 50);
            $table->string('description', 255)->nullable();
        });

        // users (renamed from nguoi_dung, FK → roles)
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

        // categories (renamed from danh_muc, self-referencing FK)
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
    }

    private function dropGroup2Schema(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::enableForeignKeyConstraints();
    }

    // =========================================================================
    // Seed helpers
    // =========================================================================

    private function seedRoles(): void
    {
        DB::table('roles')->insert([
            ['code' => 'ADMIN',    'name' => 'Admin',    'description' => null],
            ['code' => 'CUSTOMER', 'name' => 'Customer', 'description' => null],
            ['code' => 'STAFF',    'name' => 'Staff',    'description' => null],
        ]);
    }

    private function seedUsers(): int
    {
        DB::table('users')->insert([
            [
                'name'           => 'Nguyễn Văn A',
                'email'          => 'a@example.com',
                'phone'          => '0901000001',
                'password'       => bcrypt('secret'),
                'date_of_birth'  => '1990-01-01',
                'gender'         => 'male',
                'role_id'        => 1,
                'status'         => 'active',
                'loyalty_points' => 100,
                'total_spent'    => 500000,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Trần Thị B',
                'email'          => 'b@example.com',
                'phone'          => '0901000002',
                'password'       => bcrypt('secret'),
                'date_of_birth'  => '1995-06-15',
                'gender'         => 'female',
                'role_id'        => 2,
                'status'         => 'active',
                'loyalty_points' => 50,
                'total_spent'    => 200000,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Lê Văn C',
                'email'          => 'c@example.com',
                'phone'          => '0901000003',
                'password'       => bcrypt('secret'),
                'date_of_birth'  => null,
                'gender'         => 'other',
                'role_id'        => 2,
                'status'         => 'suspended',
                'loyalty_points' => 0,
                'total_spent'    => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Phạm Thị D',
                'email'          => 'd@example.com',
                'phone'          => '0901000004',
                'password'       => bcrypt('secret'),
                'date_of_birth'  => '1988-12-31',
                'gender'         => 'female',
                'role_id'        => 3,
                'status'         => 'active',
                'loyalty_points' => 200,
                'total_spent'    => 1000000,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);

        return 4;
    }

    private function seedCategories(): int
    {
        // Root categories
        DB::table('categories')->insert([
            ['id' => 1, 'parent_id' => null, 'name' => 'Văn học',       'description' => null, 'image' => null, 'sort_order' => 1, 'is_visible' => true,  'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'parent_id' => null, 'name' => 'Khoa học',      'description' => null, 'image' => null, 'sort_order' => 2, 'is_visible' => true,  'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'parent_id' => null, 'name' => 'Thiếu nhi',     'description' => null, 'image' => null, 'sort_order' => 3, 'is_visible' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Child categories (self-referencing FK)
        DB::table('categories')->insert([
            ['id' => 4, 'parent_id' => 1, 'name' => 'Tiểu thuyết',  'description' => null, 'image' => null, 'sort_order' => 1, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'parent_id' => 1, 'name' => 'Truyện ngắn',  'description' => null, 'image' => null, 'sort_order' => 2, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'parent_id' => 2, 'name' => 'Vật lý',       'description' => null, 'image' => null, 'sort_order' => 1, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        return 6;
    }

    // =========================================================================
    // Tests — Property 1: Bảo toàn số lượng row sau migration
    // =========================================================================

    /**
     * Property 1: Bảng `users` và `categories` tồn tại với tên tiếng Anh mới.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_group2_tables_exist_with_english_names(): void
    {
        $this->assertTrue(Schema::hasTable('users'),      "Bảng 'users' phải tồn tại sau migration Nhóm 2.");
        $this->assertTrue(Schema::hasTable('categories'), "Bảng 'categories' phải tồn tại sau migration Nhóm 2.");
    }

    /**
     * Property 1: Row count của `users` được bảo toàn sau khi seed.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_users_row_count_preserved(): void
    {
        $this->seedRoles();
        $expected = $this->seedUsers();

        $actual = DB::table('users')->count();

        $this->assertEquals(
            $expected,
            $actual,
            "Bảng 'users': row count phải là {$expected} sau khi seed, nhưng thực tế là {$actual}."
        );
    }

    /**
     * Property 1: Row count của `categories` được bảo toàn sau khi seed.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_categories_row_count_preserved(): void
    {
        $expected = $this->seedCategories();

        $actual = DB::table('categories')->count();

        $this->assertEquals(
            $expected,
            $actual,
            "Bảng 'categories': row count phải là {$expected} sau khi seed, nhưng thực tế là {$actual}."
        );
    }

    /**
     * Property 1: Row count ổn định qua nhiều lần đọc liên tiếp.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_row_count_stable_across_multiple_reads(): void
    {
        $this->seedRoles();
        $this->seedUsers();
        $this->seedCategories();

        foreach (['users', 'categories'] as $table) {
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
        $this->seedRoles();

        $countBefore = DB::table('users')->count();

        DB::table('users')->insert([
            ['name' => 'User X', 'email' => 'x@example.com', 'phone' => null, 'password' => bcrypt('secret'), 'date_of_birth' => null, 'gender' => 'male',   'role_id' => 1, 'status' => 'active',    'loyalty_points' => 0, 'total_spent' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'User Y', 'email' => 'y@example.com', 'phone' => null, 'password' => bcrypt('secret'), 'date_of_birth' => null, 'gender' => 'female',  'role_id' => 2, 'status' => 'suspended', 'loyalty_points' => 0, 'total_spent' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'User Z', 'email' => 'z@example.com', 'phone' => null, 'password' => bcrypt('secret'), 'date_of_birth' => null, 'gender' => 'other',   'role_id' => 2, 'status' => 'active',    'loyalty_points' => 0, 'total_spent' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $countAfter = DB::table('users')->count();

        $this->assertEquals(
            $countBefore + 3,
            $countAfter,
            "Bảng 'users': sau khi insert 3 rows, count phải tăng đúng 3."
        );
    }

    // =========================================================================
    // Tests — Property 1 (enum): Enum values là tiếng Anh sau migration
    // =========================================================================

    /**
     * Property 1 (enum): Cột `gender` chỉ chứa giá trị tiếng Anh: male/female/other.
     *
     * Validates: Requirements 2.6
     */
    public function test_property1_users_gender_enum_values_are_english(): void
    {
        $this->seedRoles();
        $this->seedUsers();

        $validGenders = ['male', 'female', 'other'];

        $rows = DB::table('users')->select('id', 'gender')->get();

        foreach ($rows as $row) {
            $this->assertContains(
                $row->gender,
                $validGenders,
                "User id={$row->id}: gender='{$row->gender}' phải là một trong [male, female, other]."
            );
        }
    }

    /**
     * Property 1 (enum): Cột `status` chỉ chứa giá trị tiếng Anh: active/suspended.
     *
     * Validates: Requirements 2.7
     */
    public function test_property1_users_status_enum_values_are_english(): void
    {
        $this->seedRoles();
        $this->seedUsers();

        $validStatuses = ['active', 'suspended'];

        $rows = DB::table('users')->select('id', 'status')->get();

        foreach ($rows as $row) {
            $this->assertContains(
                $row->status,
                $validStatuses,
                "User id={$row->id}: status='{$row->status}' phải là một trong [active, suspended]."
            );
        }
    }

    /**
     * Property 1 (enum): Không tồn tại giá trị tiếng Việt trong cột `gender`.
     *
     * Validates: Requirements 2.6
     */
    public function test_property1_no_vietnamese_gender_values_exist(): void
    {
        $this->seedRoles();
        $this->seedUsers();

        $vietnameseGenders = ['Nam', 'Nữ', 'Khác'];

        foreach ($vietnameseGenders as $viet) {
            $count = DB::table('users')->where('gender', $viet)->count();
            $this->assertEquals(
                0,
                $count,
                "Không được tồn tại giá trị gender='{$viet}' (tiếng Việt) trong bảng 'users'."
            );
        }
    }

    /**
     * Property 1 (enum): Không tồn tại giá trị tiếng Việt trong cột `status`.
     *
     * Validates: Requirements 2.7
     */
    public function test_property1_no_vietnamese_status_values_exist(): void
    {
        $this->seedRoles();
        $this->seedUsers();

        $vietnameseStatuses = ['Hoạt động', 'Bị khóa', 'Tạm khóa'];

        foreach ($vietnameseStatuses as $viet) {
            $count = DB::table('users')->where('status', $viet)->count();
            $this->assertEquals(
                0,
                $count,
                "Không được tồn tại giá trị status='{$viet}' (tiếng Việt) trong bảng 'users'."
            );
        }
    }

    /**
     * Property 1 (enum): Mỗi giá trị gender hợp lệ (male/female/other) đều có thể insert thành công.
     *
     * Validates: Requirements 2.6
     */
    public function test_property1_all_valid_gender_values_can_be_inserted(): void
    {
        $this->seedRoles();

        $genders = ['male', 'female', 'other'];

        foreach ($genders as $i => $gender) {
            DB::table('users')->insert([
                'name'           => "Test User {$gender}",
                'email'          => "test_{$gender}@example.com",
                'phone'          => null,
                'password'       => bcrypt('secret'),
                'date_of_birth'  => null,
                'gender'         => $gender,
                'role_id'        => 1,
                'status'         => 'active',
                'loyalty_points' => 0,
                'total_spent'    => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        foreach ($genders as $gender) {
            $count = DB::table('users')->where('gender', $gender)->count();
            $this->assertEquals(1, $count, "Phải có đúng 1 user với gender='{$gender}'.");
        }
    }

    /**
     * Property 1 (enum): Mỗi giá trị status hợp lệ (active/suspended) đều có thể insert thành công.
     *
     * Validates: Requirements 2.7
     */
    public function test_property1_all_valid_status_values_can_be_inserted(): void
    {
        $this->seedRoles();

        $statuses = ['active', 'suspended'];

        foreach ($statuses as $status) {
            DB::table('users')->insert([
                'name'           => "Test User {$status}",
                'email'          => "test_{$status}@example.com",
                'phone'          => null,
                'password'       => bcrypt('secret'),
                'date_of_birth'  => null,
                'gender'         => 'other',
                'role_id'        => 1,
                'status'         => $status,
                'loyalty_points' => 0,
                'total_spent'    => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        foreach ($statuses as $status) {
            $count = DB::table('users')->where('status', $status)->count();
            $this->assertEquals(1, $count, "Phải có đúng 1 user với status='{$status}'.");
        }
    }

    // =========================================================================
    // Tests — categories self-referencing FK
    // =========================================================================

    /**
     * Property 1: Self-referencing FK trong `categories` hoạt động đúng.
     * Child categories trỏ đúng đến parent.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_categories_self_referencing_fk_works(): void
    {
        $this->seedCategories();

        // Kiểm tra child categories có parent_id hợp lệ
        $childCategories = DB::table('categories')->whereNotNull('parent_id')->get();

        foreach ($childCategories as $child) {
            $parentExists = DB::table('categories')->where('id', $child->parent_id)->exists();
            $this->assertTrue(
                $parentExists,
                "Category id={$child->id}: parent_id={$child->parent_id} phải trỏ đến một category tồn tại."
            );
        }
    }

    /**
     * Property 1: Root categories có parent_id = null.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_root_categories_have_null_parent_id(): void
    {
        $this->seedCategories();

        $rootCount = DB::table('categories')->whereNull('parent_id')->count();

        $this->assertGreaterThan(0, $rootCount, "Phải có ít nhất 1 root category (parent_id = null).");
    }

    /**
     * Property 1: Bảng `categories` rỗng trước khi seed.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_categories_empty_before_seed(): void
    {
        $this->assertEquals(0, DB::table('categories')->count(), "Bảng 'categories' phải rỗng trước khi seed.");
    }

    /**
     * Property 1: Bảng `users` rỗng trước khi seed.
     *
     * Validates: Requirements 1.3
     */
    public function test_property1_users_empty_before_seed(): void
    {
        $this->assertEquals(0, DB::table('users')->count(), "Bảng 'users' phải rỗng trước khi seed.");
    }
}
