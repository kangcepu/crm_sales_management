<?php

namespace Database\Seeders;

use App\Models\StoreConditionType;
use Illuminate\Database\Seeder;

class StoreConditionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['code' => 'ACTIVE', 'name' => 'Active', 'traits' => 'Healthy condition', 'color' => '#22c55e'],
            ['code' => 'RISK', 'name' => 'Risk', 'traits' => 'Potential risk detected', 'color' => '#f97316'],
            ['code' => 'POTENTIAL', 'name' => 'Potential', 'traits' => 'Potential improvement', 'color' => '#8b5cf6'],
            ['code' => 'DROPPED', 'name' => 'Dropped', 'traits' => 'Condition dropped', 'color' => '#ef4444']
        ];

        foreach ($items as $item) {
            StoreConditionType::updateOrCreate(['code' => $item['code']], $item);
        }
    }
}
