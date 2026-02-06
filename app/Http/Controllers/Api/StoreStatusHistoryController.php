<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreStatusHistoryController extends Controller
{
    public function index()
    {
        return StoreStatusHistory::with(['store', 'changedBy'])->orderByDesc('changed_at')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'status' => ['required', Rule::in(['ACTIVE', 'INACTIVE', 'CLOSED'])],
            'note' => 'nullable|string',
            'changed_by_user_id' => 'required|exists:users,id',
            'changed_at' => 'required|date'
        ]);

        $history = StoreStatusHistory::create($data);

        return response()->json($history, 201);
    }

    public function show(StoreStatusHistory $storeStatusHistory)
    {
        return $storeStatusHistory->load(['store', 'changedBy']);
    }

    public function update(Request $request, StoreStatusHistory $storeStatusHistory)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'status' => ['required', Rule::in(['ACTIVE', 'INACTIVE', 'CLOSED'])],
            'note' => 'nullable|string',
            'changed_by_user_id' => 'required|exists:users,id',
            'changed_at' => 'required|date'
        ]);

        $storeStatusHistory->update($data);

        return $storeStatusHistory;
    }

    public function destroy(StoreStatusHistory $storeStatusHistory)
    {
        $storeStatusHistory->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
