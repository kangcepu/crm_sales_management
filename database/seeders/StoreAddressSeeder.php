<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreAddress;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Faker\Factory;

class StoreAddressSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        $country = Country::first();
        $province = Province::first();
        $city = City::first();
        $district = District::first();
        $village = Village::first();
        foreach (Store::all() as $store) {
            StoreAddress::create([
                'store_id' => $store->id,
                'country_id' => $country?->id,
                'province_id' => $province?->id,
                'city_id' => $city?->id,
                'district_id' => $district?->id,
                'village_id' => $village?->id,
                'address' => $faker->streetAddress,
                'city' => $city?->name ?? $faker->city,
                'province' => $province?->name ?? $faker->state,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude
            ]);
        }
    }
}
