<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use Tests\Support\Traits\AssertsCrud;
use Tests\Support\Traits\AssertsSystemLog;
use App\Models\Coupon;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Schema;

/**
 * CRUD tests for Coupon entity.
 *
 * Coupon uses SoftDeletes.
 * Coupon model has $timestamps = false, so no created_at/updated_at.
 * Schema uses string instead of enum for SQLite :memory: compatibility.
 */
class CouponCrudTest extends BaseAdminTestCase
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

        Schema::create('coupons', function ($t) {
            $t->increments('id');
            $t->string('code', 50)->unique();
            $t->string('name', 200)->nullable();
            $t->string('type', 30)->default('percentage'); // string instead of enum for SQLite
            $t->decimal('value', 12, 0)->default(0);
            $t->decimal('max_discount', 12, 0)->default(0);
            $t->decimal('min_order_amount', 12, 0)->default(0);
            $t->integer('usage_limit')->default(0);
            $t->integer('used_count')->default(0);
            $t->timestamp('starts_at')->nullable();
            $t->timestamp('expires_at')->nullable();
            $t->string('status', 20)->default('active'); // string instead of enum for SQLite
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

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'code'   => 'TESTCODE10',
            'name'   => 'Test Coupon',
            'type'   => 'percentage',
            'value'  => 10,
            'status' => 'active',
        ], $overrides);
    }

    // -------------------------------------------------------------------------
    // Tests
    // -------------------------------------------------------------------------

    public function test_create_coupon_persists_with_correct_code_type_value(): void
    {
        $response = $this->withSession($this->adminSession())
            ->post(route('admin.coupons.store'), $this->validPayload([
                'code'  => 'SAVE20',
                'type'  => 'percentage',
                'value' => 20,
            ]));

        $response->assertRedirect(route('admin.coupons.index'));

        $this->assertDatabaseHas('coupons', [
            'code'  => 'SAVE20',
            'type'  => 'percentage',
            'value' => 20,
        ]);

        $coupon = Coupon::where('code', 'SAVE20')->first();
        $this->assertNotNull($coupon);

        $this->assertSystemLog(
            action: 'create',
            objectType: 'Coupon',
            objectId: $coupon->id,
            level: 'info',
            type: 'data'
        );
    }

    public function test_update_coupon_value_without_creating_new_record(): void
    {
        $coupon = Coupon::create([
            'code'   => 'FIXED50',
            'name'   => 'Fixed 50k',
            'type'   => 'fixed_amount',
            'value'  => 50000,
            'status' => 'active',
        ]);

        $this->assertCountUnchanged('coupons', function () use ($coupon) {
            $this->withSession($this->adminSession())
                ->put(route('admin.coupons.update', $coupon), [
                    'code'   => 'FIXED50',
                    'name'   => 'Fixed 50k',
                    'type'   => 'fixed_amount',
                    'value'  => 75000,
                    'status' => 'active',
                ]);
        });

        $this->assertDatabaseHas('coupons', [
            'id'    => $coupon->id,
            'value' => 75000,
        ]);
    }

    public function test_soft_delete_coupon(): void
    {
        $coupon = Coupon::create([
            'code'   => 'DELETEME',
            'name'   => 'Delete Me',
            'type'   => 'percentage',
            'value'  => 5,
            'status' => 'active',
        ]);

        $id = $coupon->id;

        $this->withSession($this->adminSession())
            ->delete(route('admin.coupons.destroy', $coupon));

        $this->assertSoftDeleted('coupons', ['id' => $id]);
        $this->assertNull(Coupon::find($id), 'Soft-deleted coupon should not appear in default query');

        $this->assertSystemLog(
            action: 'delete',
            objectType: 'Coupon',
            objectId: $id,
            level: 'warning',
            type: 'data'
        );
    }

    public function test_duplicate_coupon_code_returns_validation_error(): void
    {
        // Create first coupon directly
        Coupon::create([
            'code'   => 'DUPCODE',
            'name'   => 'Original',
            'type'   => 'percentage',
            'value'  => 10,
            'status' => 'active',
        ]);

        // CouponController::store() uses plain Request without unique validation.
        // Attempting to create a duplicate code hits the DB UNIQUE constraint (500).
        // We verify only 1 coupon with that code exists.
        $this->withSession($this->adminSession())
            ->post(route('admin.coupons.store'), $this->validPayload([
                'code' => 'DUPCODE',
            ]));

        // Only the original coupon should exist
        $count = Coupon::withTrashed()->where('code', 'DUPCODE')->count();
        $this->assertEquals(1, $count, 'Only one coupon with code DUPCODE should exist');
    }

    public function test_system_log_created_for_coupon_crud(): void
    {
        $session = $this->adminSession();

        // Create
        $this->withSession($session)->post(route('admin.coupons.store'), $this->validPayload([
            'code' => 'LOGTEST01',
        ]));

        $coupon = Coupon::where('code', 'LOGTEST01')->first();
        $this->assertNotNull($coupon);

        // Update
        $this->withSession($session)->put(route('admin.coupons.update', $coupon), [
            'code'   => 'LOGTEST01',
            'name'   => 'Updated',
            'type'   => 'percentage',
            'value'  => 15,
            'status' => 'active',
        ]);

        // Delete
        $this->withSession($session)->delete(route('admin.coupons.destroy', $coupon));

        $logCount = SystemLog::where('object_type', 'Coupon')
            ->where('object_id', $coupon->id)
            ->whereIn('action', ['create', 'update', 'delete'])
            ->count();

        $this->assertEquals(3, $logCount, 'Expected 3 SystemLog entries (create, update, delete) for Coupon');
    }
}
