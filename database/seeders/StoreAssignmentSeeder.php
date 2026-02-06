<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class StoreAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $marketing = User::whereHas('roles', function ($q) {
            $q->where('name', 'Marketing');
        })->first();

        if (!$marketing) {
            return;
        }

        foreach (Store::all() as $store) {
            StoreAssignment::create([
                'store_id' => $store->id,
                'user_id' => $marketing->id,
                'assignment_role' => 'MARKETING',
                'assigned_from' => now()->subDays(10),
                'assigned_to' => null,
                'is_primary' => true
            ]);
        }
    }
}
