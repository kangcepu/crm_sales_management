<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::setValue('site_title', 'CR Sales');
        Setting::setValue('site_description', 'Enterprise CRM System');
    }
}
