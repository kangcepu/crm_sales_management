<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Area;
use Illuminate\Database\Seeder;
use Faker\Factory;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        $areas = Area::all();
        for ($i = 1; $i <= 5; $i++) {
            Store::create([
                'erp_store_id' => $faker->optional()->numerify('ERP-###'),
                'area_id' => $areas->isNotEmpty() ? $areas->random()->id : null,
                'store_code' => 'STR-'.str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'store_name' => $faker->company,
                'store_type' => $i % 2 === 0 ? 'CONSIGNMENT' : 'REGULAR',
                'owner_name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'is_active' => true
            ]);
        }
    }
}
