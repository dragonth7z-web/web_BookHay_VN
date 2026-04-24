<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase as BaseTestCase;

/**
 * PHPUnit Integration Tests — RegisterRequest (Task 1.1)
 *
 * Kiểm tra server-side validation cho POST /register sau khi AuthController
 * được cập nhật để dùng RegisterRequest (Task 2).
 *
 * Các test này sẽ pass sau khi Task 2 hoàn thành.
 *
 * _Requirements: 10.3, 10.4, 10.5, 10.6_
 */
class RegisterRequestTest extends BaseTestCase
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

    // ─── Payload helpers ──────────────────────────────────────────────────────

    /**
     * Tạo payload hợp lệ mặc định, cho phép override từng field.
     */
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

    /**
     * POST /register và trả về response (redirect-based).
     */
    private function postRegister(array $payload): \Illuminate\Testing\TestResponse
    {
        return $this->post(route('register.post'), $payload);
    }

    /**
     * POST /register với Accept: application/json để nhận JSON validation errors.
     */
    private function postRegisterJson(array $payload): \Illuminate\Testing\TestResponse
    {
        return $this->withHeaders(['Accept' => 'application/json'])
                    ->post(route('register.post'), $payload);
    }

    // =========================================================================
    // TEST 1: Dữ liệu hợp lệ → redirect dashboard, user được tạo trong DB
    // Validates: Requirements 10.3, 10.4
    // =========================================================================

    /**
     * Test 1a: POST /register với dữ liệu hợp lệ phải redirect đến account.dashboard.
     *
     * **Validates: Requirements 10.3, 10.4**
     */
    public function test_dang_ky_hop_le_redirect_den_dashboard(): void
    {
        $response = $this->postRegister($this->validPayload());

        $response->assertStatus(302);
        $response->assertRedirect(route('account.dashboard'));
    }

    /**
     * Test 1b: POST /register với dữ liệu hợp lệ phải tạo user trong database.
     *
     * **Validates: Requirements 10.3, 10.4**
     */
    public function test_dang_ky_hop_le_tao_user_trong_db(): void
    {
        $email = 'newuser_' . uniqid() . '@example.com';

        $this->postRegister($this->validPayload(['email' => $email]));

        $this->assertDatabaseHas('users', ['email' => $email]);
    }

    // =========================================================================
    // TEST 2: Email đã tồn tại → redirect back với error "Email này đã được sử dụng."
    // Validates: Requirements 10.4
    // =========================================================================

    /**
     * Test 2: POST /register với email đã tồn tại phải trả về lỗi validation.
     *
     * **Validates: Requirements 10.4**
     */
    public function test_email_da_ton_tai_tra_ve_loi_unique(): void
    {
        $email = 'existing@example.com';

        // Tạo user trước
        DB::table('users')->insert([
            'name'           => 'Existing User',
            'email'          => $email,
            'password'       => Hash::make('password123'),
            'role_id'        => 2,
            'status'         => 'active',
            'loyalty_points' => 0,
            'total_spent'    => 0,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $response = $this->postRegisterJson($this->validPayload(['email' => $email]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);

        $errors = $response->json('errors.email');
        $this->assertStringContainsString(
            'Email này đã được sử dụng.',
            implode(' ', $errors),
            'Phải có lỗi "Email này đã được sử dụng." khi email đã tồn tại'
        );
    }

    // =========================================================================
    // TEST 3: Email tạm (mailinator.com) → redirect back với error "Không chấp nhận email tạm thời."
    // Validates: Requirements 10.4
    // =========================================================================

    /**
     * Test 3: POST /register với email tạm mailinator.com phải bị từ chối.
     *
     * **Validates: Requirements 10.4**
     */
    public function test_email_tam_mailinator_bi_tu_choi(): void
    {
        $response = $this->postRegisterJson(
            $this->validPayload(['email' => 'user@mailinator.com'])
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);

        $errors = $response->json('errors.email');
        $this->assertStringContainsString(
            'Không chấp nhận email tạm thời.',
            implode(' ', $errors),
            'Phải có lỗi "Không chấp nhận email tạm thời." cho email mailinator.com'
        );
    }

    // =========================================================================
    // TEST 4: Số điện thoại không hợp lệ → redirect back với error
    // Validates: Requirements 10.6
    // =========================================================================

    /**
     * Test 4a: POST /register với số điện thoại sai prefix (bắt đầu bằng 01) phải bị từ chối.
     *
     * **Validates: Requirements 10.6**
     */
    public function test_so_dien_thoai_sai_prefix_bi_tu_choi(): void
    {
        $response = $this->postRegisterJson(
            $this->validPayload(['so_dien_thoai' => '0123456789'])
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['so_dien_thoai']);
    }

    /**
     * Test 4b: POST /register với số điện thoại sai độ dài (9 chữ số) phải bị từ chối.
     *
     * **Validates: Requirements 10.6**
     */
    public function test_so_dien_thoai_sai_do_dai_bi_tu_choi(): void
    {
        $response = $this->postRegisterJson(
            $this->validPayload(['so_dien_thoai' => '090123456']) // 9 chữ số
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['so_dien_thoai']);
    }

    /**
     * Test 4c: POST /register với số điện thoại hợp lệ (bắt đầu 09, đủ 10 số) phải được chấp nhận.
     *
     * **Validates: Requirements 10.6**
     */
    public function test_so_dien_thoai_hop_le_duoc_chap_nhan(): void
    {
        $email = 'phone_valid_' . uniqid() . '@example.com';

        $response = $this->postRegister(
            $this->validPayload([
                'email'         => $email,
                'so_dien_thoai' => '0901234567',
            ])
        );

        $response->assertStatus(302);
        $response->assertRedirect(route('account.dashboard'));
    }

    // =========================================================================
    // TEST 5: Fields có whitespace → user được lưu với trimmed values
    // Validates: Requirements 10.3
    // =========================================================================

    /**
     * Test 5: POST /register với fields có khoảng trắng đầu/cuối → user được lưu với trimmed values.
     *
     * **Validates: Requirements 10.3**
     */
    public function test_fields_co_whitespace_duoc_luu_voi_trimmed_values(): void
    {
        $email = 'trimtest_' . uniqid() . '@example.com';

        $this->postRegister($this->validPayload([
            'ho_ten'                => '  Nguyen Van A  ',
            'email'                 => '  ' . $email . '  ',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'so_dien_thoai'         => '  0901234567  ',
        ]));

        // Email phải được trim và lowercase trước khi lưu
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'name'  => 'Nguyen Van A',
        ]);
    }
}
