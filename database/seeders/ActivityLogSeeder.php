<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\StoreMedia;
use App\Models\StoreVisitReport;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            return;
        }

        foreach (StoreVisitReport::take(10)->get() as $report) {
            ActivityLog::record($user->id, 'store_visit_report', $report->id, 'created', [
                'visit_id' => $report->visit_id
            ]);
        }

        foreach (StoreMedia::take(10)->get() as $media) {
            ActivityLog::record($user->id, 'store_media', $media->id, 'created', [
                'visit_id' => $media->visit_id,
                'media_url' => $media->media_url
            ]);
        }
    }
}
