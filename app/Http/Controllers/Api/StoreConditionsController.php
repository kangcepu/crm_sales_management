<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreCondition;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreConditionsController extends Controller
{
    public function index()
    {
        return StoreCondition::with('visit.store')->orderByDesc('id')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visit_id' => 'required|exists:store_visits,id',
            'exterior_condition' => ['required', Rule::in(['GOOD', 'FAIR', 'BAD'])],
            'interior_condition' => ['required', Rule::in(['GOOD', 'FAIR', 'BAD'])],
            'display_quality' => 'required|string|max:255',
            'cleanliness' => 'required|string|max:255',
            'shelf_availability' => 'required|string|max:255',
            'overall_status' => ['required', Rule::in(['ACTIVE', 'RISK', 'POTENTIAL', 'DROPPED'])]
        ]);

        $condition = StoreCondition::create($data);

        return response()->json($condition, 201);
    }

    public function show(StoreCondition $storeCondition)
    {
        return $storeCondition->load('visit.store');
    }

    public function update(Request $request, StoreCondition $storeCondition)
    {
        $data = $request->validate([
            'visit_id' => 'required|exists:store_visits,id',
            'exterior_condition' => ['required', Rule::in(['GOOD', 'FAIR', 'BAD'])],
            'interior_condition' => ['required', Rule::in(['GOOD', 'FAIR', 'BAD'])],
            'display_quality' => 'required|string|max:255',
            'cleanliness' => 'required|string|max:255',
            'shelf_availability' => 'required|string|max:255',
            'overall_status' => ['required', Rule::in(['ACTIVE', 'RISK', 'POTENTIAL', 'DROPPED'])]
        ]);

        $storeCondition->update($data);

        return $storeCondition;
    }

    public function destroy(StoreCondition $storeCondition)
    {
        $storeCondition->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
