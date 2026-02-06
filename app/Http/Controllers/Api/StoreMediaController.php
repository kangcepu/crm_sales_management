<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\StoreMedia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreMediaController extends Controller
{
    public function index()
    {
        return StoreMedia::with('visit.store')->orderByDesc('id')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visit_id' => 'required|exists:store_visits,id',
            'media_type' => ['required_without:files', Rule::in(['PHOTO', 'VIDEO'])],
            'media_url' => 'required_without:files|string|max:255',
            'caption' => 'nullable|string|max:255',
            'taken_at' => 'nullable|date',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpg,jpeg,png,webp,mp4,mov|max:20480'
        ]);

        $files = $request->file('files', []);
        $userId = $request->user()?->id;

        if (is_array($files) && count($files) > 0) {
            $items = [];
            foreach ($files as $file) {
                $path = $file->store('store-media', 'media');
                $url = $path;
                $mediaType = $this->resolveMediaType($file->getMimeType(), $data['media_type'] ?? null);
                $item = StoreMedia::create([
                    'visit_id' => $data['visit_id'],
                    'media_type' => $mediaType,
                    'media_url' => $url,
                    'caption' => $data['caption'] ?? null,
                    'taken_at' => $data['taken_at'] ?? null
                ]);
                if ($userId) {
                    ActivityLog::record($userId, 'store_media', $item->id, 'created', [
                        'visit_id' => $data['visit_id'],
                        'media_url' => $url
                    ]);
                }
                $items[] = $item->load('visit.store');
            }

            return response()->json($items, 201);
        }

        $media = StoreMedia::create($data);
        if ($userId) {
            ActivityLog::record($userId, 'store_media', $media->id, 'created', [
                'visit_id' => $data['visit_id'],
                'media_url' => $media->media_url
            ]);
        }

        return response()->json($media->load('visit.store'), 201);
    }

    public function show(StoreMedia $storeMedia)
    {
        return $storeMedia->load('visit.store');
    }

    public function update(Request $request, StoreMedia $storeMedia)
    {
        $data = $request->validate([
            'visit_id' => 'sometimes|exists:store_visits,id',
            'media_type' => ['sometimes', Rule::in(['PHOTO', 'VIDEO'])],
            'media_url' => 'sometimes|string|max:255',
            'caption' => 'sometimes|nullable|string|max:255',
            'taken_at' => 'sometimes|nullable|date',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov|max:20480'
        ]);

        $payload = $data;
        unset($payload['file']);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('store-media', 'media');
            $payload['media_url'] = $path;
            $payload['media_type'] = $this->resolveMediaType($file->getMimeType(), $payload['media_type'] ?? $storeMedia->media_type);
        }

        $before = $storeMedia->getOriginal();
        $storeMedia->fill($payload);
        $dirty = $storeMedia->getDirty();
        if (!empty($dirty)) {
            $storeMedia->save();
            $userId = $request->user()?->id;
            if ($userId) {
                ActivityLog::record($userId, 'store_media', $storeMedia->id, 'updated', $this->formatChanges($before, $dirty));
            }
        }

        return $storeMedia->load('visit.store');
    }

    public function destroy(StoreMedia $storeMedia)
    {
        $storeMedia->delete();
        $userId = request()->user()?->id;
        if ($userId) {
            ActivityLog::record($userId, 'store_media', $storeMedia->id, 'deleted', null);
        }
        return response()->json(['message' => 'Deleted']);
    }

    private function resolveMediaType(?string $mime, ?string $fallback): string
    {
        if ($mime && str_starts_with($mime, 'video/')) {
            return 'VIDEO';
        }
        if ($mime && str_starts_with($mime, 'image/')) {
            return 'PHOTO';
        }
        return $fallback ?? 'PHOTO';
    }

    private function formatChanges(array $before, array $dirty): array
    {
        $changes = [];
        foreach ($dirty as $key => $value) {
            $changes[$key] = [
                'from' => $before[$key] ?? null,
                'to' => $value
            ];
        }
        return $changes;
    }
}
