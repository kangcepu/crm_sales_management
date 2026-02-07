<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    public function countries()
    {
        return Country::orderBy('name')->get();
    }

    public function provinces(Request $request)
    {
        $query = Province::orderBy('name');
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->input('country_id'));
        }
        return $query->get();
    }

    public function cities(Request $request)
    {
        $query = City::orderBy('name');
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->input('province_id'));
        }
        return $query->get();
    }

    public function districts(Request $request)
    {
        $query = District::orderBy('name');
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->input('city_id'));
        }
        return $query->get();
    }

    public function villages(Request $request)
    {
        $query = Village::orderBy('name');
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->input('district_id'));
        }
        return $query->get();
    }
}
