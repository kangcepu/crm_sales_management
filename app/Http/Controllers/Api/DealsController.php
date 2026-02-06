<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DealsController extends Controller
{
    public function index()
    {
        return Deal::with(['store', 'owner'])->orderByDesc('created_at')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'owner_user_id' => 'required|exists:users,id',
            'deal_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'stage' => ['required', Rule::in(['PROSPECT', 'NEGOTIATION', 'WON', 'LOST'])],
            'expected_close_date' => 'nullable|date'
        ]);

        $deal = Deal::create($data);

        return response()->json($deal, 201);
    }

    public function show(Deal $deal)
    {
        return $deal->load(['store', 'owner']);
    }

    public function update(Request $request, Deal $deal)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'owner_user_id' => 'required|exists:users,id',
            'deal_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'stage' => ['required', Rule::in(['PROSPECT', 'NEGOTIATION', 'WON', 'LOST'])],
            'expected_close_date' => 'nullable|date'
        ]);

        $deal->update($data);

        return $deal;
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
