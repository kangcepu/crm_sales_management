<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreStatusHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class StoreStatusHistorySeeder extends Seeder
{
    public function run(): void
    {
        $supervisor = User::whereHas('roles', function ($q) {
            $q->where('name', 'Supervisor');
        })->first();

        if (!$supervisor) {
            return;
        }

        foreach (Store::all() as $store) {
            StoreStatusHistory::create([
                'store_id' => $store->id,
                'status' => 'ACTIVE',
                'note' => 'Initial activation',
                'changed_by_user_id' => $supervisor->id,
                'changed_at' => now()->subDays(2)
            ]);
        }
    }
}
