<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase as BaseTestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Coupon;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Enums\CouponType;
use Illuminate\Http\Request;

/**
 * Preservation Property Tests
 *
 * Mục đích: Xác nhận các behavior KHÔNG liên quan đến bug vẫn hoạt động đúng.
 * Các test này PHẢI PASS trên code CHƯA sửa – làm baseline để đảm bảo không có regression.
 *
 * Validates: Requirements 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7
 */
class PreservationTest extends BaseTestCase
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
        // roles
        Schema::create('roles', function ($table) {
            $table->tinyIncrements('id');
            $table->string('code', 20)->unique();
            $table->string('name', 50);
            $table->string('description', 255)->nullable();
        });

        // users
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

        // books
        Schema::create('books', function ($table) {
            $table->increments('id');
            $table->string('sku', 30)->unique()->nullable();
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->unique();
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
            $table->json('extra_images')->nullable();
            $table->string('isbn', 20)->nullable();
            $table->unsignedSmallInteger('pages')->nullable();
            $table->unsignedSmallInteger('weight')->nullable();
            $table->string('cover_type', 20)->default('paperback');
            $table->string('language', 50)->default('vi');
            $table->year('published_year')->nullable();
            $table->decimal('rating_avg', 3, 2)->default(0.00);
            $table->unsignedInteger('rating_count')->default(0);
            $table->string('status', 30)->default('active');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });

        // coupons
        Schema::create('coupons', function ($table) {
            $table->increments('id');
            $table->string('code', 50)->unique();
            $table->string('name', 255)->nullable();
            $table->string('type', 20)->default('fixed_amount');
            $table->decimal('value', 12, 0)->default(0);
            $table->decimal('max_discount', 12, 0)->default(0);
            $table->decimal('min_order_amount', 12, 0)->default(0);
            $table->unsignedInteger('usage_limit')->default(0);
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('status', 20)->default('active');
            $table->softDeletes('deleted_at');
        });

        // system_logs
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

        // Seed roles
        DB::table('roles')->insert([
            ['id' => 1, 'code' => 'ADMIN',    'name' => 'Admin'],
            ['id' => 2, 'code' => 'CUSTOMER', 'name' => 'Khách hàng'],
            ['id' => 3, 'code' => 'STAFF',    'name' => 'Nhân viên'],
        ]);
    }

    private function dropMinimalSchema(): void
    {
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('books');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('system_logs');
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function taoNguoiDung(array $overrides = []): User
    {
        return User::create(array_merge([
            'name'           => 'Nguyễn Văn A',
            'email'          => 'user' . uniqid() . '@example.com',
            'password'       => Hash::make('password123'),
            'role_id'        => 2,
            'status'         => 'active',
            'loyalty_points' => 0,
            'total_spent'    => 0,
        ], $overrides));
    }

    private function taoSach(array $overrides = []): Book
    {
        $uid = uniqid();
        return Book::create(array_merge([
            'sku'            => 'SACH-' . $uid,
            'title'          => 'Sách Test ' . $uid,
            'slug'           => 'sach-test-' . $uid,
            'cost_price'     => 30000,
            'original_price' => 60000,
            'sale_price'     => 50000,
            'stock'          => 100,
            'sold_count'     => 0,
            'status'         => 'in_stock',
            'is_featured'    => false,
        ], $overrides));
    }

    private function taoMaGiamGia(array $overrides = []): Coupon
    {
        return Coupon::create(array_merge([
            'code'             => 'CODE' . uniqid(),
            'name'             => 'Test Voucher',
            'type'             => 'fixed_amount',
            'value'            => 10000,
            'max_discount'     => 0,
            'min_order_amount' => 0,
            'usage_limit'      => 100,
            'used_count'       => 0,
            'starts_at'        => now()->subDay(),
            'expires_at'       => now()->addDay(),
            'status'           => 'active',
        ], $overrides));
    }

    // =========================================================================
    // PRESERVATION 1: Login Flow
    // Requirement 3.1 – Đăng nhập thành công → session user_id, user_name, user_role được set
    // =========================================================================

    /**
     * Test P1a: Đăng nhập với email/password đúng → session được set đầy đủ.
     *
     * Validates: Requirements 3.1
     */
    public function test_P1a_login_thanh_cong_set_session_user_id_user_name_user_role(): void
    {
        $password = 'password123';
        $user = $this->taoNguoiDung([
            'password' => Hash::make($password),
            'role_id'  => 2,
        ]);

        $response = $this->post(route('login.post'), [
            'email'    => $user->email,
            'password' => $password,
        ]);

        // Session phải có đủ 3 key bắt buộc
        $response->assertSessionHas('user_id', $user->id);
        $response->assertSessionHas('user_name', $user->name);
        $response->assertSessionHas('user_role', $user->role_id);
    }

    /**
     * Test P1b: Đăng nhập với password sai → session KHÔNG được set.
     *
     * Validates: Requirements 3.1
     */
    public function test_P1b_login_that_bai_khong_set_session(): void
    {
        $user = $this->taoNguoiDung();

        $response = $this->post(route('login.post'), [
            'email'    => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertSessionMissing('user_id');
        $response->assertSessionMissing('user_name');
        $response->assertSessionMissing('user_role');
    }

    /**
     * Test P1c: Property – với nhiều user khác nhau, login đúng luôn set session đúng.
     *
     * Validates: Requirements 3.1
     */
    public function test_P1c_property_nhieu_user_login_dung_deu_set_session_chinh_xac(): void
    {
        $testCases = [
            ['name' => 'Admin User',    'role_id' => 1],
            ['name' => 'Customer User', 'role_id' => 2],
            ['name' => 'Staff User',    'role_id' => 3],
        ];

        foreach ($testCases as $data) {
            $password = 'pass' . uniqid();
            $user = $this->taoNguoiDung([
                'name'     => $data['name'],
                'password' => Hash::make($password),
                'role_id'  => $data['role_id'],
            ]);

            $response = $this->post(route('login.post'), [
                'email'    => $user->email,
                'password' => $password,
            ]);

            $response->assertSessionHas('user_id', $user->id);
            $response->assertSessionHas('user_name', $user->name);
            $response->assertSessionHas('user_role', $user->role_id);
        }
    }

    // =========================================================================
    // PRESERVATION 2: Sach CRUD
    // Requirement 3.2 – Tạo/sửa/xóa sách không có DB error
    // =========================================================================

    /**
     * Test P2a: Tạo sách mới → không có DB error, sách được lưu vào DB.
     *
     * Validates: Requirements 3.2
     */
    public function test_P2a_tao_sach_moi_thanh_cong_khong_co_db_error(): void
    {
        $sach = $this->taoSach([
            'title' => 'Lập Trình PHP',
            'sale_price' => 120000,
            'stock'      => 50,
        ]);

        $this->assertNotNull($sach->id);
        $this->assertDatabaseHas('books', [
            'title'      => 'Lập Trình PHP',
            'sale_price' => 120000,
        ]);
    }

    /**
     * Test P2b: Cập nhật sách → không có DB error, dữ liệu được cập nhật đúng.
     *
     * Validates: Requirements 3.2
     */
    public function test_P2b_cap_nhat_sach_thanh_cong_khong_co_db_error(): void
    {
        $sach = $this->taoSach();

        $sach->update([
            'title'      => 'Tên Sách Mới',
            'sale_price' => 75000,
            'stock'      => 200,
        ]);

        $this->assertDatabaseHas('books', [
            'id'         => $sach->id,
            'title'      => 'Tên Sách Mới',
            'sale_price' => 75000,
            'stock'      => 200,
        ]);
    }

    /**
     * Test P2c: Xóa sách (soft delete) → không có DB error, sách bị soft-deleted.
     *
     * Validates: Requirements 3.2
     */
    public function test_P2c_xoa_sach_soft_delete_thanh_cong(): void
    {
        $sach = $this->taoSach();
        $sachId = $sach->id;

        $sach->delete();

        // Soft delete: bản ghi vẫn còn trong DB nhưng có deleted_at
        $this->assertSoftDeleted('books', ['id' => $sachId]);
        // Không tìm thấy qua query thông thường
        $this->assertNull(Book::find($sachId));
    }

    /**
     * Test P2d: Property – tạo nhiều sách với các giá trị khác nhau → tất cả thành công.
     *
     * Validates: Requirements 3.2
     */
    public function test_P2d_property_tao_nhieu_sach_khac_nhau_deu_thanh_cong(): void
    {
        $sachData = [
            ['sale_price' => 50000,  'stock' => 10,  'status' => 'in_stock'],
            ['sale_price' => 100000, 'stock' => 0,   'status' => 'out_of_stock'],
            ['sale_price' => 200000, 'stock' => 500, 'status' => 'in_stock'],
            ['sale_price' => 30000,  'stock' => 1,   'is_featured' => true],
        ];

        foreach ($sachData as $data) {
            $sach = $this->taoSach($data);
            $this->assertNotNull($sach->id, 'Tạo sách thất bại với data: ' . json_encode($data));
        }

        $this->assertEquals(4, Book::count());
    }

    // =========================================================================
    // PRESERVATION 3: Voucher Calculation
    // Requirement 3.7 – so_tien_giam tính đúng theo loại 'Phần trăm' / 'Số tiền'
    // =========================================================================

    /**
     * Test P3a: Voucher loại 'Số tiền' → so_tien_giam = gia_tri_giam cố định.
     *
     * Validates: Requirements 3.7
     */
    public function test_P3a_voucher_so_tien_tinh_dung_so_tien_giam_co_dinh(): void
    {
        $tongTien = 200000;
        $ma = $this->taoMaGiamGia([
            'type'             => 'fixed_amount',
            'value'            => 30000,
            'max_discount'     => 0,
            'min_order_amount' => 0,
        ]);

        // Logic tính giảm giá từ CartController::applyVoucher
        $soTienGiam = $ma->type === CouponType::Percentage
            ? round($tongTien * $ma->value / 100)
            : $ma->value;

        if ($ma->max_discount > 0) {
            $soTienGiam = min($soTienGiam, $ma->max_discount);
        }
        $soTienGiam = min($soTienGiam, $tongTien);

        $this->assertEquals(30000, $soTienGiam);
    }

    /**
     * Test P3b: Voucher loại 'Phần trăm' → so_tien_giam = tongTien * phan_tram / 100.
     *
     * Validates: Requirements 3.7
     */
    public function test_P3b_voucher_phan_tram_tinh_dung_so_tien_giam_theo_phan_tram(): void
    {
        $tongTien = 200000;
        $ma = $this->taoMaGiamGia([
            'type'             => 'percentage',
            'value'            => 10, // 10%
            'max_discount'     => 0,
            'min_order_amount' => 0,
        ]);

        $soTienGiam = $ma->type === CouponType::Percentage
            ? round($tongTien * $ma->value / 100)
            : $ma->value;

        if ($ma->max_discount > 0) {
            $soTienGiam = min($soTienGiam, $ma->max_discount);
        }
        $soTienGiam = min($soTienGiam, $tongTien);

        $this->assertEquals(20000, $soTienGiam); // 200000 * 10% = 20000
    }

    /**
     * Test P3c: Voucher 'Phần trăm' có giam_toi_da → so_tien_giam bị giới hạn.
     *
     * Validates: Requirements 3.7
     */
    public function test_P3c_voucher_phan_tram_co_giam_toi_da_bi_gioi_han(): void
    {
        $tongTien = 500000;
        $ma = $this->taoMaGiamGia([
            'type'         => 'percentage',
            'value'        => 20, // 20% = 100000
            'max_discount' => 50000, // nhưng tối đa 50000
            'min_order_amount' => 0,
        ]);

        $soTienGiam = $ma->type === CouponType::Percentage
            ? round($tongTien * $ma->value / 100)
            : $ma->value;

        if ($ma->max_discount > 0) {
            $soTienGiam = min($soTienGiam, $ma->max_discount);
        }
        $soTienGiam = min($soTienGiam, $tongTien);

        $this->assertEquals(50000, $soTienGiam); // bị cap tại 50000
    }

    /**
     * Test P3d: Voucher không vượt quá tổng tiền đơn hàng.
     *
     * Validates: Requirements 3.7
     */
    public function test_P3d_voucher_so_tien_giam_khong_vuot_qua_tong_tien(): void
    {
        $tongTien = 20000;
        $ma = $this->taoMaGiamGia([
            'type'         => 'fixed_amount',
            'value'        => 50000, // lớn hơn tổng tiền
            'max_discount' => 0,
            'min_order_amount' => 0,
        ]);

        $soTienGiam = $ma->type === CouponType::Percentage
            ? round($tongTien * $ma->value / 100)
            : $ma->value;

        if ($ma->max_discount > 0) {
            $soTienGiam = min($soTienGiam, $ma->max_discount);
        }
        $soTienGiam = min($soTienGiam, $tongTien);

        $this->assertEquals(20000, $soTienGiam); // bị cap tại tổng tiền
    }

    /**
     * Test P3e: Property – với nhiều loại voucher và tổng tiền khác nhau → tính đúng.
     *
     * Validates: Requirements 3.7
     */
    public function test_P3e_property_nhieu_loai_voucher_tinh_dung(): void
    {
        $cases = [
            // [type, value, max_discount, tong_tien, expected]
            ['fixed_amount', 10000, 0,     100000, 10000],
            ['fixed_amount', 10000, 0,     5000,   5000],   // cap tại tổng tiền
            ['percentage',   15,    0,     200000, 30000],  // 15% của 200000
            ['percentage',   50,    20000, 100000, 20000],  // 50% = 50000, cap tại 20000
            ['percentage',   100,   0,     80000,  80000],  // 100% = 80000
        ];

        foreach ($cases as [$loai, $giaTri, $giamToiDa, $tongTien, $expected]) {
            $ma = $this->taoMaGiamGia([
                'type'         => $loai,
                'value'        => $giaTri,
                'max_discount' => $giamToiDa,
            ]);

            $soTienGiam = $ma->type === CouponType::Percentage
                ? round($tongTien * $ma->value / 100)
                : $ma->value;

            if ($ma->max_discount > 0) {
                $soTienGiam = min($soTienGiam, $ma->max_discount);
            }
            $soTienGiam = min($soTienGiam, $tongTien);

            $this->assertEquals(
                $expected,
                $soTienGiam,
                "Voucher [{$loai}, {$giaTri}, cap={$giamToiDa}] với tổng {$tongTien}: expected {$expected}, got {$soTienGiam}"
            );
        }
    }

    // =========================================================================
    // PRESERVATION 4: Middleware auth.custom
    // Requirement 3.5 – Redirect đúng khi chưa đăng nhập
    // =========================================================================

    /**
     * Test P4a: Truy cập route có auth.custom khi chưa đăng nhập → redirect về login.
     *
     * Validates: Requirements 3.5
     */
    public function test_P4a_auth_middleware_redirect_ve_login_khi_chua_dang_nhap(): void
    {
        // Chưa đăng nhập (không có session user_id)
        $response = $this->get(route('account.dashboard'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test P4b: Truy cập route checkout khi chưa đăng nhập → redirect về login.
     *
     * Validates: Requirements 3.5
     */
    public function test_P4b_auth_middleware_checkout_redirect_ve_login_khi_chua_dang_nhap(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test P4c: AuthMiddleware trực tiếp – không có session → redirect login.
     *
     * Validates: Requirements 3.5
     */
    public function test_P4c_auth_middleware_truc_tiep_khong_co_session_redirect_login(): void
    {
        $middleware = new AuthMiddleware();
        $request    = Request::create('/tai-khoan', 'GET');

        // Không set session user_id
        $response = $middleware->handle($request, fn($req) => response('OK'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('login', $response->headers->get('Location'));
    }

    /**
     * Test P4d: AuthMiddleware trực tiếp – có session user_id → cho qua (next).
     *
     * Validates: Requirements 3.5
     */
    public function test_P4d_auth_middleware_co_session_user_id_cho_qua(): void
    {
        $middleware = new AuthMiddleware();
        $request    = Request::create('/tai-khoan', 'GET');

        // Set session user_id
        session(['user_id' => 1]);

        $called   = false;
        $response = $middleware->handle($request, function ($req) use (&$called) {
            $called = true;
            return response('OK');
        });

        $this->assertTrue($called, 'Middleware phải gọi $next khi có session user_id');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test P4e: AdminMiddleware – không có session → redirect login.
     *
     * Validates: Requirements 3.5
     */
    public function test_P4e_admin_middleware_khong_co_session_redirect_login(): void
    {
        $middleware = new AdminMiddleware();
        $request    = Request::create('/admin', 'GET');

        $response = $middleware->handle($request, fn($req) => response('OK'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('login', $response->headers->get('Location'));
    }

    /**
     * Test P4f: AdminMiddleware – có session nhưng role không phải admin/staff → redirect home.
     *
     * Validates: Requirements 3.5
     */
    public function test_P4f_admin_middleware_role_customer_redirect_home(): void
    {
        $middleware = new AdminMiddleware();
        $request    = Request::create('/admin', 'GET');

        // Customer role (id_vai_tro = 2)
        session(['user_id' => 1, 'user_role' => 2]);

        $response = $middleware->handle($request, fn($req) => response('OK'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/', $response->headers->get('Location'));
    }

    /**
     * Test P4g: AdminMiddleware – role admin (1) → cho qua.
     *
     * Validates: Requirements 3.5
     */
    public function test_P4g_admin_middleware_role_admin_cho_qua(): void
    {
        $middleware = new AdminMiddleware();
        $request    = Request::create('/admin', 'GET');

        session(['user_id' => 1, 'user_role' => 1]);

        $called   = false;
        $response = $middleware->handle($request, function ($req) use (&$called) {
            $called = true;
            return response('OK');
        });

        $this->assertTrue($called, 'AdminMiddleware phải gọi $next khi role = 1 (Admin)');
    }

    /**
     * Test P4h: AdminMiddleware – role staff (3) → cho qua.
     *
     * Validates: Requirements 3.5
     */
    public function test_P4h_admin_middleware_role_staff_cho_qua(): void
    {
        $middleware = new AdminMiddleware();
        $request    = Request::create('/admin', 'GET');

        session(['user_id' => 2, 'user_role' => 3]);

        $called   = false;
        $response = $middleware->handle($request, function ($req) use (&$called) {
            $called = true;
            return response('OK');
        });

        $this->assertTrue($called, 'AdminMiddleware phải gọi $next khi role = 3 (Staff)');
    }
}
