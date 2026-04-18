<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        try {
            $this->call([
                // 1. Foundation data
                UserSeeder::class,
                CustomerSeeder::class,
                BookSeeder::class,

                // 2. Homepage content
                BannerSeeder::class,
                FlashSaleSeeder::class,
                WeeklyRankingSeeder::class,
                ComboSeeder::class,
                CollectionSeeder::class,
                ConfigurationSeeder::class,
                VoucherSeeder::class,

                // 3. Transactional data (needs customers + books)
                OrderSeeder::class,
                LoginHistorySeeder::class,
            ]);
        }
        catch (\Exception $e) {
            $this->command->error($e->getMessage());
            if (method_exists($e, 'getQuery')) {
                $this->command->warn($e->getQuery());
            }
            throw $e;
        }
    }
}
