<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use Tests\Support\Traits\AssertsCrud;
use Tests\Support\Traits\AssertsSystemLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SystemLog;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Schema;

/**
 * CRUD tests for Order entity.
 *
 * Order does NOT use SoftDeletes.
 * Routes: admin.orders.index, admin.orders.update, admin.orders.destroy (no create/store).
 * UpdateOrderRequest requires: status, payment_status, cancel_reason (nullable).
 * Schema uses string instead of enum for SQLite :memory: compatibility.
 */
class OrderCrudTest extends BaseAdminTestCase
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

        Schema::create('orders', function ($t) {
            $t->increments('id');
            $t->string('order_number', 20)->unique();
            $t->unsignedBigInteger('user_id');
            $t->string('recipient_name', 100)->nullable();
            $t->string('recipient_phone', 15)->nullable();
            $t->text('shipping_address')->nullable();
            $t->decimal('subtotal', 12, 0)->default(0);
            $t->decimal('shipping_fee', 12, 0)->default(0);
            $t->decimal('discount_amount', 12, 0)->default(0);
            $t->decimal('total', 12, 0)->default(0);
            $t->unsignedInteger('coupon_id')->nullable();
            $t->string('transaction_ref', 200)->nullable();
            $t->string('payment_method', 30)->default('cod'); // string instead of enum for SQLite
            $t->string('payment_status', 20)->default('unpaid'); // string instead of enum for SQLite
            $t->string('status', 30)->default('pending'); // string instead of enum for SQLite
            $t->text('notes')->nullable();
            $t->text('cancel_reason')->nullable();
            $t->timestamps();
        });

        Schema::create('order_items', function ($t) {
            $t->increments('id');
            $t->unsignedInteger('order_id');
            $t->unsignedInteger('book_id');
            $t->string('book_title_snapshot', 255)->nullable();
            $t->string('book_image_snapshot', 255)->nullable();
            $t->unsignedSmallInteger('quantity')->default(1);
            $t->decimal('unit_price', 12, 0)->default(0);
            $t->decimal('subtotal', 12, 0)->default(0);
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
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
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

    private function createOrder(array $overrides = []): Order
    {
        static $counter = 0;
        $counter++;
        return Order::create(array_merge([
            'order_number'    => 'ORD-TEST-' . str_pad($counter, 4, '0', STR_PAD_LEFT),
            'user_id'         => 1,
            'recipient_name'  => 'Test Customer',
            'recipient_phone' => '0900000000',
            'shipping_address'=> '123 Test Street',
            'status'          => 'pending',
            'payment_status'  => 'unpaid',
            'payment_method'  => 'cod',
            'total'           => 100000,
        ], $overrides));
    }

    // -------------------------------------------------------------------------
    // Tests
    // -------------------------------------------------------------------------

    public function test_update_order_status_persists_new_value(): void
    {
        $order = $this->createOrder(['status' => 'pending']);

        $this->withSession($this->adminSession())
            ->put(route('admin.orders.update', $order), [
                'status'         => 'confirmed',
                'payment_status' => 'unpaid',
            ]);

        $order->refresh();
        $this->assertEquals('confirmed', $order->status->value);
    }

    public function test_update_order_status_does_not_create_new_record(): void
    {
        $order = $this->createOrder(['status' => 'pending']);

        $this->assertCountUnchanged('orders', function () use ($order) {
            $this->withSession($this->adminSession())
                ->put(route('admin.orders.update', $order), [
                    'status'         => 'shipping',
                    'payment_status' => 'unpaid',
                ]);
        });
    }

    public function test_cancel_order_sets_cancelled_status(): void
    {
        $order = $this->createOrder(['status' => 'confirmed']);

        $this->withSession($this->adminSession())
            ->put(route('admin.orders.update', $order), [
                'status'         => 'cancelled',
                'payment_status' => 'unpaid',
                'cancel_reason'  => 'Customer requested cancellation',
            ]);

        $order->refresh();
        $this->assertEquals(OrderStatus::Cancelled, $order->status);
        $this->assertEquals('Customer requested cancellation', $order->cancel_reason);
    }

    public function test_system_log_created_for_order_update(): void
    {
        $order = $this->createOrder(['status' => 'pending']);

        $this->withSession($this->adminSession())
            ->put(route('admin.orders.update', $order), [
                'status'         => 'confirmed',
                'payment_status' => 'unpaid',
            ]);

        $this->assertSystemLog(
            action: 'update',
            objectType: 'Order',
            objectId: $order->id,
            level: 'info',
            type: 'data'
        );
    }

    public function test_order_always_has_user_id_and_order_items(): void
    {
        $order = $this->createOrder();

        // Add order items
        OrderItem::create([
            'order_id'             => $order->id,
            'book_id'              => 1,
            'book_title_snapshot'  => 'Test Book',
            'quantity'             => 2,
            'unit_price'           => 50000,
            'subtotal'             => 100000,
        ]);

        $this->assertNotNull($order->user_id, 'Order must have a non-null user_id');

        $order->load('items');
        $this->assertGreaterThanOrEqual(1, $order->items->count(), 'Order must have at least 1 order item');
    }
}
