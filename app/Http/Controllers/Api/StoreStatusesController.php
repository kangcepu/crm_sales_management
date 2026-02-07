<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreStatus;
use Illuminate\Http\Request;

class StoreStatusesController extends Controller
{
    public function index()
    {
        return StoreStatus::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:store_statuses,code',
            'name' => 'required|string|max:120',
            'traits' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        $status = StoreStatus::create($data);
        return response()->json($status, 201);
    }

    public function show(StoreStatus $storeStatus)
    {
        return $storeStatus;
    }

    public function update(Request $request, StoreStatus $storeStatus)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:store_statuses,code,'.$storeStatus->id,
            'name' => 'required|string|max:120',
            'traits' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        $storeStatus->update($data);
        return $storeStatus;
    }

    public function destroy(StoreStatus $storeStatus)
    {
        $storeStatus->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
