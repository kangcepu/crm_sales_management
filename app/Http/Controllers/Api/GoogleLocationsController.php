<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GooglePlacesService;
use Illuminate\Http\Request;

class GoogleLocationsController extends Controller
{
    public function autocomplete(Request $request, GooglePlacesService $service)
    {
        $data = $request->validate([
            'input' => 'required|string|min:3',
            'country' => 'nullable|string|max:10',
        ]);

        return response()->json([
            'items' => $service->autocomplete($data['input'], $data['country'] ?? null),
        ]);
    }

    public function details(Request $request, GooglePlacesService $service)
    {
        $data = $request->validate([
            'place_id' => 'required|string',
        ]);

        $details = $service->details($data['place_id']);

        return response()->json([
            'details' => $details,
        ]);
    }
}
