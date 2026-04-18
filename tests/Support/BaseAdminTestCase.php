<?php

namespace Tests\Support;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

abstract class BaseAdminTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
        $this->seedPermissions();
        $this->fakeStorage();
    }

    protected function tearDown(): void
    {
        $this->dropSchema();
        parent::tearDown();
    }

    /** Chạy migrations cần thiết cho test (inline Schema::create) */
    abstract protected function createSchema(): void;

    /** Xóa tất cả tables đã tạo trong createSchema() */
    abstract protected function dropSchema(): void;

    /** Seed roles + admin user tối thiểu */
    protected function seedPermissions(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'code' => 'ADMIN',    'name' => 'Admin'],
            ['id' => 2, 'code' => 'CUSTOMER', 'name' => 'Khách hàng'],
        ]);

        DB::table('users')->insert([
            'id'             => 1,
            'name'           => 'Admin Test',
            'email'          => 'admin@test.vn',
            'password'       => bcrypt('secret'),
            'role_id'        => 1,
            'status'         => 'active',
            'loyalty_points' => 0,
            'total_spent'    => 0,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    /** Intercept file operations */
    protected function fakeStorage(): void
    {
        Storage::fake('public');
    }

    /**
     * Authenticate admin user bằng cách set session trực tiếp.
     * Không dùng POST login vì guest middleware sẽ chặn.
     */
    protected function loginAdmin(): void
    {
        $this->withSession([
            'user_id'    => 1,
            'user_name'  => 'Admin Test',
            'user_role'  => 1,
            'user_email' => 'admin@test.vn',
        ]);
    }
}
