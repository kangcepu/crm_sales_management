<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GooglePlacesService
{
    public function autocomplete(string $input, ?string $country = null): array
    {
        $key = config('services.google.maps_key');
        if (!$key) {
            return [];
        }

        $payload = [
            'input' => $input,
            'key' => $key,
            'language' => config('services.google.maps_language'),
            'region' => config('services.google.maps_region'),
        ];

        $country = $country ?: config('services.google.maps_country');
        if ($country) {
            $payload['components'] = 'country:' . $country;
        }

        $response = Http::retry(2, 200)->get('https://maps.googleapis.com/maps/api/place/autocomplete/json', $payload);
        if (!$response->ok()) {
            return [];
        }

        $data = $response->json();
        if (!is_array($data) || ($data['status'] ?? '') !== 'OK') {
            return [];
        }

        return collect($data['predictions'] ?? [])
            ->map(function (array $prediction) {
                return [
                    'place_id' => $prediction['place_id'] ?? null,
                    'description' => $prediction['description'] ?? '',
                ];
            })
            ->filter(fn ($item) => !empty($item['place_id']))
            ->values()
            ->all();
    }

    public function details(string $placeId): ?array
    {
        $key = config('services.google.maps_key');
        if (!$key) {
            return null;
        }

        $payload = [
            'place_id' => $placeId,
            'key' => $key,
            'language' => config('services.google.maps_language'),
            'fields' => 'address_component,formatted_address,geometry',
        ];

        $response = Http::retry(2, 200)->get('https://maps.googleapis.com/maps/api/place/details/json', $payload);
        if (!$response->ok()) {
            return null;
        }

        $data = $response->json();
        if (!is_array($data) || ($data['status'] ?? '') !== 'OK') {
            return null;
        }

        $result = $data['result'] ?? [];
        $components = $this->mapComponents($result['address_components'] ?? []);
        $location = $result['geometry']['location'] ?? [];

        return [
            'formatted_address' => $result['formatted_address'] ?? '',
            'lat' => $location['lat'] ?? null,
            'lng' => $location['lng'] ?? null,
            'components' => [
                'country' => $components['country'] ?? null,
                'province' => $components['administrative_area_level_1'] ?? null,
                'city' => $components['administrative_area_level_2'] ?? ($components['locality'] ?? null),
                'district' => $components['administrative_area_level_3'] ?? ($components['sublocality_level_1'] ?? null),
                'village' => $components['administrative_area_level_4'] ?? ($components['sublocality_level_2'] ?? ($components['neighborhood'] ?? null)),
                'postal_code' => $components['postal_code'] ?? null,
            ],
        ];
    }

    private function mapComponents(array $components): array
    {
        $mapped = [];
        foreach ($components as $component) {
            $types = $component['types'] ?? [];
            foreach ($types as $type) {
                $mapped[$type] = [
                    'name' => $component['long_name'] ?? '',
                    'code' => $component['short_name'] ?? '',
                ];
            }
        }
        return $mapped;
    }
}
