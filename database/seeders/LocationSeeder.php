<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $country = Country::updateOrCreate(['code' => 'ID'], ['name' => 'Indonesia']);

        $province = Province::updateOrCreate(
            ['country_id' => $country->id, 'code' => 'DKI'],
            ['name' => 'DKI Jakarta']
        );

        $city = City::updateOrCreate(
            ['province_id' => $province->id, 'code' => 'JKT-PUS'],
            ['name' => 'Jakarta Pusat', 'type' => 'Kota']
        );

        $district = District::updateOrCreate(
            ['city_id' => $city->id, 'code' => 'MENTENG'],
            ['name' => 'Menteng']
        );

        Village::updateOrCreate(
            ['district_id' => $district->id, 'code' => 'GONDANGDIA'],
            ['name' => 'Gondangdia']
        );
    }
}
