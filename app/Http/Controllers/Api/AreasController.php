<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AreasController extends Controller
{
    public function index()
    {
        return Area::orderBy('area_name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'area_code' => 'required|string|max:50|unique:areas,area_code',
            'area_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $area = Area::create($data);
        return response()->json($area, 201);
    }

    public function show(Area $area)
    {
        return $area;
    }

    public function update(Request $request, Area $area)
    {
        $data = $request->validate([
            'area_code' => ['required', 'string', 'max:50', Rule::unique('areas', 'area_code')->ignore($area->id)],
            'area_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $area->update($data);
        return $area;
    }

    public function destroy(Area $area)
    {
        $area->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
