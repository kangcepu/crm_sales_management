<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $items = Setting::allKeyed()->toArray();
        if (isset($items['site_logo'])) {
            $items['site_logo'] = Setting::resolveMediaUrl($items['site_logo']);
        }
        if (isset($items['site_favicon'])) {
            $items['site_favicon'] = Setting::resolveMediaUrl($items['site_favicon']);
        }
        return response()->json($items);
    }
}
