<?php

namespace Database\Seeders;

use App\Models\StoreStatus;
use Illuminate\Database\Seeder;

class StoreStatusSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['code' => 'ACTIVE', 'name' => 'Active', 'traits' => 'Operational and open', 'color' => '#22c55e'],
            ['code' => 'INACTIVE', 'name' => 'Inactive', 'traits' => 'Temporarily inactive', 'color' => '#f97316'],
            ['code' => 'CLOSED', 'name' => 'Closed', 'traits' => 'Permanently closed', 'color' => '#ef4444']
        ];

        foreach ($items as $item) {
            StoreStatus::updateOrCreate(['code' => $item['code']], $item);
        }
    }
}
