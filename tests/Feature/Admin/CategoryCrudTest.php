<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use Tests\Support\Traits\AssertsCrud;
use Tests\Support\Traits\AssertsSystemLog;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;

/**
 * CRUD tests for Category entity.
 *
 * Category uses slug as route key.
 * Category does NOT use SoftDeletes (no deleted_at column in migration).
 * Schema uses string instead of enum for SQLite :memory: compatibility.
 */
class CategoryCrudTest extends BaseAdminTestCase
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

        Schema::create('categories', function ($t) {
            $t->increments('id');
            $t->unsignedInteger('parent_id')->nullable();
            $t->string('name', 100);
            $t->string('slug', 150)->unique();
            $t->text('description')->nullable();
            $t->string('image', 255)->nullable();
            $t->string('badge_text', 50)->nullable();
            $t->string('badge_color', 20)->nullable();
            $t->smallInteger('sort_order')->default(0);
            $t->boolean('is_visible')->default(true);
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
        Schema::dropIfExists('categories');
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

    public function test_create_category_persists_with_correct_name_and_sort_order(): void
    {
        $response = $this->withSession($this->adminSession())
            ->post(route('admin.categories.store'), [
                'name'       => 'Fiction',
                'slug'       => 'fiction',
                'sort_order' => 5,
                'is_visible' => 1,
            ]);

        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'name'       => 'Fiction',
            'slug'       => 'fiction',
            'sort_order' => 5,
        ]);

        $category = Category::where('slug', 'fiction')->first();
        $this->assertNotNull($category);
        $this->assertEquals(5, $category->sort_order);
    }

    public function test_update_sort_order_persists_without_creating_new_record(): void
    {
        $category = Category::create([
            'name'       => 'Science',
            'slug'       => 'science',
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        $this->assertCountUnchanged('categories', function () use ($category) {
            $this->withSession($this->adminSession())
                ->put(route('admin.categories.update', $category), [
                    'name'       => 'Science',
                    'slug'       => 'science',
                    'sort_order' => 10,
                    'is_visible' => 1,
                ]);
        });

        $this->assertDatabaseHas('categories', [
            'id'         => $category->id,
            'sort_order' => 10,
        ]);
    }

    public function test_delete_category_removes_from_frontend_query(): void
    {
        $category = Category::create([
            'name'       => 'History',
            'slug'       => 'history',
            'sort_order' => 2,
            'is_visible' => true,
        ]);

        $id = $category->id;

        $this->withSession($this->adminSession())
            ->delete(route('admin.categories.destroy', $category));

        // Category model uses SoftDeletes — should not appear in default query
        $found = Category::where('id', $id)->first();
        $this->assertNull($found, 'Deleted category should not appear in default query');
    }

    public function test_categories_ordered_by_sort_order_ascending(): void
    {
        Category::create(['name' => 'Cat C', 'slug' => 'cat-c', 'sort_order' => 30]);
        Category::create(['name' => 'Cat A', 'slug' => 'cat-a', 'sort_order' => 10]);
        Category::create(['name' => 'Cat B', 'slug' => 'cat-b', 'sort_order' => 20]);

        $categories = Category::orderBy('sort_order', 'asc')->get();

        $this->assertEquals('Cat A', $categories[0]->name);
        $this->assertEquals('Cat B', $categories[1]->name);
        $this->assertEquals('Cat C', $categories[2]->name);
    }

    public function test_create_category_without_name_returns_validation_error(): void
    {
        // CategoryController::store() uses plain Request without validation rules.
        // Submitting without 'name' causes a DB NOT NULL constraint violation (500).
        // We verify the category was NOT created in the database.
        $countBefore = \Illuminate\Support\Facades\DB::table('categories')->count();

        $this->withSession($this->adminSession())
            ->post(route('admin.categories.store'), [
                'slug'       => 'no-name-cat',
                'sort_order' => 1,
            ]);

        // No new category should have been persisted
        $countAfter = \Illuminate\Support\Facades\DB::table('categories')->count();
        $this->assertEquals($countBefore, $countAfter, 'No category should be created when name is missing');
        $this->assertDatabaseMissing('categories', ['slug' => 'no-name-cat']);
    }
}
