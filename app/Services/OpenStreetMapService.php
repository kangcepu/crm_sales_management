<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenStreetMapService
{
    public function search(string $query, int $limit = 6): array
    {
        $query = trim($query);
        if ($query === '') {
            return [];
        }

        $params = [
            'q' => $query,
            'format' => 'jsonv2',
            'addressdetails' => 1,
            'limit' => $limit,
        ];

        $email = config('services.nominatim.email');
        if ($email) {
            $params['email'] = $email;
        }

        $response = $this->client()->get($this->baseUrl() . '/search', $params);
        if (!$response->ok()) {
            return [];
        }

        $items = $response->json();
        if (!is_array($items)) {
            return [];
        }

        return collect($items)->map(function (array $item) {
            return [
                'label' => $item['display_name'] ?? '',
                'lat' => isset($item['lat']) ? (float) $item['lat'] : null,
                'lng' => isset($item['lon']) ? (float) $item['lon'] : null,
            ];
        })->filter(fn ($item) => !empty($item['label']) && $item['lat'] !== null && $item['lng'] !== null)
            ->values()
            ->all();
    }

    public function reverse(float $lat, float $lng): ?array
    {
        $params = [
            'lat' => $lat,
            'lon' => $lng,
            'format' => 'jsonv2',
            'addressdetails' => 1,
        ];

        $email = config('services.nominatim.email');
        if ($email) {
            $params['email'] = $email;
        }

        $response = $this->client()->get($this->baseUrl() . '/reverse', $params);
        if (!$response->ok()) {
            return null;
        }

        $data = $response->json();
        if (!is_array($data)) {
            return null;
        }

        return [
            'display_name' => $data['display_name'] ?? '',
            'address' => $data['address'] ?? [],
            'lat' => isset($data['lat']) ? (float) $data['lat'] : $lat,
            'lng' => isset($data['lon']) ? (float) $data['lon'] : $lng,
        ];
    }

    private function client()
    {
        $lang = config('services.nominatim.language') ?: 'id';
        $agent = trim(config('app.name') . ' ' . config('app.url'));

        return Http::retry(2, 200)->withHeaders([
            'User-Agent' => $agent,
            'Accept-Language' => $lang,
        ]);
    }

    private function baseUrl(): string
    {
        return rtrim(config('services.nominatim.base_url'), '/');
    }
}
