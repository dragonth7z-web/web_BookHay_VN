<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Roles if they don't exist
        $roles = [
            ['id' => 1, 'code' => 'ADMIN', 'name' => 'Quản trị viên', 'description' => 'Toàn quyền quản lý hệ thống'],
            ['id' => 2, 'code' => 'CUSTOMER', 'name' => 'Khách hàng', 'description' => 'Người mua hàng'],
            ['id' => 3, 'code' => 'STAFF', 'name' => 'Nhân viên', 'description' => 'Nhân viên quản lý đơn hàng & kho'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['id' => $role['id']], $role);
        }

        // 2. Seed Admin Account
        User::updateOrCreate(
        ['email' => 'admin@bookstore.vn'],
        [
            'name' => 'Admin Book Store',
            'password' => Hash::make('admin123'),
            'phone' => '0987654321',
            'role_id' => 1,
            'status' => 'active',
            'gender' => 'male',
        ]
        );

        // 3. Seed Regular User Account
        User::updateOrCreate(
        ['email' => 'user@gmail.com'],
        [
            'name' => 'Trần Bình An',
            'password' => Hash::make('user123'),
            'phone' => '0123456789',
            'role_id' => 2,
            'status' => 'active',
            'gender' => 'male',
        ]
        );

        // 4. Seed Staff Account
        User::updateOrCreate(
        ['email' => 'staff@bookstore.vn'],
        [
            'name' => 'Nhân viên Book Store',
            'password' => Hash::make('staff123'),
            'phone' => '0987654322',
            'role_id' => 3,
            'status' => 'active',
            'gender' => 'female',
        ]
        );

        echo "Admin: admin@bookstore.vn / admin123\n";
        echo "User: user@gmail.com / user123\n";
        echo "Staff: staff@bookstore.vn / staff123\n";
    }
}
