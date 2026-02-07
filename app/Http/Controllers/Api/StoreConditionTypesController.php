<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreConditionType;
use Illuminate\Http\Request;

class StoreConditionTypesController extends Controller
{
    public function index()
    {
        return StoreConditionType::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:store_condition_types,code',
            'name' => 'required|string|max:120',
            'traits' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        $type = StoreConditionType::create($data);
        return response()->json($type, 201);
    }

    public function show(StoreConditionType $storeConditionType)
    {
        return $storeConditionType;
    }

    public function update(Request $request, StoreConditionType $storeConditionType)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:store_condition_types,code,'.$storeConditionType->id,
            'name' => 'required|string|max:120',
            'traits' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        $storeConditionType->update($data);
        return $storeConditionType;
    }

    public function destroy(StoreConditionType $storeConditionType)
    {
        $storeConditionType->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
