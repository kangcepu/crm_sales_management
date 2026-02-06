<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreCondition;
use App\Models\StoreConditionType;
use Illuminate\Http\Request;

class StoreConditionsController extends Controller
{
    public function index()
    {
        return StoreCondition::with(['visit.store', 'conditionType'])->orderByDesc('id')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visit_id' => 'required|exists:store_visits,id',
            'condition_type_id' => 'nullable|exists:store_condition_types,id',
            'exterior_condition' => 'required|string|max:50',
            'interior_condition' => 'required|string|max:50',
            'display_quality' => 'required|string|max:255',
            'cleanliness' => 'required|string|max:255',
            'shelf_availability' => 'required|string|max:255',
            'overall_status' => 'nullable|string|max:50'
        ]);

        if (!empty($data['condition_type_id'])) {
            $type = StoreConditionType::find($data['condition_type_id']);
            $data['overall_status'] = $type?->code;
        }
        if (empty($data['overall_status'])) {
            return response()->json(['message' => 'Overall status required'], 422);
        }

        $condition = StoreCondition::create($data);

        return response()->json($condition, 201);
    }

    public function show(StoreCondition $storeCondition)
    {
        return $storeCondition->load(['visit.store', 'conditionType']);
    }

    public function update(Request $request, StoreCondition $storeCondition)
    {
        $data = $request->validate([
            'visit_id' => 'required|exists:store_visits,id',
            'condition_type_id' => 'nullable|exists:store_condition_types,id',
            'exterior_condition' => 'required|string|max:50',
            'interior_condition' => 'required|string|max:50',
            'display_quality' => 'required|string|max:255',
            'cleanliness' => 'required|string|max:255',
            'shelf_availability' => 'required|string|max:255',
            'overall_status' => 'nullable|string|max:50'
        ]);

        if (!empty($data['condition_type_id'])) {
            $type = StoreConditionType::find($data['condition_type_id']);
            $data['overall_status'] = $type?->code;
        }
        if (empty($data['overall_status'])) {
            return response()->json(['message' => 'Overall status required'], 422);
        }

        $storeCondition->update($data);

        return $storeCondition;
    }

    public function destroy(StoreCondition $storeCondition)
    {
        $storeCondition->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
