<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\StoreMedia;
use App\Models\StoreVisit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class StoreMediaPage extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $form = [
        'visit_id' => '',
        'media_type' => 'PHOTO',
        'caption' => '',
        'taken_at' => ''
    ];

    public $uploads = [];
    public $upload = null;
    public $editingId = null;
    public $showForm = false;

    protected function rules()
    {
        return [
            'form.visit_id' => 'required|exists:store_visits,id',
            'form.media_type' => ['required', Rule::in(['PHOTO', 'VIDEO'])],
            'form.caption' => 'nullable|string|max:255',
            'form.taken_at' => 'nullable|date',
            'uploads' => $this->editingId ? 'nullable|array' : 'required|array|min:1',
            'uploads.*' => 'file|mimes:jpg,jpeg,png,webp,mp4,mov|max:20480',
            'upload' => $this->editingId ? 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov|max:20480' : 'nullable'
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $media = StoreMedia::findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'visit_id' => $media->visit_id,
            'media_type' => $media->media_type,
            'caption' => $media->caption,
            'taken_at' => $this->formatDateTime($media->taken_at)
        ];
        $this->upload = null;
        $this->uploads = [];
        $this->showForm = true;
    }

    public function save()
    {
        $data = $this->validate();
        $payload = $data['form'];
        $userId = auth()->id();

        if ($this->editingId) {
            $media = StoreMedia::findOrFail($this->editingId);
            $before = $media->getOriginal();
            if ($this->upload) {
                $path = $this->upload->store('store-media', 'media');
                $payload['media_url'] = url('media/'.$path);
                $payload['media_type'] = $this->resolveMediaType($this->upload->getMimeType(), $payload['media_type'] ?? null);
            }
            $media->fill($payload);
            $dirty = $media->getDirty();
            if (!empty($dirty)) {
                $media->save();
                if ($userId) {
                    ActivityLog::record($userId, 'store_media', $media->id, 'updated', $this->formatChanges($before, $dirty));
                }
            }
        } else {
            foreach ($this->uploads as $file) {
                $path = $file->store('store-media', 'media');
                $url = url('media/'.$path);
                $mediaType = $this->resolveMediaType($file->getMimeType(), $payload['media_type'] ?? null);
                $media = StoreMedia::create([
                    'visit_id' => $payload['visit_id'],
                    'media_type' => $mediaType,
                    'media_url' => $url,
                    'caption' => $payload['caption'] ?? null,
                    'taken_at' => $payload['taken_at'] ?? null
                ]);
                if ($userId) {
                    ActivityLog::record($userId, 'store_media', $media->id, 'created', [
                        'visit_id' => $payload['visit_id'],
                        'media_url' => $url
                    ]);
                }
            }
        }

        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(int $id)
    {
        StoreMedia::whereKey($id)->delete();
        $userId = auth()->id();
        if ($userId) {
            ActivityLog::record($userId, 'store_media', $id, 'deleted', null);
        }
        session()->flash('message', 'Deleted');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->form = [
            'visit_id' => '',
            'media_type' => 'PHOTO',
            'caption' => '',
            'taken_at' => ''
        ];
        $this->uploads = [];
        $this->upload = null;
        $this->editingId = null;
        $this->showForm = false;
        $this->resetValidation();
    }

    private function formatDateTime($value): string
    {
        if (!$value) {
            return '';
        }
        return Carbon::parse($value)->format('Y-m-d\TH:i');
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

    public function render()
    {
        $groups = StoreMedia::select('visit_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('visit_id')
            ->orderByDesc('latest_id')
            ->paginate(10);

        $mediaByVisit = StoreMedia::with('visit.store')
            ->whereIn('visit_id', $groups->pluck('visit_id'))
            ->orderByDesc('id')
            ->get()
            ->groupBy('visit_id');

        return view('livewire.store-media-page', [
            'groups' => $groups,
            'mediaByVisit' => $mediaByVisit,
            'visits' => StoreVisit::with('store')->orderByDesc('visit_at')->get()
        ])->layout('layouts.app', ['title' => 'Store Media']);
    }
}
