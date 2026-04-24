<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase as BaseTestCase;

/**
 * Preservation Property Tests — Password Min Length Validation
 *
 * Mục đích: Xác nhận hành vi HIỆN TẠI (baseline) trên code CHƯA sửa.
 * Các test này PHẢI PASS — pass = baseline được ghi nhận để bảo toàn sau khi fix.
 *
 * Property 2: Preservation — Mật khẩu hợp lệ (≥ 8 ký tự) vẫn hoạt động bình thường.
 * Với mọi input KHÔNG thuộc bug condition (length(password) >= 8),
 * hệ thống phải cho kết quả giống hệt trước và sau khi fix.
 *
 * **Validates: Requirements 3.1, 3.2, 3.3, 3.4, 3.5**
 */
class PasswordMinLengthPreservationTest extends BaseTestCase
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
     * Helper: tạo payload đăng ký với password và confirmation tùy chỉnh.
     *
     * Lưu ý: AuthController@register validate field 'name' nhưng đọc $request->ho_ten
     * để insert vào DB. Form HTML dùng name="ho_ten". Cần gửi cả hai để:
     * - 'name' vượt qua validation rule 'required|string|max:100'
     * - 'ho_ten' được đọc bởi $request->ho_ten trong User::create()
     */
    private function registerPayload(string $password, string $confirmation = null, string $email = null): array
    {
        $email = $email ?? 'test_' . uniqid() . '@example.com';
        $confirmation = $confirmation ?? $password;
        return [
            'name'                  => 'Test User',   // cho validation rule 'name' => 'required'
            'ho_ten'                => 'Test User',   // cho $request->ho_ten trong User::create()
            'email'                 => $email,
            'password'              => $password,
            'password_confirmation' => $confirmation,
            'so_dien_thoai'         => '0901234567',
        ];
    }

    /**
     * Helper: POST /register với Accept: application/json để nhận JSON response.
     */
    private function postRegisterJson(string $password, string $confirmation = null, string $email = null): \Illuminate\Testing\TestResponse
    {
        return $this->withHeaders(['Accept' => 'application/json'])
                    ->post(route('register.post'), $this->registerPayload($password, $confirmation, $email));
    }

    /**
     * Helper: POST /register bình thường (không JSON) để nhận redirect.
     */
    private function postRegister(string $password, string $confirmation = null, string $email = null): \Illuminate\Testing\TestResponse
    {
        return $this->post(route('register.post'), $this->registerPayload($password, $confirmation, $email));
    }

    // =========================================================================
    // TEST 1: Password 8 ký tự chính xác — phải được chấp nhận (HTTP 302 redirect)
    // Preservation: length(password) = 8 >= 8 → NOT isBugCondition → phải thành công
    // Validates: Requirements 3.1, 3.4
    // =========================================================================

    /**
     * Test 1: POST /register với password "abcd1234" (8 ký tự, valid confirmation)
     * phải trả về HTTP 302 redirect đến dashboard.
     *
     * EXPECTED OUTCOME (trên code chưa fix): PASS
     * - Server chấp nhận password 8 ký tự (min:6 cho phép)
     * - Redirect đến account.dashboard
     *
     * **Validates: Requirements 3.1, 3.4**
     */
    public function test_password_8_ky_tu_chinh_xac_duoc_chap_nhan_va_redirect(): void
    {
        $response = $this->postRegister('abcd1234');

        // Kỳ vọng: HTTP 302 redirect — password 8 ký tự phải được chấp nhận
        $response->assertStatus(302);
        $response->assertRedirect(route('account.dashboard'));
    }

    /**
     * Test 1b: Tài khoản được tạo trong DB khi đăng ký với password 8 ký tự.
     *
     * EXPECTED OUTCOME (trên code chưa fix): PASS
     *
     * **Validates: Requirements 3.4**
     */
    public function test_password_8_ky_tu_tao_tai_khoan_trong_db(): void
    {
        $email = 'preserve_8_' . uniqid() . '@example.com';
        $this->postRegister('abcd1234', 'abcd1234', $email);

        $this->assertDatabaseHas('users', ['email' => $email]);
    }

    // =========================================================================
    // TEST 2: Password 13 ký tự — phải được chấp nhận (HTTP 302 redirect)
    // Preservation: length(password) = 13 >= 8 → NOT isBugCondition → phải thành công
    // Validates: Requirements 3.2, 3.4
    // =========================================================================

    /**
     * Test 2: POST /register với password "abcdefgh12345" (13 ký tự)
     * phải trả về HTTP 302 redirect đến dashboard.
     *
     * EXPECTED OUTCOME (trên code chưa fix): PASS
     *
     * **Validates: Requirements 3.2, 3.4**
     */
    public function test_password_13_ky_tu_duoc_chap_nhan_va_redirect(): void
    {
        $response = $this->postRegister('abcdefgh12345');

        $response->assertStatus(302);
        $response->assertRedirect(route('account.dashboard'));
    }

    // =========================================================================
    // TEST 3: Password hợp lệ nhưng confirmation không khớp → lỗi "Xác nhận mật khẩu không khớp"
    // Preservation: confirmed mismatch vẫn bị từ chối đúng cách
    // Validates: Requirements 3.3
    // =========================================================================

    /**
     * Test 3: POST /register với password "abcd1234", password_confirmation "abcd5678"
     * phải trả về HTTP 422 với lỗi "Xác nhận mật khẩu không khớp".
     *
     * EXPECTED OUTCOME (trên code chưa fix): PASS
     *
     * **Validates: Requirements 3.3**
     */
    public function test_password_hop_le_nhung_confirmation_khong_khop_tra_ve_422(): void
    {
        $response = $this->postRegisterJson('abcd1234', 'abcd5678');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);

        $errors = $response->json('errors.password');
        $errorMessage = implode(' ', $errors);

        $this->assertStringContainsString(
            'Xác nhận mật khẩu không khớp',
            $errorMessage,
            'Phải có lỗi "Xác nhận mật khẩu không khớp" khi confirmation không khớp'
        );
    }

    // =========================================================================
    // TEST 4: Login với password 6 ký tự — vẫn hoạt động bình thường (login dùng min:6)
    // Preservation: form login không bị ảnh hưởng bởi fix
    // Validates: Requirements 3.5
    // =========================================================================

    /**
     * Test 4: POST /login với password 6 ký tự vẫn hoạt động bình thường.
     * Login dùng rule min:6 — không được thay đổi sau khi fix register.
     *
     * EXPECTED OUTCOME (trên code chưa fix): PASS
     * - Login không trả về lỗi validation về độ dài password
     * - (Có thể trả về lỗi "Email hoặc mật khẩu không đúng" nếu user không tồn tại — đó là đúng)
     *
     * **Validates: Requirements 3.5**
     */
    public function test_login_voi_password_6_ky_tu_khong_bi_anh_huong(): void
    {
        // Tạo user với password 6 ký tự để test login thực sự
        $email = 'login_test_' . uniqid() . '@example.com';
        DB::table('users')->insert([
            'name'           => 'Login Test User',
            'email'          => $email,
            'password'       => Hash::make('abc123'),
            'role_id'        => 2,
            'status'         => 'active',
            'loyalty_points' => 0,
            'total_spent'    => 0,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $response = $this->withHeaders(['Accept' => 'application/json'])
                         ->post(route('login.post'), [
                             'email'    => $email,
                             'password' => 'abc123',
                         ]);

        // Login với password 6 ký tự phải KHÔNG trả về lỗi validation về password
        // (min:6 cho phép 6 ký tự — không được thay đổi)
        $this->assertNotEquals(422, $response->status(),
            'Login với password 6 ký tự không được trả về lỗi validation — login dùng min:6'
        );

        // Nếu có lỗi validation, đảm bảo không phải lỗi về độ dài password
        if ($response->status() === 422) {
            $errors = $response->json('errors', []);
            $this->assertArrayNotHasKey('password', $errors,
                'Login không được có lỗi validation về password khi dùng password 6 ký tự'
            );
        }
    }

    /**
     * Test 4b: POST /login với password 6 ký tự không có lỗi validation về min length.
     * Kiểm tra trực tiếp rằng rule min:6 của login không bị thay đổi.
     *
     * EXPECTED OUTCOME (trên code chưa fix): PASS
     *
     * **Validates: Requirements 3.5**
     */
    public function test_login_password_6_ky_tu_khong_co_loi_validation_min_length(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
                         ->post(route('login.post'), [
                             'email'    => 'nonexistent@example.com',
                             'password' => 'abc123', // 6 ký tự
                         ]);

        // Nếu có lỗi validation (422), không được có lỗi về password min length
        if ($response->status() === 422) {
            $errors = $response->json('errors', []);
            if (isset($errors['password'])) {
                $passwordErrors = implode(' ', $errors['password']);
                $this->assertStringNotContainsString(
                    'tối thiểu',
                    $passwordErrors,
                    'Login không được có lỗi min length cho password 6 ký tự — rule login là min:6'
                );
            }
        }

        // Kết quả hợp lệ: 302 (redirect sau login thất bại) hoặc 422 (validation error khác)
        // Quan trọng: KHÔNG phải lỗi về password min length
        $this->assertContains($response->status(), [302, 422],
            'Login phải trả về 302 hoặc 422, không phải status khác'
        );
    }

    // =========================================================================
    // PROPERTY-BASED TESTS: Sinh nhiều test case với password length >= 8
    // Validates: Requirements 3.1, 3.2, 3.4
    // =========================================================================

    /**
     * Property Test: Với mọi password có length từ 8 đến 20 ký tự và valid confirmation,
     * đăng ký phải thành công (HTTP 302 redirect).
     *
     * Đây là property-based test — sinh nhiều test case để đảm bảo preservation.
     *
     * **Validates: Requirements 3.1, 3.2, 3.4**
     */
    public function test_property_moi_password_tu_8_den_20_ky_tu_deu_duoc_chap_nhan(): void
    {
        // Các độ dài đại diện: boundary (8), mid-range (10, 13, 16), max (20)
        $lengths = [8, 9, 10, 12, 13, 15, 16, 20];

        foreach ($lengths as $length) {
            $password = str_repeat('a', $length - 4) . '1234'; // đảm bảo đủ length
            $password = substr($password, 0, $length);
            $email    = 'prop_' . $length . '_' . uniqid() . '@example.com';

            $response = $this->postRegister($password, $password, $email);

            $this->assertEquals(302, $response->status(),
                "PRESERVATION FAILED: password với length={$length} ('{$password}') phải được chấp nhận (HTTP 302) nhưng nhận được HTTP {$response->status()}"
            );
        }
    }

    /**
     * Property Test: Với mọi password hợp lệ (≥ 8 ký tự) nhưng confirmation không khớp,
     * luôn trả về lỗi "Xác nhận mật khẩu không khớp".
     *
     * **Validates: Requirements 3.3**
     */
    public function test_property_password_hop_le_confirmation_khong_khop_luon_tra_ve_loi(): void
    {
        $testCases = [
            ['password' => 'abcd1234',      'confirmation' => 'abcd5678'],
            ['password' => 'abcdefgh12345', 'confirmation' => 'different1'],
            ['password' => 'mypassword1',   'confirmation' => 'mypassword2'],
        ];

        foreach ($testCases as $case) {
            $response = $this->postRegisterJson($case['password'], $case['confirmation']);

            $this->assertEquals(422, $response->status(),
                "Confirmation mismatch với password='{$case['password']}' phải trả về HTTP 422"
            );

            $errors = $response->json('errors.password', []);
            $errorMessage = implode(' ', $errors);

            $this->assertStringContainsString(
                'Xác nhận mật khẩu không khớp',
                $errorMessage,
                "Phải có lỗi 'Xác nhận mật khẩu không khớp' cho password='{$case['password']}'"
            );
        }
    }
}
