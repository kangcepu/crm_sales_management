<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class StoresController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::with(['address', 'area'])->orderByDesc('created_at');
        $user = $request->user();

        if ($user && !$user->hasPermission('stores.manage')) {
            $storeIds = $user->storeAssignments()->pluck('store_id');
            $query->whereIn('id', $storeIds);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'erp_store_id' => 'nullable|string|max:255',
            'area_id' => 'nullable|exists:areas,id',
            'store_code' => 'required|string|max:50|unique:stores,store_code',
            'store_name' => 'required|string|max:255',
            'store_type' => ['required', Rule::in(['CONSIGNMENT', 'REGULAR'])],
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'is_active' => 'boolean',
            'address' => 'required|array',
            'address.address' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.province' => 'required|string|max:255',
            'address.latitude' => 'nullable|numeric',
            'address.longitude' => 'nullable|numeric'
        ]);

        $store = Store::create(Arr::except($data, ['address']));
        $store->address()->create($data['address']);

        return response()->json($store->load(['address', 'area']), 201);
    }

    public function show(Request $request, Store $store)
    {
        $user = $request->user();
        if ($user && !$user->hasPermission('stores.manage')) {
            $assigned = $user->storeAssignments()->where('store_id', $store->id)->exists();
            if (!$assigned) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }
        return $store->load(['address', 'area']);
    }

    public function update(Request $request, Store $store)
    {
        $data = $request->validate([
            'erp_store_id' => 'nullable|string|max:255',
            'area_id' => 'nullable|exists:areas,id',
            'store_code' => ['required', 'string', 'max:50', Rule::unique('stores', 'store_code')->ignore($store->id)],
            'store_name' => 'required|string|max:255',
            'store_type' => ['required', Rule::in(['CONSIGNMENT', 'REGULAR'])],
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'is_active' => 'boolean',
            'address' => 'required|array',
            'address.address' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.province' => 'required|string|max:255',
            'address.latitude' => 'nullable|numeric',
            'address.longitude' => 'nullable|numeric'
        ]);

        $store->update(Arr::except($data, ['address']));
        if ($store->address) {
            $store->address->update($data['address']);
        } else {
            $store->address()->create($data['address']);
        }

        return $store->load(['address', 'area']);
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
