<?php

namespace App\Livewire;

use App\Models\Store;
use App\Models\StoreVisit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class StoreVisitsPage extends Component
{
    use WithPagination;

    public $form = [
        'store_id' => '',
        'user_id' => '',
        'visit_at' => '',
        'latitude' => '',
        'longitude' => '',
        'distance_from_store' => '',
        'visit_status' => 'ON_TIME',
        'summary' => '',
        'next_visit_plan' => ''
    ];

    public $editingId = null;
    public $showForm = false;

    protected function rules()
    {
        return [
            'form.store_id' => 'required|exists:stores,id',
            'form.user_id' => 'required|exists:users,id',
            'form.visit_at' => 'required|date',
            'form.latitude' => 'nullable|numeric',
            'form.longitude' => 'nullable|numeric',
            'form.distance_from_store' => 'nullable|numeric',
            'form.visit_status' => ['required', Rule::in(['ON_TIME', 'OUT_OF_RANGE'])],
            'form.summary' => 'nullable|string',
            'form.next_visit_plan' => 'nullable|string'
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $visit = StoreVisit::findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'store_id' => $visit->store_id,
            'user_id' => $visit->user_id,
            'visit_at' => $this->formatDateTime($visit->visit_at),
            'latitude' => $visit->latitude,
            'longitude' => $visit->longitude,
            'distance_from_store' => $visit->distance_from_store,
            'visit_status' => $visit->visit_status,
            'summary' => $visit->summary,
            'next_visit_plan' => $visit->next_visit_plan
        ];
        $this->showForm = true;
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            $visit = StoreVisit::findOrFail($this->editingId);
            $visit->update($data['form']);
        } else {
            StoreVisit::create($data['form']);
        }
        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(int $id)
    {
        StoreVisit::whereKey($id)->delete();
        session()->flash('message', 'Deleted');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->form = [
            'store_id' => '',
            'user_id' => '',
            'visit_at' => $this->formatDateTime(now()),
            'latitude' => '',
            'longitude' => '',
            'distance_from_store' => '',
            'visit_status' => 'ON_TIME',
            'summary' => '',
            'next_visit_plan' => ''
        ];
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

    public function render()
    {
        return view('livewire.store-visits-page', [
            'items' => StoreVisit::with(['store', 'user'])->orderByDesc('visit_at')->paginate(10),
            'stores' => Store::orderBy('store_name')->get(),
            'users' => User::orderBy('full_name')->get()
        ])->layout('layouts.app', ['title' => 'Store Visits']);
    }
}
