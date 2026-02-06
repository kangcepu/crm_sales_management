<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\StoreVisit;
use App\Models\StoreVisitReport;
use App\Models\StoreVisitReportMedia;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class StoreVisitReportsPage extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $form = [
        'visit_id' => '',
        'consignment_qty' => 0,
        'consignment_value' => 0,
        'sales_qty' => 0,
        'sales_value' => 0,
        'payment_status' => 'PENDING',
        'competitor_activity' => '',
        'notes' => ''
    ];

    public $uploads = [];
    public $editingId = null;
    public $showForm = false;

    protected function rules()
    {
        return [
            'form.visit_id' => 'required|exists:store_visits,id',
            'form.consignment_qty' => 'required|integer|min:0',
            'form.consignment_value' => 'required|numeric|min:0',
            'form.sales_qty' => 'required|integer|min:0',
            'form.sales_value' => 'required|numeric|min:0',
            'form.payment_status' => ['required', Rule::in(['PAID', 'PENDING'])],
            'form.competitor_activity' => 'nullable|string',
            'form.notes' => 'nullable|string',
            'uploads' => 'nullable|array',
            'uploads.*' => 'file|mimes:jpg,jpeg,png,webp,mp4,mov|max:20480'
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $report = StoreVisitReport::findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'visit_id' => $report->visit_id,
            'consignment_qty' => $report->consignment_qty,
            'consignment_value' => $report->consignment_value,
            'sales_qty' => $report->sales_qty,
            'sales_value' => $report->sales_value,
            'payment_status' => $report->payment_status,
            'competitor_activity' => $report->competitor_activity,
            'notes' => $report->notes
        ];
        $this->uploads = [];
        $this->showForm = true;
    }

    public function save()
    {
        $data = $this->validate();
        $payload = $data['form'];
        $userId = auth()->id();

        if ($this->editingId) {
            $report = StoreVisitReport::findOrFail($this->editingId);
            $before = $report->getOriginal();
            $report->fill($payload);
            $dirty = $report->getDirty();
            if (!empty($dirty)) {
                $report->save();
                if ($userId) {
                    ActivityLog::record($userId, 'store_visit_report', $report->id, 'updated', $this->formatChanges($before, $dirty));
                }
            }
            $mediaItems = $this->attachMedia($report, $this->uploads);
            if ($userId && count($mediaItems) > 0) {
                ActivityLog::record($userId, 'store_visit_report', $report->id, 'media_added', [
                    'count' => count($mediaItems),
                    'media_urls' => array_values(array_map(fn ($item) => $item->media_url, $mediaItems))
                ]);
            }
        } else {
            $report = StoreVisitReport::create($payload);
            if ($userId) {
                ActivityLog::record($userId, 'store_visit_report', $report->id, 'created', [
                    'visit_id' => $payload['visit_id']
                ]);
            }
            $mediaItems = $this->attachMedia($report, $this->uploads);
            if ($userId && count($mediaItems) > 0) {
                ActivityLog::record($userId, 'store_visit_report', $report->id, 'media_added', [
                    'count' => count($mediaItems),
                    'media_urls' => array_values(array_map(fn ($item) => $item->media_url, $mediaItems))
                ]);
            }
        }

        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(int $id)
    {
        StoreVisitReport::whereKey($id)->delete();
        $userId = auth()->id();
        if ($userId) {
            ActivityLog::record($userId, 'store_visit_report', $id, 'deleted', null);
        }
        session()->flash('message', 'Deleted');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function attachMedia(StoreVisitReport $report, array $files): array
    {
        $items = [];
        if (!is_array($files)) {
            return $items;
        }
        foreach ($files as $file) {
            $path = $file->store('report-media', 'media');
            $url = $path;
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

    private function resetForm()
    {
        $this->form = [
            'visit_id' => '',
            'consignment_qty' => 0,
            'consignment_value' => 0,
            'sales_qty' => 0,
            'sales_value' => 0,
            'payment_status' => 'PENDING',
            'competitor_activity' => '',
            'notes' => ''
        ];
        $this->uploads = [];
        $this->editingId = null;
        $this->showForm = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.store-visit-reports-page', [
            'items' => StoreVisitReport::with(['visit.store', 'media'])->orderByDesc('id')->paginate(10),
            'visits' => StoreVisit::with('store')->orderByDesc('visit_at')->get()
        ])->layout('layouts.app', ['title' => 'Visit Reports']);
    }
}
