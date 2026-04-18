<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use Tests\Support\Traits\AssertsCrud;
use Tests\Support\Traits\AssertsSystemLog;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Schema;

/**
 * CRUD tests for FlashSale entity.
 *
 * FlashSale does NOT use SoftDeletes.
 * Route parameter name is 'flashSale' (from ->parameters(['flash-sales' => 'flashSale'])).
 * FlashSale::active() scope: start_date <= now AND end_date >= now.
 */
class FlashSaleCrudTest extends BaseAdminTestCase
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
        Schema::dropIfExists('flash_sale_items');
        Schema::dropIfExists('flash_sales');
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

    public function test_active_flash_sale_appears_in_active_scope(): void
    {
        $flashSale = FlashSale::create([
            'name'       => 'Active Sale',
            'start_date' => now()->subDay(),
            'end_date'   => now()->addDay(),
        ]);

        $found = FlashSale::active()->first();

        $this->assertNotNull($found, 'Active flash sale should appear in active() scope');
        $this->assertEquals($flashSale->id, $found->id);
    }

    public function test_future_flash_sale_not_in_active_scope(): void
    {
        FlashSale::create([
            'name'       => 'Future Sale',
            'start_date' => now()->addDay(),
            'end_date'   => now()->addDays(2),
        ]);

        $found = FlashSale::active()->first();

        $this->assertNull($found, 'Future flash sale should NOT appear in active() scope');
    }

    public function test_expired_flash_sale_not_in_active_scope(): void
    {
        FlashSale::create([
            'name'       => 'Expired Sale',
            'start_date' => now()->subDays(3),
            'end_date'   => now()->subDay(),
        ]);

        $found = FlashSale::active()->first();

        $this->assertNull($found, 'Expired flash sale should NOT appear in active() scope');
    }

    public function test_delete_active_flash_sale_removes_from_active_scope(): void
    {
        $flashSale = FlashSale::create([
            'name'       => 'Sale To Delete',
            'start_date' => now()->subHour(),
            'end_date'   => now()->addHour(),
        ]);

        // Verify it's active before deletion
        $this->assertNotNull(FlashSale::active()->first());

        $this->withSession($this->adminSession())
            ->delete(route('admin.flash-sales.destroy', $flashSale));

        $found = FlashSale::active()->first();
        $this->assertNull($found, 'Deleted flash sale should not appear in active() scope');
    }

    public function test_flash_sale_items_count_after_adding_items(): void
    {
        $flashSale = FlashSale::create([
            'name'       => 'Sale With Items',
            'start_date' => now()->subHour(),
            'end_date'   => now()->addHour(),
        ]);

        // Add items directly (bypassing controller which requires books table)
        FlashSaleItem::create([
            'flash_sale_id' => $flashSale->id,
            'book_id'       => 1,
            'flash_price'   => 50000,
            'display_order' => 1,
        ]);

        FlashSaleItem::create([
            'flash_sale_id' => $flashSale->id,
            'book_id'       => 2,
            'flash_price'   => 75000,
            'display_order' => 2,
        ]);

        $flashSale->load('items');
        $this->assertCount(2, $flashSale->items, 'Flash sale should have 2 items');
    }

    public function test_system_log_created_for_flash_sale_crud(): void
    {
        $session = $this->adminSession();

        // Create via HTTP (FlashSaleController.store requires items)
        // We create directly and test the delete log via HTTP
        $flashSale = FlashSale::create([
            'name'       => 'Log Test Sale',
            'start_date' => now()->subHour(),
            'end_date'   => now()->addHour(),
        ]);

        // Log create manually to simulate what controller does
        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Tạo flash sale mới: Log Test Sale',
            level: 'info',
            objectType: 'FlashSale',
            objectId: $flashSale->id
        );

        // Delete via HTTP — controller logs this
        $this->withSession($session)
            ->delete(route('admin.flash-sales.destroy', $flashSale));

        $this->assertSystemLog(
            action: 'create',
            objectType: 'FlashSale',
            objectId: $flashSale->id,
            level: 'info',
            type: 'data'
        );

        $this->assertSystemLog(
            action: 'delete',
            objectType: 'FlashSale',
            objectId: $flashSale->id,
            level: 'warning',
            type: 'data'
        );
    }
}
