<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreAddress;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class StoreAddressesController extends Controller
{
    public function index()
    {
        return StoreAddress::with('store')->orderByDesc('id')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'country_id' => 'nullable|exists:countries,id',
            'province_id' => 'nullable|exists:provinces,id',
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'village_id' => 'nullable|exists:villages,id',
            'address' => 'required|string|max:255',
            'city' => 'required_without:city_id|string|max:255',
            'province' => 'required_without:province_id|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        if (!empty($data['city_id'])) {
            $city = City::find($data['city_id']);
            if ($city) {
                $data['city'] = $city->name;
            }
        }
        if (!empty($data['province_id'])) {
            $province = Province::find($data['province_id']);
            if ($province) {
                $data['province'] = $province->name;
            }
        }

        $address = StoreAddress::create($data);

        return response()->json($address, 201);
    }

    public function show(StoreAddress $storeAddress)
    {
        return $storeAddress->load('store');
    }

    public function update(Request $request, StoreAddress $storeAddress)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'country_id' => 'nullable|exists:countries,id',
            'province_id' => 'nullable|exists:provinces,id',
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'village_id' => 'nullable|exists:villages,id',
            'address' => 'required|string|max:255',
            'city' => 'required_without:city_id|string|max:255',
            'province' => 'required_without:province_id|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        if (!empty($data['city_id'])) {
            $city = City::find($data['city_id']);
            if ($city) {
                $data['city'] = $city->name;
            }
        }
        if (!empty($data['province_id'])) {
            $province = Province::find($data['province_id']);
            if ($province) {
                $data['province'] = $province->name;
            }
        }

        $storeAddress->update($data);

        return $storeAddress;
    }

    public function destroy(StoreAddress $storeAddress)
    {
        $storeAddress->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
