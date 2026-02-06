<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreVisit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory;

class StoreVisitSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        $marketing = User::whereHas('roles', function ($q) {
            $q->where('name', 'Marketing');
        })->first();

        if (!$marketing) {
            return;
        }

        foreach (Store::all() as $store) {
            for ($i = 0; $i < 2; $i++) {
                StoreVisit::create([
                    'store_id' => $store->id,
                    'user_id' => $marketing->id,
                    'visit_at' => now()->subDays($faker->numberBetween(1, 14)),
                    'latitude' => $faker->latitude,
                    'longitude' => $faker->longitude,
                    'distance_from_store' => $faker->randomFloat(2, 0, 1.5),
                    'visit_status' => $faker->randomElement(['ON_TIME', 'OUT_OF_RANGE']),
                    'summary' => $faker->sentence,
                    'next_visit_plan' => $faker->sentence
                ]);
            }
        }
    }
}
