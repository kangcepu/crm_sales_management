<?php

namespace Database\Seeders;

use App\Models\StoreCondition;
use App\Models\StoreConditionType;
use App\Models\StoreVisit;
use Illuminate\Database\Seeder;
use Faker\Factory;

class StoreConditionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        foreach (StoreVisit::all() as $visit) {
            $overall = $faker->randomElement(['ACTIVE', 'RISK', 'POTENTIAL', 'DROPPED']);
            $type = StoreConditionType::where('code', $overall)->first();
            StoreCondition::create([
                'visit_id' => $visit->id,
                'condition_type_id' => $type?->id,
                'exterior_condition' => $faker->randomElement(['GOOD', 'FAIR', 'BAD']),
                'interior_condition' => $faker->randomElement(['GOOD', 'FAIR', 'BAD']),
                'display_quality' => $faker->randomElement(['Excellent', 'Good', 'Average']),
                'cleanliness' => $faker->randomElement(['Excellent', 'Good', 'Average']),
                'shelf_availability' => $faker->randomElement(['High', 'Medium', 'Low']),
                'overall_status' => $overall
            ]);
        }
    }
}
