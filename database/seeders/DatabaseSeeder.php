<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            AreaSeeder::class,
            StoreSeeder::class,
            StoreAddressSeeder::class,
            StoreAssignmentSeeder::class,
            StoreVisitSeeder::class,
            StoreVisitReportSeeder::class,
            StoreVisitReportMediaSeeder::class,
            StoreConditionSeeder::class,
            StoreMediaSeeder::class,
            StoreStatusHistorySeeder::class,
            DealSeeder::class,
            ActivityLogSeeder::class
        ]);
    }
}
