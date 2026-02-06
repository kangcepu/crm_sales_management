<?php

namespace Database\Seeders;

use App\Models\Deal;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory;

class DealSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        $owner = User::whereHas('roles', function ($q) {
            $q->where('name', 'Supervisor');
        })->first();

        if (!$owner) {
            return;
        }

        foreach (Store::all() as $store) {
            Deal::create([
                'store_id' => $store->id,
                'owner_user_id' => $owner->id,
                'deal_name' => $faker->catchPhrase,
                'amount' => $faker->randomFloat(2, 500, 15000),
                'stage' => $faker->randomElement(['PROSPECT', 'NEGOTIATION', 'WON', 'LOST']),
                'expected_close_date' => now()->addDays($faker->numberBetween(5, 30))
            ]);
        }
    }
}
