<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        Area::create(['area_code' => 'A', 'area_name' => 'Area A', 'description' => 'Primary area', 'is_active' => true]);
        Area::create(['area_code' => 'B', 'area_name' => 'Area B', 'description' => 'Secondary area', 'is_active' => true]);
    }
}
