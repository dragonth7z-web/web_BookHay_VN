<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Nguyễn Thị Mai', 'email' => 'mai.nguyen@gmail.com', 'phone' => '0901234567', 'gender' => 'female', 'total_spent' => 5200000, 'loyalty_points' => 520, 'created_at' => now()->subDays(90)],
            ['name' => 'Trần Văn Hùng', 'email' => 'hung.tran@gmail.com', 'phone' => '0912345678', 'gender' => 'male', 'total_spent' => 3800000, 'loyalty_points' => 380, 'created_at' => now()->subDays(60)],
            ['name' => 'Lê Thị Hồng', 'email' => 'hong.le@gmail.com', 'phone' => '0923456789', 'gender' => 'female', 'total_spent' => 2100000, 'loyalty_points' => 210, 'created_at' => now()->subDays(45)],
            ['name' => 'Phạm Quốc Bảo', 'email' => 'bao.pham@gmail.com', 'phone' => '0934567890', 'gender' => 'male', 'total_spent' => 1500000, 'loyalty_points' => 150, 'created_at' => now()->subDays(30)],
            ['name' => 'Vũ Minh Châu', 'email' => 'chau.vu@gmail.com', 'phone' => '0945678901', 'gender' => 'female', 'total_spent' => 890000, 'loyalty_points' => 89, 'created_at' => now()->subDays(20)],
            ['name' => 'Đặng Thanh Tùng', 'email' => 'tung.dang@gmail.com', 'phone' => '0956789012', 'gender' => 'male', 'total_spent' => 650000, 'loyalty_points' => 65, 'created_at' => now()->subDays(15)],
            ['name' => 'Hoàng Thị Lan', 'email' => 'lan.hoang@gmail.com', 'phone' => '0967890123', 'gender' => 'female', 'total_spent' => 450000, 'loyalty_points' => 45, 'created_at' => now()->subDays(10)],
            ['name' => 'Bùi Xuân Đức', 'email' => 'duc.bui@gmail.com', 'phone' => '0978901234', 'gender' => 'male', 'total_spent' => 320000, 'loyalty_points' => 32, 'created_at' => now()->subDays(7)],
            ['name' => 'Ngô Phương Thảo', 'email' => 'thao.ngo@gmail.com', 'phone' => '0989012345', 'gender' => 'female', 'total_spent' => 180000, 'loyalty_points' => 18, 'created_at' => now()->subDays(5)],
            ['name' => 'Dương Minh Khoa', 'email' => 'khoa.duong@gmail.com', 'phone' => '0990123456', 'gender' => 'male', 'total_spent' => 0, 'loyalty_points' => 0, 'created_at' => now()->subDays(2)],
            ['name' => 'Phan Thị Hương', 'email' => 'huong.phan@gmail.com', 'phone' => '0911223344', 'gender' => 'female', 'total_spent' => 0, 'loyalty_points' => 0, 'created_at' => now()->subDays(1)],
            ['name' => 'Lý Quang Vinh', 'email' => 'vinh.ly@gmail.com', 'phone' => '0922334455', 'gender' => 'male', 'total_spent' => 0, 'loyalty_points' => 0, 'created_at' => now()],
        ];

        foreach ($customers as $c) {
            User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'password' => Hash::make('customer123'),
                    'phone' => $c['phone'],
                    'role_id' => 2,
                    'status' => 'active',
                    'gender' => $c['gender'],
                    'total_spent' => $c['total_spent'],
                    'loyalty_points' => $c['loyalty_points'],
                    'created_at' => $c['created_at'],
                    'updated_at' => $c['created_at'],
                ]
            );
        }

        echo "Seeded " . count($customers) . " customer accounts.\n";
    }
}
