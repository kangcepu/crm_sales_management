<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\StoreVisitReport;
use App\Models\StoreVisitReportMedia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreVisitReportsController extends Controller
{
    public function index()
    {
        return StoreVisitReport::with(['visit.store', 'media'])->orderByDesc('id')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visit_id' => 'required|exists:store_visits,id',
            'consignment_qty' => 'required|integer|min:0',
            'consignment_value' => 'required|numeric|min:0',
            'sales_qty' => 'required|integer|min:0',
            'sales_value' => 'required|numeric|min:0',
            'payment_status' => ['required', Rule::in(['PAID', 'PENDING'])],
            'competitor_activity' => 'nullable|string',
            'notes' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpg,jpeg,png,webp,mp4,mov|max:20480'
        ]);

        $payload = $data;
        unset($payload['files']);

        $report = StoreVisitReport::create($payload);
        $userId = $request->user()?->id;
        if ($userId) {
            ActivityLog::record($userId, 'store_visit_report', $report->id, 'created', [
                'visit_id' => $payload['visit_id']
            ]);
        }

        $files = $request->file('files', []);
        $mediaItems = $this->storeReportMedia($report, $files);
        if ($userId && count($mediaItems) > 0) {
            ActivityLog::record($userId, 'store_visit_report', $report->id, 'media_added', [
                'count' => count($mediaItems),
                'media_urls' => array_values(array_map(fn ($item) => $item->media_url, $mediaItems))
            ]);
        }

        return response()->json($report->load(['visit.store', 'media']), 201);
    }

    public function show(StoreVisitReport $storeVisitReport)
    {
        return $storeVisitReport->load(['visit.store', 'media']);
    }

    public function update(Request $request, StoreVisitReport $storeVisitReport)
    {
        $data = $request->validate([
            'visit_id' => 'sometimes|exists:store_visits,id',
            'consignment_qty' => 'sometimes|integer|min:0',
            'consignment_value' => 'sometimes|numeric|min:0',
            'sales_qty' => 'sometimes|integer|min:0',
            'sales_value' => 'sometimes|numeric|min:0',
            'payment_status' => ['sometimes', Rule::in(['PAID', 'PENDING'])],
            'competitor_activity' => 'sometimes|nullable|string',
            'notes' => 'sometimes|nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpg,jpeg,png,webp,mp4,mov|max:20480'
        ]);

        $payload = $data;
        unset($payload['files']);

        $before = $storeVisitReport->getOriginal();
        $storeVisitReport->fill($payload);
        $dirty = $storeVisitReport->getDirty();
        $userId = $request->user()?->id;
        if (!empty($dirty)) {
            $storeVisitReport->save();
            if ($userId) {
                ActivityLog::record($userId, 'store_visit_report', $storeVisitReport->id, 'updated', $this->formatChanges($before, $dirty));
            }
        }

        $files = $request->file('files', []);
        $mediaItems = $this->storeReportMedia($storeVisitReport, $files);
        if ($userId && count($mediaItems) > 0) {
            ActivityLog::record($userId, 'store_visit_report', $storeVisitReport->id, 'media_added', [
                'count' => count($mediaItems),
                'media_urls' => array_values(array_map(fn ($item) => $item->media_url, $mediaItems))
            ]);
        }

        return $storeVisitReport->load(['visit.store', 'media']);
    }

    public function destroy(StoreVisitReport $storeVisitReport)
    {
        $storeVisitReport->delete();
        $userId = request()->user()?->id;
        if ($userId) {
            ActivityLog::record($userId, 'store_visit_report', $storeVisitReport->id, 'deleted', null);
        }
        return response()->json(['message' => 'Deleted']);
    }

    private function storeReportMedia(StoreVisitReport $report, array $files): array
    {
        $items = [];
        if (!is_array($files)) {
            return $items;
        }

        foreach ($files as $file) {
            $path = $file->store('report-media', 'media');
            $url = url('media/'.$path);
            $mediaType = $this->resolveMediaType($file->getMimeType(), null);
            $items[] = StoreVisitReportMedia::create([
                'report_id' => $report->id,
                'media_type' => $mediaType,
                'media_url' => $url,
                'caption' => null,
                'taken_at' => now()
            ]);
        }

        return $items;
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
