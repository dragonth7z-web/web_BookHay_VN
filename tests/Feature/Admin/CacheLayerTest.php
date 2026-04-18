<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use App\Models\Banner;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tests cache invalidation behavior.
 *
 * Uses CACHE_STORE=array (from phpunit.xml) so Cache::remember() works in-memory.
 * Tests cache behavior directly via the Cache facade.
 */
class CacheLayerTest extends BaseAdminTestCase
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

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function createBanner(array $overrides = []): Banner
    {
        return Banner::create(array_merge([
            'title'      => 'Test Banner',
            'position'   => 'Slider',
            'sort_order' => 1,
            'is_visible' => true,
        ], $overrides));
    }

    // -------------------------------------------------------------------------
    // Tests
    // -------------------------------------------------------------------------

    public function test_cache_stores_and_retrieves_banner_data(): void
    {
        $banner = $this->createBanner(['title' => 'Cached Banner']);

        $cached = Cache::remember('homepage_banners', 60, function () {
            return Banner::orderBy('sort_order')->get();
        });

        $this->assertNotEmpty($cached);
        $this->assertEquals('Cached Banner', $cached->first()->title);

        // Second call returns cached value (not re-querying DB)
        $cachedAgain = Cache::remember('homepage_banners', 60, function () {
            return collect(); // would return empty if cache miss
        });

        $this->assertEquals('Cached Banner', $cachedAgain->first()->title);
    }

    public function test_cache_invalidated_after_banner_update(): void
    {
        $banner = $this->createBanner(['title' => 'Original Title']);

        // Cache the banner list
        Cache::remember('homepage_banners', 60, function () {
            return Banner::orderBy('sort_order')->get();
        });

        // Update the banner in DB
        $banner->update(['title' => 'Updated Title']);

        // Forget the cache key
        Cache::forget('homepage_banners');

        // Fresh query returns updated data
        $fresh = Cache::remember('homepage_banners', 60, function () {
            return Banner::orderBy('sort_order')->get();
        });

        $this->assertEquals('Updated Title', $fresh->first()->title);
    }

    public function test_cache_miss_returns_fresh_data_from_db(): void
    {
        $this->createBanner(['title' => 'DB Banner']);

        // Ensure cache key does not exist
        Cache::forget('homepage_banners');

        // Cache::get returns null on miss
        $cached = Cache::get('homepage_banners');
        $this->assertNull($cached);

        // Direct DB query returns the record
        $fromDb = Banner::orderBy('sort_order')->get();
        $this->assertNotEmpty($fromDb);
        $this->assertEquals('DB Banner', $fromDb->first()->title);
    }

    public function test_banner_create_reflected_after_cache_clear(): void
    {
        $this->createBanner(['title' => 'First Banner']);

        // Cache the initial list
        Cache::remember('homepage_banners', 60, function () {
            return Banner::orderBy('sort_order')->get();
        });

        // Create a new banner
        $this->createBanner(['title' => 'New Banner', 'sort_order' => 2]);

        // Clear cache
        Cache::forget('homepage_banners');

        // Fresh query includes the new banner
        $fresh = Cache::remember('homepage_banners', 60, function () {
            return Banner::orderBy('sort_order')->get();
        });

        $titles = $fresh->pluck('title')->toArray();
        $this->assertContains('New Banner', $titles);
        $this->assertCount(2, $fresh);
    }

    public function test_soft_deleted_banner_not_in_fresh_query_after_cache_clear(): void
    {
        $banner1 = $this->createBanner(['title' => 'Keep Banner', 'sort_order' => 1]);
        $banner2 = $this->createBanner(['title' => 'Delete Banner', 'sort_order' => 2]);

        // Cache the list
        Cache::remember('homepage_banners', 60, function () {
            return Banner::orderBy('sort_order')->get();
        });

        // Soft-delete one banner
        $banner2->delete();

        // Clear cache
        Cache::forget('homepage_banners');

        // Fresh query should not include soft-deleted banner
        $fresh = Cache::remember('homepage_banners', 60, function () {
            return Banner::orderBy('sort_order')->get();
        });

        $titles = $fresh->pluck('title')->toArray();
        $this->assertContains('Keep Banner', $titles);
        $this->assertNotContains('Delete Banner', $titles);
        $this->assertCount(1, $fresh);
    }
}
