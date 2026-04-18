<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use App\Models\Banner;
use App\Models\Book;
use App\Models\Collection;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\WeeklyRanking;
use App\Models\WeeklyRankingItem;
use Illuminate\Support\Facades\Schema;

/**
 * Tests that admin CRUD actions are reflected in homepage queries.
 * No HTTP requests — direct model queries only.
 */
class HomepageSyncTest extends BaseAdminTestCase
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

        Schema::create('flash_sales', function ($t) {
            $t->increments('id');
            $t->string('name', 255)->nullable();
            $t->dateTime('start_date');
            $t->dateTime('end_date');
            $t->timestamps();
        });

        Schema::create('flash_sale_items', function ($t) {
            $t->increments('id');
            $t->unsignedInteger('flash_sale_id');
            $t->unsignedInteger('book_id');
            $t->decimal('flash_price', 12, 0)->default(0);
            $t->unsignedTinyInteger('display_order')->default(1);
            $t->timestamps();
        });

        Schema::create('collections', function ($t) {
            $t->increments('id');
            $t->string('title', 255);
            $t->string('subtitle', 255)->nullable();
            $t->string('badge', 100)->nullable();
            $t->string('image', 255)->nullable();
            $t->string('url', 255)->nullable();
            $t->boolean('is_visible')->default(true);
            $t->integer('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('weekly_rankings', function ($t) {
            $t->increments('id');
            $t->string('week_name', 100)->nullable();
            $t->date('week_start');
            $t->date('week_end');
            $t->timestamps();
        });

        Schema::create('weekly_ranking_items', function ($t) {
            $t->increments('id');
            $t->unsignedInteger('weekly_ranking_id');
            $t->unsignedInteger('book_id');
            $t->unsignedTinyInteger('rank')->default(1);
            $t->timestamps();
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
        Schema::dropIfExists('weekly_ranking_items');
        Schema::dropIfExists('weekly_rankings');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('flash_sale_items');
        Schema::dropIfExists('flash_sales');
        Schema::dropIfExists('book_author');
        Schema::dropIfExists('books');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('banner');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }

    // -------------------------------------------------------------------------
    // Tests
    // -------------------------------------------------------------------------

    public function test_banner_visible_home_main_appears_in_homepage_query(): void
    {
        $banner = Banner::create([
            'title'      => 'Home Main Banner',
            'position'   => 'home_main',
            'is_visible' => true,
            'sort_order' => 1,
        ]);

        $results = Banner::where('is_visible', 1)->where('position', 'home_main')->get();

        $this->assertTrue($results->contains('id', $banner->id));
    }

    public function test_banner_set_invisible_disappears_from_homepage_query(): void
    {
        $banner = Banner::create([
            'title'      => 'Visible Banner',
            'position'   => 'home_main',
            'is_visible' => true,
            'sort_order' => 1,
        ]);

        $banner->update(['is_visible' => false]);

        $results = Banner::where('is_visible', 1)->where('position', 'home_main')->get();

        $this->assertFalse($results->contains('id', $banner->id));
    }

    public function test_soft_deleted_banner_disappears_from_all_homepage_queries(): void
    {
        $banner = Banner::create([
            'title'      => 'Banner To Delete',
            'position'   => 'home_main',
            'is_visible' => true,
            'sort_order' => 1,
        ]);

        $banner->delete();

        $mainResults = Banner::where('is_visible', 1)->where('position', 'home_main')->get();
        $miniResults = Banner::where('is_visible', 1)->where('position', 'home_mini')->get();

        $this->assertFalse($mainResults->contains('id', $banner->id));
        $this->assertFalse($miniResults->contains('id', $banner->id));
    }

    public function test_banner_position_change_moves_to_correct_query(): void
    {
        $banner = Banner::create([
            'title'      => 'Position Banner',
            'position'   => 'home_main',
            'is_visible' => true,
            'sort_order' => 1,
        ]);

        $banner->update(['position' => 'home_mini']);

        $mainResults = Banner::where('is_visible', 1)->where('position', 'home_main')->get();
        $miniResults = Banner::where('is_visible', 1)->where('position', 'home_mini')->get();

        $this->assertFalse($mainResults->contains('id', $banner->id));
        $this->assertTrue($miniResults->contains('id', $banner->id));
    }

    public function test_banners_ordered_by_sort_order_ascending(): void
    {
        Banner::create(['title' => 'Banner C', 'position' => 'home_main', 'is_visible' => true, 'sort_order' => 30]);
        Banner::create(['title' => 'Banner A', 'position' => 'home_main', 'is_visible' => true, 'sort_order' => 10]);
        Banner::create(['title' => 'Banner B', 'position' => 'home_main', 'is_visible' => true, 'sort_order' => 20]);

        $results = Banner::where('is_visible', 1)
            ->where('position', 'home_main')
            ->orderBy('sort_order')
            ->get();

        $this->assertEquals([10, 20, 30], $results->pluck('sort_order')->toArray());
    }

    public function test_active_flash_sale_with_items_returned_correctly(): void
    {
        $flashSale = FlashSale::create([
            'name'       => 'Active Sale',
            'start_date' => now()->subHour(),
            'end_date'   => now()->addHour(),
        ]);

        FlashSaleItem::create([
            'flash_sale_id' => $flashSale->id,
            'book_id'       => 1,
            'flash_price'   => 50000,
            'display_order' => 1,
        ]);

        $found = FlashSale::with(['items'])->active()->first();

        $this->assertNotNull($found);
        $this->assertEquals($flashSale->id, $found->id);
        $this->assertCount(1, $found->items);
        $this->assertEquals(50000, $found->items->first()->flash_price);
    }

    public function test_book_is_featured_appears_in_featured_query(): void
    {
        $book = Book::create([
            'sku'         => 'FEAT-001',
            'title'       => 'Featured Book',
            'slug'        => 'featured-book',
            'is_featured' => true,
            'status'      => 'in_stock',
            'stock'       => 10,
        ]);

        $results = Book::where('is_featured', 1)->where('status', 'in_stock')->get();

        $this->assertTrue($results->contains('id', $book->id));
    }

    public function test_book_is_featured_false_disappears_from_featured_query(): void
    {
        $book = Book::create([
            'sku'         => 'FEAT-002',
            'title'       => 'Featured Book 2',
            'slug'        => 'featured-book-2',
            'is_featured' => true,
            'status'      => 'in_stock',
            'stock'       => 10,
        ]);

        $book->update(['is_featured' => false]);

        $results = Book::where('is_featured', 1)->where('status', 'in_stock')->get();

        $this->assertFalse($results->contains('id', $book->id));
    }

    public function test_collection_visible_appears_in_query(): void
    {
        $collection = Collection::create([
            'title'      => 'Visible Collection',
            'is_visible' => true,
            'sort_order' => 1,
        ]);

        $results = Collection::where('is_visible', 1)->orderBy('sort_order')->get();

        $this->assertTrue($results->contains('id', $collection->id));
    }

    public function test_weekly_ranking_items_reflect_updates(): void
    {
        $ranking = WeeklyRanking::create([
            'week_name'  => 'Week 1',
            'week_start' => now()->startOfWeek()->toDateString(),
            'week_end'   => now()->endOfWeek()->toDateString(),
        ]);

        WeeklyRankingItem::create([
            'weekly_ranking_id' => $ranking->id,
            'book_id'           => 1,
            'rank'              => 1,
        ]);

        WeeklyRankingItem::create([
            'weekly_ranking_id' => $ranking->id,
            'book_id'           => 2,
            'rank'              => 2,
        ]);

        // Update: change rank of book_id=2 to rank=1 and book_id=1 to rank=2
        WeeklyRankingItem::where('weekly_ranking_id', $ranking->id)
            ->where('book_id', 1)
            ->update(['rank' => 2]);

        WeeklyRankingItem::where('weekly_ranking_id', $ranking->id)
            ->where('book_id', 2)
            ->update(['rank' => 1]);

        $ranking->load('items');

        $this->assertCount(2, $ranking->items);
        $this->assertEquals(1, $ranking->items->where('book_id', 2)->first()->rank);
        $this->assertEquals(2, $ranking->items->where('book_id', 1)->first()->rank);
    }
}
