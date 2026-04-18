<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

/**
 * Tests SystemLog integrity — direct model calls via SystemLog::ghi().
 */
class SystemLogTest extends BaseAdminTestCase
{
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
            $t->string('position', 30)->default('Slider');
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
    // Tests
    // -------------------------------------------------------------------------

    public function test_create_action_logs_with_type_data_and_level_info(): void
    {
        SystemLog::ghi('data', 'create', 'Test Banner created', 'info', 'Banner', 1);

        $this->assertDatabaseHas('system_logs', [
            'type'        => 'data',
            'action'      => 'create',
            'description' => 'Test Banner created',
            'level'       => 'info',
            'object_type' => 'Banner',
            'object_id'   => 1,
        ]);
    }

    public function test_update_action_logs_with_level_info(): void
    {
        SystemLog::ghi('data', 'update', 'Banner updated', 'info', 'Banner', 2);

        $log = SystemLog::where('action', 'update')->where('object_id', 2)->first();

        $this->assertNotNull($log);
        $this->assertEquals('info', $log->level);
    }

    public function test_delete_action_logs_with_level_warning(): void
    {
        SystemLog::ghi('data', 'delete', 'Banner deleted', 'warning', 'Banner', 3);

        $log = SystemLog::where('action', 'delete')->where('object_id', 3)->first();

        $this->assertNotNull($log);
        $this->assertEquals('warning', $log->level);
    }

    public function test_object_type_never_null(): void
    {
        SystemLog::ghi('data', 'create', 'Banner created', 'info', 'Banner', 10);

        $log = SystemLog::where('object_id', 10)->first();

        $this->assertNotNull($log);
        $this->assertNotNull($log->object_type, 'object_type should never be null');
    }

    public function test_object_id_matches_entity_primary_key(): void
    {
        SystemLog::ghi('data', 'create', 'Banner created', 'info', 'Banner', 42);

        $log = SystemLog::where('object_type', 'Banner')->where('object_id', 42)->first();

        $this->assertNotNull($log);
        $this->assertEquals(42, $log->object_id);
    }

    public function test_full_crud_cycle_produces_three_logs(): void
    {
        SystemLog::ghi('data', 'create', 'Banner created', 'info', 'Banner', 99);
        SystemLog::ghi('data', 'update', 'Banner updated', 'info', 'Banner', 99);
        SystemLog::ghi('data', 'delete', 'Banner deleted', 'warning', 'Banner', 99);

        $count = SystemLog::where('object_type', 'Banner')
            ->where('object_id', 99)
            ->count();

        $this->assertEquals(3, $count, 'Full CRUD cycle should produce exactly 3 log entries');
    }

    public function test_description_is_non_empty_string(): void
    {
        SystemLog::ghi('data', 'create', 'Banner: Test Title', 'info', 'Banner', 5);

        $log = SystemLog::where('object_id', 5)->first();

        $this->assertNotNull($log);
        $this->assertIsString($log->description);
        $this->assertNotEmpty($log->description);
    }

    public function test_user_id_matches_acting_admin(): void
    {
        $user = User::find(1);
        $this->assertNotNull($user, 'Admin user must exist in DB');

        $this->actingAs($user);

        SystemLog::ghi('data', 'create', 'Banner created by admin', 'info', 'Banner', 7);

        $log = SystemLog::where('object_id', 7)->first();

        $this->assertNotNull($log);
        $this->assertEquals(1, $log->user_id, 'user_id should match the acting admin user id');
    }

    public function test_log_created_at_is_within_five_seconds(): void
    {
        SystemLog::ghi('data', 'create', 'Banner created', 'info', 'Banner', 8);

        $log = SystemLog::where('object_id', 8)->latest()->first();

        $this->assertNotNull($log);
        $this->assertLessThanOrEqual(
            5,
            now()->diffInSeconds($log->created_at),
            'Log created_at should be within 5 seconds of now()'
        );
    }
}
