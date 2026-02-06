<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoreAssignmentsController extends Controller
{
    public function index()
    {
        return StoreAssignment::with(['store', 'user'])->orderByDesc('assigned_from')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'user_id' => 'required|exists:users,id',
            'assignment_role' => 'required|in:SALES,MARKETING,SUPERVISOR,OTHER',
            'assigned_from' => 'required|date',
            'assigned_to' => 'nullable|date|after_or_equal:assigned_from',
            'is_primary' => 'boolean'
        ]);

        $assignment = StoreAssignment::create($data);

        return response()->json($assignment, 201);
    }

    public function show($storeId, $userId, $assignedFrom)
    {
        return $this->findAssignment($storeId, $userId, $assignedFrom)->load(['store', 'user']);
    }

    public function update(Request $request, $storeId, $userId, $assignedFrom)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'user_id' => 'required|exists:users,id',
            'assignment_role' => 'required|in:SALES,MARKETING,SUPERVISOR,OTHER',
            'assigned_from' => 'required|date',
            'assigned_to' => 'nullable|date|after_or_equal:assigned_from',
            'is_primary' => 'boolean'
        ]);

        StoreAssignment::where('store_id', $storeId)
            ->where('user_id', $userId)
            ->where('assigned_from', $this->parseAssignedFrom($assignedFrom))
            ->update($data);

        return $this->findAssignment($data['store_id'], $data['user_id'], $data['assigned_from']);
    }

    public function destroy($storeId, $userId, $assignedFrom)
    {
        StoreAssignment::where('store_id', $storeId)
            ->where('user_id', $userId)
            ->where('assigned_from', $this->parseAssignedFrom($assignedFrom))
            ->delete();

        return response()->json(['message' => 'Deleted']);
    }

    private function findAssignment($storeId, $userId, $assignedFrom)
    {
        return StoreAssignment::where('store_id', $storeId)
            ->where('user_id', $userId)
            ->where('assigned_from', $this->parseAssignedFrom($assignedFrom))
            ->firstOrFail();
    }

    private function parseAssignedFrom($value): string
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
