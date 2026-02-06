<?php

namespace Database\Seeders;

use App\Models\StoreVisit;
use App\Models\StoreVisitReport;
use Illuminate\Database\Seeder;
use Faker\Factory;

class StoreVisitReportSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        foreach (StoreVisit::all() as $visit) {
            StoreVisitReport::create([
                'visit_id' => $visit->id,
                'consignment_qty' => $faker->numberBetween(0, 50),
                'consignment_value' => $faker->randomFloat(2, 100, 5000),
                'sales_qty' => $faker->numberBetween(0, 50),
                'sales_value' => $faker->randomFloat(2, 100, 8000),
                'payment_status' => $faker->randomElement(['PAID', 'PENDING']),
                'competitor_activity' => $faker->sentence,
                'notes' => $faker->sentence
            ]);
        }
    }
}
