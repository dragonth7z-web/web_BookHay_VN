<?php

namespace Tests\Feature;

use Tests\TestCase as BaseTestCase;
use App\Models\Banner;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Kiểm tra CRUD Banner + System Log.
 *
 * Dùng URL ảnh thay vì file upload (tránh phụ thuộc GD extension).
 * Chạy trên SQLite in-memory với schema tối thiểu.
 */
class AdminBannerCrudTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        $this->dropSchema();
        parent::tearDown();
    }

    private function createSchema(): void
    {
        // roles
        Schema::create('roles', function ($t) {
            $t->tinyIncrements('id');
            $t->string('code', 20)->unique();
            $t->string('name', 50);
            $t->string('description', 255)->nullable();
        });

        // users
        Schema::create('users', function ($t) {
            $t->increments('id');
            $t->string('name', 100);
            $t->string('email', 100)->unique();
            $t->string('password', 255);
            $t->string('phone', 15)->nullable();
            $t->string('avatar', 255)->nullable();
            $t->date('date_of_birth')->nullable();
            $t->string('gender', 10)->default('other');
            $t->unsignedTinyInteger('role_id')->default(2);
            $t->string('status', 20)->default('active');
            $t->unsignedInteger('loyalty_points')->default(0);
            $t->decimal('total_spent', 15, 0)->default(0);
            $t->timestamps();
            $t->softDeletes('deleted_at');
        });

        // banner (khớp với migration đã fix)
        Schema::create('banner', function ($t) {
            $t->increments('id');
            $t->string('title', 255)->nullable();
            $t->string('badge_text', 255)->nullable();
            $t->string('image', 255)->nullable();
            $t->string('image_url', 500)->nullable();
            $t->string('url', 255)->nullable();
            $t->string('button_text', 255)->nullable();
            $t->string('position', 30)->default('Slider'); // string thay vì enum cho SQLite
            $t->integer('sort_order')->default(0);
            $t->boolean('is_visible')->default(true);
            $t->timestamps();
            $t->softDeletes('deleted_at');
        });

        // system_logs
        Schema::create('system_logs', function ($t) {
            $t->id();
            $t->string('type', 30)->index();
            $t->string('action', 50)->index();
            $t->string('level', 20)->default('info')->index();
            $t->text('description');
            $t->string('object_type', 100)->nullable();
            $t->unsignedBigInteger('object_id')->nullable();
            $t->json('old_data')->nullable();
            $t->json('new_data')->nullable();
            $t->unsignedBigInteger('user_id')->nullable();
            $t->string('user_name', 100)->nullable();
            $t->string('ip_address', 45)->nullable();
            $t->string('user_agent', 255)->nullable();
            $t->string('url', 500)->nullable();
            $t->timestamps();
        });

        // Seed roles + admin
        DB::table('roles')->insert([
            ['id' => 1, 'code' => 'ADMIN',    'name' => 'Admin'],
            ['id' => 2, 'code' => 'CUSTOMER', 'name' => 'Khách hàng'],
        ]);

        DB::table('users')->insert([
            'id'             => 1,
            'name'           => 'Admin Test',
            'email'          => 'admin@bookstore.vn',
            'password'       => bcrypt('admin123'),
            'role_id'        => 1,
            'status'         => 'active',
            'loyalty_points' => 0,
            'total_spent'    => 0,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    private function dropSchema(): void
    {
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('banner');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }

    private function loginAdmin(): void
    {
        $this->post(route('login.post'), [
            'email'    => 'admin@bookstore.vn',
            'password' => 'admin123',
        ]);
    }

    // =========================================================================
    // 1. THÊM (Create) – dùng URL ảnh thay vì file upload
    // =========================================================================

    public function test_1_them_banner_luu_vao_db_va_ghi_system_log(): void
    {
        $this->loginAdmin();

        $response = $this->post(route('admin.banner.store'), [
            'title'        => 'Banner Kiểm Tra CRUD',
            'position'     => 'home_main',
            'sort_order'   => 1,
            'is_visible'   => 1,
            'image_url'    => 'https://via.placeholder.com/1200x400',
        ]);

        // 1a. Redirect thành công
        $response->assertRedirect(route('admin.banner.index'));
        $response->assertSessionHas('success');

        // 1b. Dữ liệu lưu vào DB
        $banner = Banner::where('title', 'Banner Kiểm Tra CRUD')->first();
        $this->assertNotNull($banner, 'Banner phải được lưu vào DB');
        $this->assertEquals('home_main', $banner->position);
        $this->assertEquals(1, $banner->sort_order);
        $this->assertTrue((bool) $banner->is_visible);
        $this->assertEquals('https://via.placeholder.com/1200x400', $banner->image);

        // 1c. System log ghi đúng với object_type
        $log = SystemLog::where('action', 'create')
            ->where('description', 'like', '%Banner Kiểm Tra CRUD%')
            ->latest()
            ->first();

        $this->assertNotNull($log, 'System log phải được ghi sau khi thêm banner');
        $this->assertEquals('data', $log->type);
        $this->assertEquals('create', $log->action);
        $this->assertEquals('Banner', $log->object_type,
            'object_type phải là "Banner", không được NULL');
        $this->assertEquals($banner->id, $log->object_id);
    }

    // =========================================================================
    // 2. SỬA (Update)
    // =========================================================================

    public function test_2_sua_banner_cap_nhat_db_va_ghi_system_log(): void
    {
        $this->loginAdmin();

        // Tạo banner trước
        $banner = Banner::create([
            'title'      => 'Banner Cũ',
            'image'      => 'https://via.placeholder.com/old.jpg',
            'position'   => 'home_main',
            'sort_order' => 5,
            'is_visible' => true,
        ]);

        $response = $this->put(route('admin.banner.update', $banner->id), [
            'title'        => 'Banner Đã Cập Nhật',
            'position'     => 'home_mini',
            'sort_order'   => 2,
            'is_visible'   => 1,
            'image_url'    => 'https://via.placeholder.com/new.jpg',
        ]);

        $response->assertRedirect(route('admin.banner.index'));
        $response->assertSessionHas('success');

        // 2a. DB được cập nhật
        $banner->refresh();
        $this->assertEquals('Banner Đã Cập Nhật', $banner->title);
        $this->assertEquals('home_mini', $banner->position);
        $this->assertEquals(2, $banner->sort_order);

        // 2b. System log ghi đúng
        $log = SystemLog::where('action', 'update')
            ->where('description', 'like', '%Banner Đã Cập Nhật%')
            ->latest()
            ->first();

        $this->assertNotNull($log, 'System log phải được ghi sau khi sửa banner');
        $this->assertEquals('Banner', $log->object_type,
            'object_type phải là "Banner", không được NULL');
        $this->assertEquals($banner->id, $log->object_id);
    }

    // =========================================================================
    // 3. XÓA (Delete)
    // =========================================================================

    public function test_3_xoa_banner_soft_delete_va_ghi_system_log(): void
    {
        $this->loginAdmin();

        $banner = Banner::create([
            'title'      => 'Banner Sẽ Bị Xóa',
            'image'      => 'https://via.placeholder.com/delete.jpg',
            'position'   => 'home_gift',
            'sort_order' => 10,
            'is_visible' => true,
        ]);

        $bannerId = $banner->id;

        $response = $this->delete(route('admin.banner.destroy', $bannerId));

        $response->assertRedirect(route('admin.banner.index'));
        $response->assertSessionHas('success');

        // 3a. Bản ghi bị soft-delete
        $this->assertNull(Banner::find($bannerId), 'Banner phải bị xóa khỏi query thông thường');
        $this->assertSoftDeleted('banner', ['id' => $bannerId]);

        // 3b. System log ghi đúng
        $log = SystemLog::where('action', 'delete')
            ->where('description', 'like', '%Banner Sẽ Bị Xóa%')
            ->latest()
            ->first();

        $this->assertNotNull($log, 'System log phải được ghi sau khi xóa banner');
        $this->assertEquals('data', $log->type);
        $this->assertEquals('delete', $log->action);
        $this->assertEquals('warning', $log->level);
        $this->assertEquals('Banner', $log->object_type,
            'object_type phải là "Banner", không được NULL');
        $this->assertEquals($bannerId, $log->object_id);
    }

    // =========================================================================
    // 4. Kiểm tra doi_tuong_loai không bao giờ NULL sau CRUD
    // =========================================================================

    public function test_4_system_log_doi_tuong_loai_khong_null_sau_toan_bo_crud(): void
    {
        $this->loginAdmin();

        // Create
        $this->post(route('admin.banner.store'), [
            'title'        => 'Log Audit Banner',
            'position'     => 'home_main',
            'sort_order'   => 99,
            'is_visible'   => 1,
            'image_url'    => 'https://via.placeholder.com/audit.jpg',
        ]);

        $banner = Banner::where('title', 'Log Audit Banner')->first();

        // Update
        $this->put(route('admin.banner.update', $banner->id), [
            'title'        => 'Log Audit Banner Updated',
            'position'     => 'home_mini',
            'sort_order'   => 99,
            'is_visible'   => 1,
            'image_url'    => 'https://via.placeholder.com/audit2.jpg',
        ]);

        // Delete
        $this->delete(route('admin.banner.destroy', $banner->id));

        // Kiểm tra: tất cả log liên quan đến Banner đều có object_type
        $nullLogs = SystemLog::where('type', 'data')
            ->whereNull('object_type')
            ->where('description', 'like', '%banner%')
            ->count();

        $this->assertEquals(0, $nullLogs,
            'Không được có system_log nào liên quan đến banner mà object_type = NULL. ' .
            "Tìm thấy {$nullLogs} bản ghi bị NULL.");

        // Verify đủ 3 log (create, update, delete)
        $bannerLogs = SystemLog::where('type', 'data')
            ->where('object_type', 'Banner')
            ->whereIn('action', ['create', 'update', 'delete'])
            ->count();

        $this->assertGreaterThanOrEqual(3, $bannerLogs,
            'Phải có ít nhất 3 log (create, update, delete) cho Banner');
    }
}
