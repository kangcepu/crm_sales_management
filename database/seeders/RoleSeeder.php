<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'description' => 'System administrator'],
            ['name' => 'Marketing', 'description' => 'Marketing staff'],
            ['name' => 'Supervisor', 'description' => 'Field supervisor'],
            ['name' => 'Sales', 'description' => 'Sales staff']
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
