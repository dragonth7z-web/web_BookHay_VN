<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use Tests\Support\Traits\AssertsCrud;
use Tests\Support\Traits\AssertsSystemLog;
use App\Models\Banner;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * CRUD tests for Banner entity.
 *
 * Uses session-based admin auth (AdminMiddleware checks session('user_id') and session('user_role')).
 * Uses image_url instead of file upload to avoid GD/Imagick dependency.
 * Schema uses string instead of enum for SQLite :memory: compatibility.
 */
class BannerCrudTest extends BaseAdminTestCase
{
    use AssertsCrud;
    use AssertsSystemLog;

    // -------------------------------------------------------------------------
    // Schema
    // -------------------------------------------------------------------------

    protected function createSchema(): void
    {
        Schema::create('roles', function ($t) {
            $t->tinyIncrements('id');
            $t->string('code', 20)->unique();
            $t->string('name', 50);
            $t->string('description', 255)->nullable();
        });

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
            $t->softDeletes();
        });

        Schema::create('banner', function ($t) {
            $t->increments('id');
            $t->string('title', 255)->nullable();
            $t->string('badge_text', 255)->nullable();
            $t->string('image', 255)->nullable();
            $t->string('image_url', 500)->nullable();
            $t->string('url', 255)->nullable();
            $t->string('button_text', 255)->nullable();
            $t->string('position', 30)->default('Slider'); // string instead of enum for SQLite
            $t->integer('sort_order')->default(0);
            $t->boolean('is_visible')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

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
    }

    protected function dropSchema(): void
    {
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('banner');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }

    // -------------------------------------------------------------------------
    // Helper
    // -------------------------------------------------------------------------

    /** Session data required by AdminMiddleware */
    private function adminSession(): array
    {
        return [
            'user_id'    => 1,
            'user_name'  => 'Admin Test',
            'user_role'  => 1,
            'user_email' => 'admin@test.vn',
        ];
    }

    /** Minimal valid banner payload using image_url (no file upload needed) */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title'      => 'Test Banner',
            'position'   => 'home_main',
            'sort_order' => 1,
            'is_visible' => 1,
            'image_url'  => 'https://example.com/banner.jpg',
        ], $overrides);
    }

    // -------------------------------------------------------------------------
    // Tests
    // -------------------------------------------------------------------------

    public function test_create_banner_redirects_and_persists_record(): void
    {
        $payload = $this->validPayload(['title' => 'New Banner']);

        $response = $this->withSession($this->adminSession())
            ->post(route('admin.banner.store'), $payload);

        $response->assertRedirect(route('admin.banner.index'));

        $this->assertDatabaseHas('banner', [
            'title'    => 'New Banner',
            'position' => 'home_main',
        ]);

        $banner = Banner::where('title', 'New Banner')->first();
        $this->assertNotNull($banner);

        $this->assertSystemLog(
            action: 'create',
            objectType: 'Banner',
            objectId: $banner->id,
            level: 'info',
            type: 'data'
        );
    }

    public function test_update_banner_persists_changes_without_creating_new_record(): void
    {
        $banner = Banner::create([
            'title'      => 'Original Title',
            'image'      => 'https://example.com/old.jpg',
            'position'   => 'home_main',
            'sort_order' => 5,
            'is_visible' => true,
        ]);

        $this->assertCountUnchanged('banner', function () use ($banner) {
            $this->withSession($this->adminSession())
                ->put(route('admin.banner.update', $banner->id), [
                    'title'      => 'Updated Title',
                    'position'   => 'home_mini',
                    'sort_order' => 10,
                    'is_visible' => 1,
                ]);
        });

        $this->assertDatabaseHas('banner', [
            'id'       => $banner->id,
            'title'    => 'Updated Title',
            'position' => 'home_mini',
        ]);

        $this->assertSystemLog(
            action: 'update',
            objectType: 'Banner',
            objectId: $banner->id,
            level: 'info',
            type: 'data'
        );
    }

    public function test_delete_banner_soft_deletes_and_logs_warning(): void
    {
        $banner = Banner::create([
            'title'      => 'Banner To Delete',
            'image'      => 'https://example.com/delete.jpg',
            'position'   => 'home_gift',
            'sort_order' => 3,
            'is_visible' => true,
        ]);

        $id = $banner->id;

        $this->withSession($this->adminSession())
            ->delete(route('admin.banner.destroy', $id));

        $this->assertSoftDeletedCorrectly('banner', $id);

        $this->assertSystemLog(
            action: 'delete',
            objectType: 'Banner',
            objectId: $id,
            level: 'warning',
            type: 'data'
        );
    }

    public function test_system_log_object_type_never_null_after_full_crud_cycle(): void
    {
        $session = $this->adminSession();

        // Create
        $this->withSession($session)
            ->post(route('admin.banner.store'), $this->validPayload(['title' => 'Cycle Banner']));

        $banner = Banner::where('title', 'Cycle Banner')->first();
        $this->assertNotNull($banner);

        // Update
        $this->withSession($session)
            ->put(route('admin.banner.update', $banner->id), [
                'title'      => 'Cycle Banner Updated',
                'position'   => 'Slider',
                'sort_order' => 2,
                'is_visible' => 1,
            ]);

        // Delete
        $this->withSession($session)
            ->delete(route('admin.banner.destroy', $banner->id));

        // All SystemLog records for this Banner must have non-null object_type
        $nullCount = SystemLog::where('object_id', $banner->id)
            ->whereNull('object_type')
            ->count();

        $this->assertEquals(0, $nullCount, 'No SystemLog for Banner should have null object_type');

        // Verify all 3 actions were logged
        $logCount = SystemLog::where('object_type', 'Banner')
            ->where('object_id', $banner->id)
            ->whereIn('action', ['create', 'update', 'delete'])
            ->count();

        $this->assertEquals(3, $logCount, 'Expected 3 SystemLog entries (create, update, delete) for Banner');
    }
}
