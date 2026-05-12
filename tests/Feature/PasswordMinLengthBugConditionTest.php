<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase as BaseTestCase;

/**
 * Bug Condition Exploration Test — Password Min Length Validation
 *
 * Mục đích: Xác nhận bug TỒN TẠI trên code CHƯA sửa.
 * Các test này PHẢI FAIL — failure = bug được xác nhận.
 *
 * Bug: AuthController@register dùng min:6 thay vì min:8.
 * - Mật khẩu 6–7 ký tự được chấp nhận (phải bị từ chối)
 * - Mật khẩu < 6 ký tự trả về "6 ký tự" thay vì "8 ký tự"
 *
 * Validates: Requirements 1.1, 1.2, 1.3
 *
 * **Validates: Requirements 1.2**
 */
class PasswordMinLengthBugConditionTest extends BaseTestCase
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

    /**
     * Helper: tạo payload đăng ký hợp lệ với password tùy chỉnh.
     */
    private function registerPayload(string $password, string $email = null): array
    {
        $email = $email ?? 'test_' . uniqid() . '@example.com';
        return [
            'name'                  => 'Test User',
            'email'                 => $email,
            'password'              => $password,
            'password_confirmation' => $password,
            'phone'                 => '0901234567',
        ];
    }

    /**
     * Helper: POST /register với Accept: application/json để nhận 422 thay vì redirect.
     */
    private function postRegisterJson(string $password, string $email = null): \Illuminate\Testing\TestResponse
    {
        return $this->withHeaders(['Accept' => 'application/json'])
                    ->post(route('register.post'), $this->registerPayload($password, $email));
    }

    // =========================================================================
    // TEST 1: Password 6 ký tự — phải bị từ chối (HTTP 422), thực tế được chấp nhận (HTTP 302)
    // Bug: min:6 cho phép password 6 ký tự vượt qua validation
    // =========================================================================

    /**
     * Test 1: POST /register với password "abc123" (6 ký tự) phải trả về HTTP 422
     * với message chứa "8 ký tự".
     *
     * EXPECTED OUTCOME (trên code chưa fix): FAIL
     * - Server trả về HTTP 302 (redirect thành công) thay vì 422
     * - Counterexample: register("abc123") → 302 (bug confirmed: min:6 chấp nhận 6 ký tự)
     *
     * Validates: Requirements 1.2
     */
    public function test_password_6_ky_tu_phai_bi_tu_choi_voi_http_422(): void
    {
        $response = $this->postRegisterJson('abc123');

        // Kỳ vọng: HTTP 422 (validation error) — password 6 ký tự phải bị từ chối
        // Thực tế trên code chưa fix: HTTP 302 (redirect) — bug confirmed
        $response->assertStatus(422);
    }

    /**
     * Test 1b: Message lỗi cho password 6 ký tự phải chứa "8 ký tự".
     *
     * EXPECTED OUTCOME (trên code chưa fix): FAIL
     * - Server trả về HTTP 302 (không có message lỗi nào)
     * - Counterexample: register("abc123") → 302, không có message "8 ký tự"
     *
     * Validates: Requirements 1.2
     */
    public function test_password_6_ky_tu_message_loi_phai_chua_8_ky_tu(): void
    {
        $response = $this->postRegisterJson('abc123');

        // Kỳ vọng: message lỗi chứa "8 ký tự"
        // Thực tế trên code chưa fix: HTTP 302, không có validation error
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);

        $errors = $response->json('errors.password');
        $this->assertNotEmpty($errors, 'Phải có lỗi validation cho trường password');

        $errorMessage = implode(' ', $errors);
        $this->assertStringContainsString(
            '8 ký tự',
            $errorMessage,
            'BUG CONFIRMED: Message lỗi là "' . $errorMessage . '" — phải chứa "8 ký tự" nhưng thực tế không có (server chấp nhận password 6 ký tự)'
        );
    }

    // =========================================================================
    // TEST 2: Password 7 ký tự — phải bị từ chối (HTTP 422), thực tế được chấp nhận (HTTP 302)
    // Bug: min:6 cho phép password 7 ký tự vượt qua validation
    // =========================================================================

    /**
     * Test 2: POST /register với password "abc1234" (7 ký tự) phải trả về HTTP 422
     * với message chứa "8 ký tự".
     *
     * EXPECTED OUTCOME (trên code chưa fix): FAIL
     * - Server trả về HTTP 302 (redirect thành công) thay vì 422
     * - Counterexample: register("abc1234") → 302 (bug confirmed: min:6 chấp nhận 7 ký tự)
     *
     * Validates: Requirements 1.2
     */
    public function test_password_7_ky_tu_phai_bi_tu_choi_voi_http_422(): void
    {
        $response = $this->postRegisterJson('abc1234');

        // Kỳ vọng: HTTP 422 — password 7 ký tự phải bị từ chối
        // Thực tế trên code chưa fix: HTTP 302 — bug confirmed
        $response->assertStatus(422);
    }

    /**
     * Test 2b: Message lỗi cho password 7 ký tự phải chứa "8 ký tự".
     *
     * EXPECTED OUTCOME (trên code chưa fix): FAIL
     * - Server trả về HTTP 302, không có message lỗi
     * - Counterexample: register("abc1234") → 302, không có message "8 ký tự"
     *
     * Validates: Requirements 1.2
     */
    public function test_password_7_ky_tu_message_loi_phai_chua_8_ky_tu(): void
    {
        $response = $this->postRegisterJson('abc1234');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);

        $errors = $response->json('errors.password');
        $this->assertNotEmpty($errors);

        $errorMessage = implode(' ', $errors);
        $this->assertStringContainsString(
            '8 ký tự',
            $errorMessage,
            'BUG CONFIRMED: Message lỗi là "' . $errorMessage . '" — phải chứa "8 ký tự" nhưng thực tế không có (server chấp nhận password 7 ký tự)'
        );
    }

    // =========================================================================
    // TEST 3: Password 5 ký tự — bị từ chối nhưng message sai ("6 ký tự" thay vì "8 ký tự")
    // Bug: message lỗi là "Mật khẩu tối thiểu 6 ký tự" thay vì "Mật khẩu tối thiểu 8 ký tự"
    // =========================================================================

    /**
     * Test 3: POST /register với password "abc12" (5 ký tự) phải trả về HTTP 422
     * với message chứa "8 ký tự", KHÔNG phải "6 ký tự".
     *
     * EXPECTED OUTCOME (trên code chưa fix): FAIL
     * - Server trả về HTTP 422 (đúng — 5 ký tự < min:6 nên bị từ chối)
     * - Nhưng message là "Mật khẩu tối thiểu 6 ký tự" thay vì "8 ký tự"
     * - Counterexample: register("abc12") → 422 với message "6 ký tự" (sai message)
     *
     * Validates: Requirements 1.3
     */
    public function test_password_5_ky_tu_message_loi_phai_chua_8_ky_tu_khong_phai_6(): void
    {
        $response = $this->postRegisterJson('abc12');

        // HTTP 422 được trả về (đúng — 5 ký tự bị từ chối bởi min:6)
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);

        $errors = $response->json('errors.password');
        $this->assertNotEmpty($errors);

        $errorMessage = implode(' ', $errors);

        // Kỳ vọng: message chứa "8 ký tự"
        // Thực tế trên code chưa fix: message chứa "6 ký tự" — bug confirmed
        $this->assertStringContainsString(
            '8 ký tự',
            $errorMessage,
            'BUG CONFIRMED: Message lỗi là "' . $errorMessage . '" — phải chứa "8 ký tự" nhưng thực tế chứa "6 ký tự" (message sai do min:6)'
        );

        // Đồng thời xác nhận message KHÔNG chứa "6 ký tự" (sai)
        $this->assertStringNotContainsString(
            '6 ký tự',
            $errorMessage,
            'BUG CONFIRMED: Message lỗi chứa "6 ký tự" — đây là message sai, phải là "8 ký tự"'
        );
    }
}
