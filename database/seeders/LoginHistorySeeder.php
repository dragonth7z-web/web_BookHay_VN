<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LoginHistorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('login_histories')->truncate();

        $customers = User::where('role_id', 2)->get();

        if ($customers->isEmpty()) {
            echo "⚠ Chưa có customers. Chạy CustomerSeeder trước.\n";
            return;
        }

        $logs = [];
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_2 like Mac OS X) Mobile/15E148',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Safari/605.1.15',
            'Mozilla/5.0 (Linux; Android 14) Chrome/120.0.6099.43 Mobile',
        ];

        foreach ($customers as $customer) {
            $numLogins = rand(2, 8);
            for ($i = 0; $i < $numLogins; $i++) {
                $loginDate = now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                $logs[] = [
                    'user_id'    => $customer->id,
                    'ip_address' => '192.168.1.' . rand(1, 254),
                    'device'     => $userAgents[array_rand($userAgents)],
                    'status'     => 'success',
                    'created_at' => $loginDate,
                ];
            }
        }

        $admin = User::where('role_id', 1)->first();
        if ($admin) {
            for ($i = 0; $i < 15; $i++) {
                $loginDate = now()->subDays(rand(0, 30))->subHours(rand(6, 22));
                $logs[] = [
                    'user_id'    => $admin->id,
                    'ip_address' => '10.0.0.' . rand(1, 10),
                    'device'     => $userAgents[0],
                    'status'     => 'success',
                    'created_at' => $loginDate,
                ];
            }
        }

        DB::table('login_histories')->insert($logs);

        echo "Seeded " . count($logs) . " login history records.\n";
    }
}
