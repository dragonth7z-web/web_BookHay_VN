<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use App\Models\Banner;
use App\Models\Coupon;
use Illuminate\Support\Facades\Schema;

/**
 * Tests input validation for admin forms.
 */
class ValidationTest extends BaseAdminTestCase
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

        Schema::create('categories', function ($t) {
            $t->increments('id');
            $t->unsignedInteger('parent_id')->nullable();
            $t->string('name', 100);
            $t->string('slug', 150)->unique();
            $t->text('description')->nullable();
            $t->string('image', 255)->nullable();
            $t->smallInteger('sort_order')->default(0);
            $t->boolean('is_visible')->default(true);
            $t->softDeletes();
        });

        Schema::create('publishers', function ($t) {
            $t->increments('id');
            $t->string('name', 200);
            $t->string('slug', 200)->unique();
            $t->string('logo', 255)->nullable();
            $t->text('description')->nullable();
            $t->string('website', 255)->nullable();
            $t->boolean('is_partner')->default(false);
            $t->timestamps();
        });

        Schema::create('books', function ($t) {
            $t->increments('id');
            $t->string('sku', 30)->unique();
            $t->string('title', 255);
            $t->string('slug', 255)->unique();
            $t->unsignedInteger('category_id')->nullable();
            $t->unsignedInteger('publisher_id')->nullable();
            $t->decimal('cost_price', 12, 0)->default(0);
            $t->decimal('original_price', 12, 0)->default(0);
            $t->decimal('sale_price', 12, 0)->default(0);
            $t->integer('stock')->default(0);
            $t->integer('sold_count')->default(0);
            $t->text('description')->nullable();
            $t->string('short_description', 500)->nullable();
            $t->string('cover_image', 255)->nullable();
            $t->json('extra_images')->nullable();
            $t->string('isbn', 20)->nullable();
            $t->unsignedSmallInteger('pages')->nullable();
            $t->unsignedSmallInteger('weight')->nullable();
            $t->string('cover_type', 20)->default('paperback');
            $t->string('language', 50)->default('English');
            $t->year('published_year')->nullable();
            $t->decimal('rating_avg', 3, 2)->default(0.00);
            $t->unsignedInteger('rating_count')->default(0);
            $t->string('status', 30)->default('in_stock');
            $t->boolean('is_featured')->default(false);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('book_author', function ($t) {
            $t->unsignedInteger('book_id');
            $t->unsignedInteger('author_id');
            $t->primary(['book_id', 'author_id']);
        });

        Schema::create('coupons', function ($t) {
            $t->increments('id');
            $t->string('code', 50)->unique();
            $t->string('name', 200)->nullable();
            $t->string('type', 30)->default('percentage');
            $t->decimal('value', 12, 0)->default(0);
            $t->decimal('max_discount', 12, 0)->default(0);
            $t->decimal('min_order_amount', 12, 0)->default(0);
            $t->integer('usage_limit')->default(0);
            $t->integer('used_count')->default(0);
            $t->timestamp('starts_at')->nullable();
            $t->timestamp('expires_at')->nullable();
            $t->string('status', 20)->default('active');
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
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('book_author');
        Schema::dropIfExists('books');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('categories');
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

    // -------------------------------------------------------------------------
    // Tests
    // -------------------------------------------------------------------------

    public function test_banner_create_without_position_returns_validation_error(): void
    {
        $response = $this->withSession($this->adminSession())
            ->post(route('admin.banner.store'), [
                'title'     => 'No Position Banner',
                'image_url' => 'https://example.com/img.jpg',
            ]);

        $response->assertSessionHasErrors('position');
    }

    public function test_banner_create_without_image_or_image_url_returns_validation_error(): void
    {
        $response = $this->withSession($this->adminSession())
            ->post(route('admin.banner.store'), [
                'title'    => 'No Image Banner',
                'position' => 'home_main',
            ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_banner_create_with_invalid_position_returns_validation_error(): void
    {
        $response = $this->withSession($this->adminSession())
            ->post(route('admin.banner.store'), [
                'title'     => 'Invalid Position Banner',
                'position'  => 'invalid_pos',
                'image_url' => 'https://example.com/img.jpg',
            ]);

        $response->assertSessionHasErrors('position');
    }

    public function test_book_create_without_title_returns_validation_error(): void
    {
        $response = $this->withSession($this->adminSession())
            ->post(route('admin.books.store'), [
                'sku'    => 'SKU-NOTITLE',
                'slug'   => 'no-title-book',
                'status' => 'in_stock',
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_book_create_without_sku_returns_validation_error(): void
    {
        $response = $this->withSession($this->adminSession())
            ->post(route('admin.books.store'), [
                'title'  => 'No SKU Book',
                'slug'   => 'no-sku-book',
                'status' => 'in_stock',
            ]);

        $response->assertSessionHasErrors('sku');
    }

    public function test_coupon_create_with_duplicate_code_rejected(): void
    {
        Coupon::create([
            'code'   => 'DUPVAL01',
            'name'   => 'Original Coupon',
            'type'   => 'percentage',
            'value'  => 10,
            'status' => 'active',
        ]);

        // Attempt to create duplicate — DB unique constraint or controller validation should reject it
        $this->withSession($this->adminSession())
            ->post(route('admin.coupons.store'), [
                'code'   => 'DUPVAL01',
                'name'   => 'Duplicate Coupon',
                'type'   => 'percentage',
                'value'  => 20,
                'status' => 'active',
            ]);

        $count = Coupon::withTrashed()->where('code', 'DUPVAL01')->count();
        $this->assertEquals(1, $count, 'Only one coupon with duplicate code should exist');
    }

    public function test_required_field_empty_does_not_create_record(): void
    {
        $countBefore = Banner::count();

        $this->withSession($this->adminSession())
            ->post(route('admin.banner.store'), [
                'title'     => 'Missing Position Banner',
                'image_url' => 'https://example.com/img.jpg',
                // position intentionally omitted
            ]);

        $this->assertEquals($countBefore, Banner::count(), 'No new banner should be created when required field is missing');
    }
}
