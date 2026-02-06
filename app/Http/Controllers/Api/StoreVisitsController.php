<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreVisit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreVisitsController extends Controller
{
    public function index()
    {
        return StoreVisit::with(['store', 'user'])->orderByDesc('visit_at')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'user_id' => 'required|exists:users,id',
            'visit_at' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'distance_from_store' => 'nullable|numeric',
            'visit_status' => ['required', Rule::in(['ON_TIME', 'OUT_OF_RANGE'])],
            'summary' => 'nullable|string',
            'next_visit_plan' => 'nullable|string'
        ]);

        $visit = StoreVisit::create($data);

        return response()->json($visit, 201);
    }

    public function show(StoreVisit $storeVisit)
    {
        return $storeVisit->load(['store', 'user']);
    }

    public function update(Request $request, StoreVisit $storeVisit)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'user_id' => 'required|exists:users,id',
            'visit_at' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'distance_from_store' => 'nullable|numeric',
            'visit_status' => ['required', Rule::in(['ON_TIME', 'OUT_OF_RANGE'])],
            'summary' => 'nullable|string',
            'next_visit_plan' => 'nullable|string'
        ]);

        $storeVisit->update($data);

        return $storeVisit;
    }

    public function destroy(StoreVisit $storeVisit)
    {
        $storeVisit->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
