<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreStatus;
use App\Models\StoreStatusHistory;
use Illuminate\Http\Request;

class StoreStatusHistoryController extends Controller
{
    public function index()
    {
        return StoreStatusHistory::with(['store', 'changedBy', 'statusRef'])->orderByDesc('changed_at')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'store_status_id' => 'nullable|exists:store_statuses,id',
            'status' => 'nullable|string|max:50',
            'note' => 'nullable|string',
            'changed_by_user_id' => 'required|exists:users,id',
            'changed_at' => 'required|date'
        ]);

        if (!empty($data['store_status_id'])) {
            $status = StoreStatus::find($data['store_status_id']);
            $data['status'] = $status?->code;
        }
        if (empty($data['store_status_id']) && !empty($data['status'])) {
            $status = StoreStatus::where('code', $data['status'])->first();
            if ($status) {
                $data['store_status_id'] = $status->id;
            }
        }
        if (empty($data['status'])) {
            return response()->json(['message' => 'Status required'], 422);
        }

        $history = StoreStatusHistory::create($data);

        return response()->json($history, 201);
    }

    public function show(StoreStatusHistory $storeStatusHistory)
    {
        return $storeStatusHistory->load(['store', 'changedBy', 'statusRef']);
    }

    public function update(Request $request, StoreStatusHistory $storeStatusHistory)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'store_status_id' => 'nullable|exists:store_statuses,id',
            'status' => 'nullable|string|max:50',
            'note' => 'nullable|string',
            'changed_by_user_id' => 'required|exists:users,id',
            'changed_at' => 'required|date'
        ]);

        if (!empty($data['store_status_id'])) {
            $status = StoreStatus::find($data['store_status_id']);
            $data['status'] = $status?->code;
        }
        if (empty($data['store_status_id']) && !empty($data['status'])) {
            $status = StoreStatus::where('code', $data['status'])->first();
            if ($status) {
                $data['store_status_id'] = $status->id;
            }
        }
        if (empty($data['status'])) {
            return response()->json(['message' => 'Status required'], 422);
        }

        $storeStatusHistory->update($data);

        return $storeStatusHistory;
    }

    public function destroy(StoreStatusHistory $storeStatusHistory)
    {
        $storeStatusHistory->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
