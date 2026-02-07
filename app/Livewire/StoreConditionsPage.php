<?php

namespace App\Livewire;

use App\Models\StoreCondition;
use App\Models\StoreConditionType;
use App\Models\StoreVisit;
use Livewire\Component;
use Livewire\WithPagination;

class StoreConditionsPage extends Component
{
    use WithPagination;

    public $form = [
        'visit_id' => '',
        'condition_type_id' => '',
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
            'form.condition_type_id' => 'nullable|exists:store_condition_types,id',
            'form.exterior_condition' => 'required|string|max:50',
            'form.interior_condition' => 'required|string|max:50',
            'form.display_quality' => 'required|string|max:255',
            'form.cleanliness' => 'required|string|max:255',
            'form.shelf_availability' => 'required|string|max:255',
            'form.overall_status' => 'nullable|string|max:50'
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
            'condition_type_id' => $condition->condition_type_id,
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
        if (!empty($data['form']['condition_type_id'])) {
            $type = StoreConditionType::find($data['form']['condition_type_id']);
            $data['form']['overall_status'] = $type?->code;
        }
        if (empty($data['form']['condition_type_id']) && !empty($data['form']['overall_status'])) {
            $type = StoreConditionType::where('code', $data['form']['overall_status'])->first();
            if ($type) {
                $data['form']['condition_type_id'] = $type->id;
            }
        }
        if (empty($data['form']['overall_status'])) {
            $this->addError('form.overall_status', 'Overall status required');
            return;
        }
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
            'condition_type_id' => '',
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

    public function updatedFormConditionTypeId()
    {
        if (!empty($this->form['condition_type_id'])) {
            $type = StoreConditionType::find($this->form['condition_type_id']);
            if ($type) {
                $this->form['overall_status'] = $type->code;
            }
        }
    }

    public function render()
    {
        return view('livewire.store-conditions-page', [
            'items' => StoreCondition::with(['visit.store', 'conditionType'])->orderByDesc('id')->paginate(10),
            'types' => StoreConditionType::orderBy('name')->get(),
            'visits' => StoreVisit::with('store')->orderByDesc('visit_at')->get()
        ])->layout('layouts.app', ['title' => 'Store Conditions']);
    }
}
