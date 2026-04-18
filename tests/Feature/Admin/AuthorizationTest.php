<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use App\Models\Banner;
use Illuminate\Support\Facades\Schema;

/**
 * Tests AdminMiddleware protection for admin routes.
 */
class AuthorizationTest extends BaseAdminTestCase
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

    private function seedCustomerUser(): void
    {
        \Illuminate\Support\Facades\DB::table('users')->insert([
            'id'             => 2,
            'name'           => 'Customer Test',
            'email'          => 'customer@test.vn',
            'password'       => bcrypt('secret'),
            'role_id'        => 2,
            'status'         => 'active',
            'loyalty_points' => 0,
            'total_spent'    => 0,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Tests
    // -------------------------------------------------------------------------

    public function test_unauthenticated_user_redirected_to_login_on_admin_get(): void
    {
        $response = $this->get(route('admin.banner.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_customer_user_redirected_away_from_admin(): void
    {
        $this->seedCustomerUser();

        $response = $this->withSession([
            'user_id'   => 2,
            'user_role' => 2,
        ])->get(route('admin.banner.index'));

        $response->assertRedirect(route('home'));
    }

    public function test_admin_user_can_access_admin_routes(): void
    {
        $response = $this->withSession([
            'user_id'    => 1,
            'user_name'  => 'Admin Test',
            'user_role'  => 1,
            'user_email' => 'admin@test.vn',
        ])->get(route('admin.banner.index'));

        $response->assertStatus(200);
    }

    public function test_unauthenticated_post_does_not_create_record(): void
    {
        $countBefore = Banner::count();

        $this->post(route('admin.banner.store'), [
            'title'     => 'Unauthorized Banner',
            'position'  => 'home_main',
            'image_url' => 'https://example.com/img.jpg',
        ]);

        $this->assertEquals($countBefore, Banner::count(), 'No banner should be created without authentication');
    }

    public function test_customer_post_does_not_create_record(): void
    {
        $this->seedCustomerUser();

        $countBefore = Banner::count();

        $this->withSession([
            'user_id'   => 2,
            'user_role' => 2,
        ])->post(route('admin.banner.store'), [
            'title'     => 'Customer Banner',
            'position'  => 'home_main',
            'image_url' => 'https://example.com/img.jpg',
        ]);

        $this->assertEquals($countBefore, Banner::count(), 'No banner should be created by a customer user');
    }

    public function test_admin_middleware_redirects_to_login_not_error_page(): void
    {
        $response = $this->get(route('admin.banner.index'));

        $response->assertRedirect();
        $this->assertStringContainsString('login', $response->headers->get('Location'));
    }
}
