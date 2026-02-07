<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['key' => 'users.manage', 'name' => 'Manage Users'],
            ['key' => 'roles.manage', 'name' => 'Manage Roles'],
            ['key' => 'settings.manage', 'name' => 'Manage Settings'],
            ['key' => 'stores.manage', 'name' => 'Manage Stores'],
            ['key' => 'assignments.manage', 'name' => 'Manage Store Assignments'],
            ['key' => 'areas.manage', 'name' => 'Manage Areas'],
            ['key' => 'area_mapping.view', 'name' => 'View Area Mapping'],
            ['key' => 'status_history.manage', 'name' => 'Manage Status History'],
            ['key' => 'store_statuses.manage', 'name' => 'Manage Store Statuses'],
            ['key' => 'condition_types.manage', 'name' => 'Manage Store Condition Types'],
            ['key' => 'visits.manage', 'name' => 'Manage Visits'],
            ['key' => 'visit_reports.manage', 'name' => 'Manage Visit Reports'],
            ['key' => 'report_tracking.view', 'name' => 'View Report Tracking'],
            ['key' => 'conditions.manage', 'name' => 'Manage Store Conditions'],
            ['key' => 'media.manage', 'name' => 'Manage Store Media'],
            ['key' => 'deals.manage', 'name' => 'Manage Deals']
        ];

        foreach ($items as $item) {
            Permission::firstOrCreate(['key' => $item['key']], $item);
        }
    }
}
