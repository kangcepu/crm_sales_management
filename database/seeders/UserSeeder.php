<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $marketingRole = Role::where('name', 'Marketing')->first();
        $supervisorRole = Role::where('name', 'Supervisor')->first();
        $salesRole = Role::where('name', 'Sales')->first();

        $admin = User::create([
            'email' => 'admin@crsales.test',
            'full_name' => 'Admin User',
            'phone' => '555-0100',
            'password_hash' => Hash::make('password'),
            'is_active' => true
        ]);
        $admin->roles()->sync([$adminRole->id]);

        $marketing = User::create([
            'email' => 'marketing@crsales.test',
            'full_name' => 'Marketing User',
            'phone' => '555-0101',
            'password_hash' => Hash::make('password'),
            'is_active' => true
        ]);
        $marketing->roles()->sync([$marketingRole->id]);

        $supervisor = User::create([
            'email' => 'supervisor@crsales.test',
            'full_name' => 'Supervisor User',
            'phone' => '555-0102',
            'password_hash' => Hash::make('password'),
            'is_active' => true
        ]);
        $supervisor->roles()->sync([$supervisorRole->id]);

        if ($salesRole) {
            $sales = User::create([
                'email' => 'sales@crsales.test',
                'full_name' => 'Sales User',
                'phone' => '555-0103',
                'password_hash' => Hash::make('password'),
                'is_active' => true
            ]);
            $sales->roles()->sync([$salesRole->id]);
        }
    }
}
