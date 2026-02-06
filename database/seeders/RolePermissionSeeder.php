<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = Permission::all()->keyBy('key');

        $this->sync('Admin', $permissions->keys()->all());
        $this->sync('Supervisor', [
            'stores.manage',
            'assignments.manage',
            'areas.manage',
            'area_mapping.view',
            'status_history.manage',
            'visits.manage',
            'visit_reports.manage',
            'report_tracking.view',
            'conditions.manage',
            'media.manage',
            'deals.manage'
        ]);
        $this->sync('Marketing', [
            'visits.manage',
            'visit_reports.manage',
            'report_tracking.view',
            'conditions.manage',
            'media.manage',
            'deals.manage'
        ]);
        $this->sync('Sales', [
            'visits.manage',
            'visit_reports.manage',
            'report_tracking.view',
            'media.manage',
            'deals.manage'
        ]);
    }

    private function sync(string $roleName, array $keys): void
    {
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            return;
        }
        $ids = Permission::whereIn('key', $keys)->pluck('id');
        $role->permissions()->sync($ids);
    }
}
