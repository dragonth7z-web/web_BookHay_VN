<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase as BaseTestCase;

/**
 * PHPUnit Feature Tests — Rate Limiting (Task 3.1)
 *
 * Kiểm tra throttle middleware `throttle:5,1` trên route POST /register.
 * 6 requests từ cùng IP trong 1 phút → request thứ 6 phải trả về HTTP 429.
 *
 * _Requirements: 10.2_
 */
class RateLimitingTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalSchema();
        $this->clearRateLimits();
    }

    protected function tearDown(): void
    {
        $this->clearRateLimits();
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

    private function clearRateLimits(): void
    {
        RateLimiter::clear('127.0.0.1');
        // Clear theo key format mà Laravel throttle middleware tạo ra
        RateLimiter::clear(sha1('127.0.0.1'));
    }

    // ─── Payload helpers ──────────────────────────────────────────────────────

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'ho_ten'                => 'Nguyen Van A',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'so_dien_thoai'         => '0901234567',
        ], $overrides);
    }

    // =========================================================================
    // TEST: 6 requests từ cùng IP trong 1 phút → request thứ 6 trả về HTTP 429
    // Validates: Requirements 10.2
    // =========================================================================

    /**
     * Test: 6 requests POST /register từ cùng IP trong 1 phút phải bị throttle ở request thứ 6.
     *
     * Route có middleware `throttle:5,1` (5 requests/phút).
     * Requests 1-5 được xử lý bình thường (có thể fail validation nhưng throttle vẫn đếm).
     * Request thứ 6 phải trả về HTTP 429 Too Many Requests.
     *
     * **Validates: Requirements 10.2**
     */
    public function test_6_requests_cung_ip_trong_1_phut_request_thu_6_tra_ve_429(): void
    {
        $ip = '127.0.0.1';

        // Gửi 5 requests đầu tiên — throttle đếm nhưng chưa block
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])
                             ->post(route('register.post'), $this->validPayload([
                                 'email' => "user{$i}@example.com",
                             ]));

            // Các request này không được là 429
            $this->assertNotEquals(
                429,
                $response->getStatusCode(),
                "Request #{$i} không được bị throttle (chỉ có {$i}/5 requests)"
            );
        }

        // Request thứ 6 phải bị throttle → HTTP 429
        $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])
                         ->post(route('register.post'), $this->validPayload([
                             'email' => 'user6@example.com',
                         ]));

        $response->assertStatus(429);
    }
}
