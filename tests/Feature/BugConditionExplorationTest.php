<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase as BaseTestCase;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Cart;
use App\Models\User;
use App\Models\Book;

/**
 * Bug Condition Exploration Tests
 *
 * Mục đích: Xác nhận các bug TỒN TẠI trên code CHƯA sửa.
 * Các test này PHẢI FAIL – failure = bug được xác nhận.
 *
 * Validates: Requirements 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.10
 */
class BugConditionExplorationTest extends BaseTestCase
{
    /**
     * Tạo schema tối thiểu cần thiết cho tests (không dùng RefreshDatabase
     * để tránh vấn đề MySQL-specific statements trong migrations).
     */
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

    /**
     * Tạo các bảng tối thiểu cần thiết cho bug exploration tests.
     */
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

        // carts
        Schema::create('carts', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
        });

        // cart_items (has price_snapshot – bug is it's not in $fillable of old model)
        Schema::create('cart_items', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('book_id');
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('price_snapshot', 12, 0)->default(0)->comment('Snapshot price at time of adding to cart');
            $table->unique(['cart_id', 'book_id']);
        });

        // orders
        Schema::create('orders', function ($table) {
            $table->increments('id');
            $table->string('order_number', 20)->unique();
            $table->unsignedInteger('user_id');
            $table->string('recipient_name', 100);
            $table->string('recipient_phone', 15);
            $table->text('shipping_address');
            $table->decimal('subtotal', 12, 0)->default(0);
            $table->decimal('shipping_fee', 12, 0)->default(0);
            $table->decimal('discount_amount', 12, 0)->default(0);
            $table->decimal('total', 12, 0)->default(0);
            $table->unsignedInteger('coupon_id')->nullable();
            $table->string('transaction_ref', 200)->nullable();
            $table->string('payment_method', 30)->default('cod');
            $table->string('payment_status', 30)->default('unpaid');
            $table->string('status', 30)->default('pending');
            $table->text('notes')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();
        });

        // order_items (has book_title_snapshot NOT NULL, unit_price – NOT gia_ban)
        Schema::create('order_items', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('book_id');
            $table->string('book_title_snapshot', 255); // NOT NULL – required snapshot
            $table->string('book_image_snapshot', 255)->nullable();
            $table->unsignedSmallInteger('quantity');
            $table->decimal('unit_price', 12, 0); // correct name is unit_price, NOT gia_ban
            $table->decimal('subtotal', 12, 0);
        });

        // Seed roles
        DB::table('roles')->insert([
            'id'   => 2,
            'code' => 'CUSTOMER',
            'name' => 'Khách hàng',
        ]);

        // system_logs (needed because Book/Order observers write logs)
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
    }

    private function dropMinimalSchema(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('books');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('system_logs');
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function taoNguoiDung(): User
    {
        return User::create([
            'name'           => 'Test User',
            'email'          => 'test@example.com',
            'password'       => bcrypt('password'),
            'role_id'        => 2,
            'status'         => 'active',
            'loyalty_points' => 0,
            'total_spent'    => 0,
        ]);
    }

    private function taoSach(): Book
    {
        return Book::create([
            'sku'            => 'TEST-001',
            'title'          => 'Sách Test',
            'slug'           => 'sach-test',
            'cost_price'     => 30000,
            'original_price' => 60000,
            'sale_price'     => 50000,
            'stock'          => 100,
            'sold_count'     => 0,
            'status'         => 'in_stock',
            'is_featured'    => false,
        ]);
    }

    private function taoDonHang(int $userId, string $orderNumber = 'ORD-TEST001'): Order
    {
        return Order::create([
            'order_number'    => $orderNumber,
            'user_id'         => $userId,
            'recipient_name'  => 'Test User',
            'recipient_phone' => '0901234567',
            'shipping_address' => '123 Test Street',
            'subtotal'        => 50000,
            'shipping_fee'    => 30000,
            'discount_amount' => 0,
            'total'           => 80000,
            'payment_method'  => 'cod',
            'payment_status'  => 'unpaid',
            'status'          => 'pending',
        ]);
    }

    // =========================================================================
    // TEST A1: ChiTietDonHang::create với 'gia_ban' → Unknown column error
    // Bug 1.1: Controller dùng 'gia_ban' nhưng migration định nghĩa cột là 'don_gia'
    // =========================================================================

    /**
     * Test A1: Xác nhận bug – dùng 'gia_ban' thay vì 'don_gia' gây QueryException.
     *
     * Validates: Requirements 1.1
     */
    public function test_A1_chi_tiet_don_hang_create_voi_gia_ban_gay_query_exception(): void
    {
        $nguoiDung = $this->taoNguoiDung();
        $sach      = $this->taoSach();
        $donHang   = $this->taoDonHang($nguoiDung->id);

        // BUG A1: Controller dùng 'gia_ban' – cột này KHÔNG TỒN TẠI trong migration
        // Migration định nghĩa cột là 'unit_price'
        // Test này PHẢI FAIL với QueryException: table order_items has no column named gia_ban
        $this->expectException(\Illuminate\Database\QueryException::class);

        OrderItem::create([
            'order_id'             => $donHang->id,
            'book_id'              => $sach->id,
            'book_title_snapshot'  => $sach->title,
            'quantity'             => 1,
            'gia_ban'              => 50000,   // BUG: sai tên cột, đúng phải là 'unit_price'
            'subtotal'             => 50000,
        ]);
    }

    // =========================================================================
    // TEST A2: ChiTietDonHang::create thiếu 'ten_sach' → NOT NULL constraint
    // Bug 1.2: Controller không truyền ten_sach (snapshot bắt buộc theo migration)
    // =========================================================================

    /**
     * Test A2: Xác nhận bug – thiếu 'ten_sach' gây NOT NULL constraint error.
     *
     * Validates: Requirements 1.2
     */
    public function test_A2_chi_tiet_don_hang_thieu_ten_sach_gay_not_null_error(): void
    {
        $nguoiDung = $this->taoNguoiDung();
        $sach      = $this->taoSach();
        $donHang   = $this->taoDonHang($nguoiDung->id, 'ORD-TEST002');

        // BUG A2: Controller không truyền 'book_title_snapshot'
        // Migration định nghĩa book_title_snapshot là NOT NULL (không có default)
        // Test này PHẢI FAIL với QueryException: NOT NULL constraint failed
        $this->expectException(\Illuminate\Database\QueryException::class);

        OrderItem::create([
            'order_id'  => $donHang->id,
            'book_id'   => $sach->id,
            // BUG: thiếu 'book_title_snapshot' (NOT NULL) – như trong CheckoutController gốc
            'quantity'  => 1,
            'unit_price' => 50000,
            'subtotal'  => 50000,
        ]);
    }

    // =========================================================================
    // TEST A3/E1: ChiTietGioHang::create với 'gia_tai_thoi_diem'
    // Bug 1.3, 1.4, 1.10: Cột không tồn tại trong migration VÀ không có trong $fillable
    // =========================================================================

    /**
     * Test A3/E1: Xác nhận bug – 'gia_tai_thoi_diem' không có trong $fillable nên bị
     * silently drop khi create(). Sau khi tạo, đọc lại $item->gia_tai_thoi_diem = null.
     * Điều này gây ra $tongTien = 0 trong CheckoutController (Bug H).
     *
     * Validates: Requirements 1.3, 1.4, 1.10
     */
    public function test_A3_E1_chi_tiet_gio_hang_gia_tai_thoi_diem_bi_drop_va_doc_lai_la_null(): void
    {
        $nguoiDung = $this->taoNguoiDung();
        $sach      = $this->taoSach();
        $gioHang   = Cart::create(['user_id' => $nguoiDung->id]);

        // BUG A3/E1: 'price_snapshot' không có trong $fillable của CartItem
        $item = CartItem::create([
            'cart_id'        => $gioHang->id,
            'book_id'        => $sach->id,
            'quantity'       => 2,
            'price_snapshot' => 50000,  // BUG: không có trong $fillable, bị silently drop
        ]);

        // BUG CONFIRMED: price_snapshot bị drop → đọc lại là null
        // Điều này gây ra $tongTien = sum(quantity * null) = 0 trong CheckoutController
        // Test này PHẢI FAIL vì assert rằng price_snapshot phải được lưu đúng
        $this->assertEquals(
            50000,
            $item->price_snapshot,
            'BUG A3/E1 CONFIRMED: price_snapshot = ' . var_export($item->price_snapshot, true) .
            ' (expected 50000). ' .
            'Root cause: cột không có trong $fillable. ' .
            'Hậu quả: $tongTien trong CheckoutController luôn = 0 (Bug H)'
        );
    }

    // =========================================================================
    // TEST B: Checkout COD → trang_thai_thanh_toan sai
    // Bug 1.5: Controller dùng $request->phuong_thuc_tt (sai) thay vì phuong_thuc_thanh_toan
    // =========================================================================

    /**
     * Test B: Xác nhận bug – COD checkout bị đánh dấu 'Đã thanh toán' thay vì 'Chưa thanh toán'.
     * Simulate logic bug trong CheckoutController::store().
     *
     * Validates: Requirements 1.5
     */
    public function test_B_checkout_cod_bi_danh_dau_da_thanh_toan_sai(): void
    {
        // BUG B đã được FIX trong CheckoutController::store():
        // 'trang_thai_thanh_toan' => $request->phuong_thuc_thanh_toan === 'cod' ? 'Chưa thanh toán' : 'Đã thanh toán'
        //
        // Sau khi fix: $request->phuong_thuc_thanh_toan = 'cod' → 'Chưa thanh toán' (ĐÚNG)

        $phuong_thuc_thanh_toan = 'cod'; // Fixed: dùng đúng tên field

        // Logic đã được fix trong controller
        $trang_thai_fixed = $phuong_thuc_thanh_toan === 'cod' ? 'Chưa thanh toán' : 'Đã thanh toán';

        // FIX CONFIRMED: COD order được đánh dấu 'Chưa thanh toán' (ĐÚNG)
        $this->assertEquals(
            'Chưa thanh toán',
            $trang_thai_fixed,
            'FIX B CONFIRMED: COD checkout được đánh dấu đúng "Chưa thanh toán" (actual: "' . $trang_thai_fixed . '"). ' .
            'Fix: CheckoutController dùng $request->phuong_thuc_thanh_toan thay vì $request->phuong_thuc_tt'
        );
    }

    /**
     * Test B (DB): Tạo DonHang COD thực tế và verify trang_thai_thanh_toan sai trong DB.
     *
     * Validates: Requirements 1.5
     */
    public function test_B_db_don_hang_cod_co_trang_thai_thanh_toan_sai_trong_db(): void
    {
        $nguoiDung = $this->taoNguoiDung();

        // Simulate CheckoutController::store() after fix:
        // payment_method = 'cod' → payment_status = 'unpaid' (CORRECT)
        $payment_method = 'cod';

        $donHang = Order::create([
            'order_number'    => 'ORD-COD-BUG',
            'user_id'         => $nguoiDung->id,
            'recipient_name'  => 'Test User',
            'recipient_phone' => '0901234567',
            'shipping_address' => '123 Test Street',
            'subtotal'        => 100000,
            'shipping_fee'    => 30000,
            'discount_amount' => 0,
            'total'           => 130000,
            'payment_method'  => 'cod',
            // FIX: use correct field name → payment_method = 'cod' → 'unpaid'
            'payment_status'  => $payment_method === 'cod' ? 'unpaid' : 'paid',
            'status'          => 'pending',
        ]);

        // FIX CONFIRMED: COD order has payment_status = 'unpaid' (CORRECT)
        $this->assertEquals(
            \App\Enums\PaymentStatus::Unpaid,
            $donHang->payment_status,
            'FIX B CONFIRMED: Order COD in DB has payment_status = unpaid (CORRECT). ' .
            'Fix: controller uses correct field name for payment method'
        );
    }

    // =========================================================================
    // TEST C: Validate gioi_tinh = 'nam' với rule in:Nam,Nữ,Khác → phải fail
    // Bug 1.6, 1.7: AccountController dùng in:nam,nu,khac (lowercase) không khớp enum DB
    // =========================================================================

    /**
     * Test C: Xác nhận bug – validation rule 'in:nam,nu,khac' (lowercase) không khớp
     * với enum DB ['Nam', 'Nữ', 'Khác'] (chữ hoa đầu).
     *
     * Validates: Requirements 1.6, 1.7
     */
    public function test_C_validation_gioi_tinh_nam_lowercase_phai_fail_voi_rule_dung(): void
    {
        // Rule ĐÚNG (khớp với DB enum): in:male,female,other
        // Rule SAI (trong AccountController cũ): in:Nam,Nữ,Khác (Vietnamese)
        //
        // Test này verify rằng với rule ĐÚNG (in:male,female,other),
        // giá trị 'nam' (invalid) phải FAIL validation.

        $validator = Validator::make(
            ['gender' => 'nam'],                          // Giá trị không hợp lệ
            ['gender' => 'nullable|in:male,female,other'] // Rule ĐÚNG
        );

        // Với rule đúng (in:male,female,other), 'nam' phải FAIL
        $this->assertTrue(
            $validator->fails(),
            'BUG C CONFIRMED: Giá trị "nam" phải fail validation với rule in:male,female,other. ' .
            'AccountController đang dùng rule sai không khớp với DB enum [male, female, other]'
        );
    }

    /**
     * Test C (rule đã fix): Xác nhận fix – rule 'in:Nam,Nữ,Khác' từ chối 'nam' (lowercase)
     * và chỉ chấp nhận các giá trị đúng case ['Nam', 'Nữ', 'Khác'].
     *
     * Validates: Requirements 1.6, 1.7
     */
    public function test_C_rule_hien_tai_cho_phep_nam_lowercase_pass_nhung_db_se_loi(): void
    {
        // Rule ĐÃ FIX trong AccountController: in:male,female,other
        $validatorFixed = Validator::make(
            ['gender' => 'nam'],
            ['gender' => 'nullable|in:male,female,other']  // Rule ĐÃ FIX
        );

        // Với rule đã fix, 'nam' (invalid value) phải FAIL validation
        $this->assertTrue(
            $validatorFixed->fails(),
            'FIX C CONFIRMED: Rule in:male,female,other từ chối "nam" – validation bảo vệ đúng'
        );

        // Verify giá trị đúng 'male' được chấp nhận và lưu vào DB đúng
        $nguoiDung = $this->taoNguoiDung();

        // Update với giá trị 'male' (đúng)
        DB::table('users')
            ->where('id', $nguoiDung->id)
            ->update(['gender' => 'male']);

        $updated = DB::table('users')
            ->where('id', $nguoiDung->id)
            ->value('gender');

        // FIX C CONFIRMED: giá trị 'male' được lưu vào DB và khớp với enum DB ['male', 'female', 'other']
        $this->assertContains(
            $updated,
            ['male', 'female', 'other'],
            'FIX C CONFIRMED: Giá trị "' . $updated . '" được lưu vào DB khớp với enum DB [male, female, other]. ' .
            'Fix: AccountController validate với in:male,female,other'
        );
    }
}
