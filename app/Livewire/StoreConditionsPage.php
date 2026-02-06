<?php

namespace App\Livewire;

use App\Models\StoreCondition;
use App\Models\StoreVisit;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class StoreConditionsPage extends Component
{
    use WithPagination;

    public $form = [
        'visit_id' => '',
        'exterior_condition' => 'GOOD',
        'interior_condition' => 'GOOD',
        'display_quality' => '',
        'cleanliness' => '',
        'shelf_availability' => '',
        'overall_status' => 'ACTIVE'
    ];

    public $editingId = null;
    public $showForm = false;

    protected function rules()
    {
        return [
            'form.visit_id' => 'required|exists:store_visits,id',
            'form.exterior_condition' => ['required', Rule::in(['GOOD', 'FAIR', 'BAD'])],
            'form.interior_condition' => ['required', Rule::in(['GOOD', 'FAIR', 'BAD'])],
            'form.display_quality' => 'required|string|max:255',
            'form.cleanliness' => 'required|string|max:255',
            'form.shelf_availability' => 'required|string|max:255',
            'form.overall_status' => ['required', Rule::in(['ACTIVE', 'RISK', 'POTENTIAL', 'DROPPED'])]
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $condition = StoreCondition::findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'visit_id' => $condition->visit_id,
            'exterior_condition' => $condition->exterior_condition,
            'interior_condition' => $condition->interior_condition,
            'display_quality' => $condition->display_quality,
            'cleanliness' => $condition->cleanliness,
            'shelf_availability' => $condition->shelf_availability,
            'overall_status' => $condition->overall_status
        ];
        $this->showForm = true;
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            $condition = StoreCondition::findOrFail($this->editingId);
            $condition->update($data['form']);
        } else {
            StoreCondition::create($data['form']);
        }
        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(int $id)
    {
        StoreCondition::whereKey($id)->delete();
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
            'exterior_condition' => 'GOOD',
            'interior_condition' => 'GOOD',
            'display_quality' => '',
            'cleanliness' => '',
            'shelf_availability' => '',
            'overall_status' => 'ACTIVE'
        ];
        $this->editingId = null;
        $this->showForm = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.store-conditions-page', [
            'items' => StoreCondition::with('visit.store')->orderByDesc('id')->paginate(10),
            'visits' => StoreVisit::with('store')->orderByDesc('visit_at')->get()
        ])->layout('layouts.app', ['title' => 'Store Conditions']);
    }
}
