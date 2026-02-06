<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreAddress;
use Illuminate\Database\Seeder;
use Faker\Factory;

class StoreAddressSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        foreach (Store::all() as $store) {
            StoreAddress::create([
                'store_id' => $store->id,
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'province' => $faker->state,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude
            ]);
        }
    }
}
