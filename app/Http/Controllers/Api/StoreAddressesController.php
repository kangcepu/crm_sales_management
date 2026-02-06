<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreAddress;
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
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

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
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        $storeAddress->update($data);

        return $storeAddress;
    }

    public function destroy(StoreAddress $storeAddress)
    {
        $storeAddress->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
