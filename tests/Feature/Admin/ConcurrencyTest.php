<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use App\Models\Banner;
use Illuminate\Support\Facades\Schema;

/**
 * Tests concurrent update scenarios (simulated sequentially).
 */
class ConcurrencyTest extends BaseAdminTestCase
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
    // Helpers
    // -------------------------------------------------------------------------

    private function adminSession(): array
    {
        return [
            'user_id'    => 1,
            'user_name'  => 'Admin Test',
            'user_role'  => 1,
            'user_email' => 'admin@test.vn',
        ];
    }

    private function createBanner(string $title = 'Concurrency Banner'): Banner
    {
        return Banner::create([
            'title'      => $title,
            'position'   => 'home_main',
            'is_visible' => true,
            'sort_order' => 1,
        ]);
    }

    // -------------------------------------------------------------------------
    // Tests
    // -------------------------------------------------------------------------

    public function test_two_sequential_updates_result_in_last_write_wins(): void
    {
        $banner = $this->createBanner('Original Title');
        $session = $this->adminSession();

        // Update 1: title = A
        $this->withSession($session)->put(route('admin.banner.update', $banner->id), [
            'title'      => 'Title A',
            'position'   => 'home_main',
            'sort_order' => 1,
            'is_visible' => 1,
        ]);

        // Update 2: title = B (last write wins)
        $this->withSession($session)->put(route('admin.banner.update', $banner->id), [
            'title'      => 'Title B',
            'position'   => 'home_main',
            'sort_order' => 1,
            'is_visible' => 1,
        ]);

        $banner->refresh();
        $this->assertEquals('Title B', $banner->title, 'Last write should win');
    }

    public function test_concurrent_updates_do_not_create_duplicate_records(): void
    {
        $banner = $this->createBanner('Duplicate Check Banner');
        $session = $this->adminSession();
        $countBefore = Banner::count();

        $this->withSession($session)->put(route('admin.banner.update', $banner->id), [
            'title'      => 'Update 1',
            'position'   => 'home_main',
            'sort_order' => 1,
            'is_visible' => 1,
        ]);

        $this->withSession($session)->put(route('admin.banner.update', $banner->id), [
            'title'      => 'Update 2',
            'position'   => 'home_main',
            'sort_order' => 1,
            'is_visible' => 1,
        ]);

        $this->assertEquals($countBefore, Banner::count(), 'Updates should not create duplicate records');
    }

    public function test_delete_after_update_leaves_record_deleted(): void
    {
        $banner = $this->createBanner('Update Then Delete');
        $session = $this->adminSession();

        $this->withSession($session)->put(route('admin.banner.update', $banner->id), [
            'title'      => 'Updated Before Delete',
            'position'   => 'home_main',
            'sort_order' => 1,
            'is_visible' => 1,
        ]);

        $this->withSession($session)->delete(route('admin.banner.destroy', $banner->id));

        $this->assertSoftDeleted('banner', ['id' => $banner->id]);
    }

    public function test_updated_at_is_valid_datetime_after_updates(): void
    {
        $banner = $this->createBanner('Datetime Check Banner');
        $session = $this->adminSession();

        $this->withSession($session)->put(route('admin.banner.update', $banner->id), [
            'title'      => 'Datetime Updated',
            'position'   => 'home_main',
            'sort_order' => 1,
            'is_visible' => 1,
        ]);

        $banner->refresh();

        $this->assertNotNull($banner->updated_at, 'updated_at should not be null after update');
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $banner->updated_at);
    }
}
