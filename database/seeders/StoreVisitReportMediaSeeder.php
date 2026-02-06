<?php

namespace Database\Seeders;

use App\Models\StoreVisitReport;
use App\Models\StoreVisitReportMedia;
use Illuminate\Database\Seeder;

class StoreVisitReportMediaSeeder extends Seeder
{
    public function run(): void
    {
        foreach (StoreVisitReport::all() as $report) {
            StoreVisitReportMedia::create([
                'report_id' => $report->id,
                'media_type' => 'PHOTO',
                'media_url' => 'https://placehold.co/600x400',
                'caption' => 'Report photo',
                'taken_at' => now()->subDays(1)
            ]);
        }
    }
}
