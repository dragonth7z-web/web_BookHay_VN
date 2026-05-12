<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase as BaseTestCase;

/**
 * PHPUnit Integration Tests — GET /auth/check-email (Task 2.1)
 *
 * Kiểm tra endpoint kiểm tra email tồn tại phục vụ AJAX validation phía client.
 *
 * _Requirements: 2.5, 2.6_
 */
class CheckEmailTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalSchema();
    }

    protected function tearDown(): void
    {
        $this->dropMinimalSchema();
        parent::tearDown();
    }

    // ─── Schema helpers ───────────────────────────────────────────────────────

    private function createMinimalSchema(): void
    {
        Schema::create('roles', function ($table) {
            $table->tinyIncrements('id');
            $table->string('code', 20)->unique();
            $table->string('name', 50);
            $table->string('description', 255)->nullable();
        });

        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('phone', 15)->nullable();
            $table->string('password', 255);
            $table->string('avatar', 255)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 10)->default('other');
            $table->unsignedTinyInteger('role_id')->default(2);
            $table->string('status', 20)->default('active');
            $table->unsignedInteger('loyalty_points')->default(0);
            $table->decimal('total_spent', 15, 0)->default(0);
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });

        Schema::create('system_logs', function ($table) {
            $table->id();
            $table->string('type', 30)->index();
            $table->string('action', 50)->index();
            $table->string('level', 20)->default('info')->index();
            $table->text('description');
            $table->string('object_type', 100)->nullable();
            $table->unsignedBigInteger('object_id')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->string('url', 500)->nullable();
            $table->timestamps();
        });

        DB::table('roles')->insert([
            'id'   => 2,
            'code' => 'CUSTOMER',
            'name' => 'Khách hàng',
        ]);
    }

    private function dropMinimalSchema(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('system_logs');
    }

    // ─── Helper ───────────────────────────────────────────────────────────────

    private function checkEmail(string $email): \Illuminate\Testing\TestResponse
    {
        return $this->getJson('/auth/check-email?email=' . urlencode($email));
    }

    // =========================================================================
    // TEST 1: Email đã tồn tại → {"exists": true}
    // Validates: Requirements 2.5, 2.6
    // =========================================================================

    /**
     * Test 1: GET /auth/check-email với email đã tồn tại phải trả về {"exists": true}.
     *
     * **Validates: Requirements 2.5, 2.6**
     */
    public function test_email_da_ton_tai_tra_ve_exists_true(): void
    {
        DB::table('users')->insert([
            'name'           => 'Existing User',
            'email'          => 'existing@test.com',
            'password'       => Hash::make('password123'),
            'role_id'        => 2,
            'status'         => 'active',
            'loyalty_points' => 0,
            'total_spent'    => 0,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $response = $this->checkEmail('existing@test.com');

        $response->assertStatus(200);
        $response->assertJson(['exists' => true]);
    }

    // =========================================================================
    // TEST 2: Email chưa tồn tại → {"exists": false}
    // Validates: Requirements 2.5
    // =========================================================================

    /**
     * Test 2: GET /auth/check-email với email chưa tồn tại phải trả về {"exists": false}.
     *
     * **Validates: Requirements 2.5**
     */
    public function test_email_chua_ton_tai_tra_ve_exists_false(): void
    {
        $response = $this->checkEmail('new@test.com');

        $response->assertStatus(200);
        $response->assertJson(['exists' => false]);
    }

    // =========================================================================
    // TEST 3: Email sai định dạng → {"exists": false}
    // Validates: Requirements 2.5
    // =========================================================================

    /**
     * Test 3: GET /auth/check-email với email sai định dạng phải trả về {"exists": false}.
     *
     * **Validates: Requirements 2.5**
     */
    public function test_email_sai_dinh_dang_tra_ve_exists_false(): void
    {
        $response = $this->checkEmail('invalid-format');

        $response->assertStatus(200);
        $response->assertJson(['exists' => false]);
    }
}
