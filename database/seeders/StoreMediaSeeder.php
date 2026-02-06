<?php

namespace Database\Seeders;

use App\Models\StoreMedia;
use App\Models\StoreVisit;
use Illuminate\Database\Seeder;

class StoreMediaSeeder extends Seeder
{
    public function run(): void
    {
        foreach (StoreVisit::all() as $visit) {
            StoreMedia::create([
                'visit_id' => $visit->id,
                'media_type' => 'PHOTO',
                'media_url' => 'https://placehold.co/600x400',
                'caption' => 'Sample photo',
                'taken_at' => now()->subDays(1)
            ]);
        }
    }
}
