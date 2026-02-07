<?php

namespace App\Livewire;

use App\Models\StoreConditionType;
use Livewire\Component;
use Livewire\WithPagination;

class StoreConditionTypesPage extends Component
{
    use WithPagination;

    public $form = [
        'code' => '',
        'name' => '',
        'traits' => '',
        'color' => '',
        'is_active' => true
    ];

    public $editingId = null;
    public $showForm = false;

    protected function rules()
    {
        return [
            'form.code' => 'required|string|max:50|unique:store_condition_types,code,'.$this->editingId,
            'form.name' => 'required|string|max:120',
            'form.traits' => 'nullable|string',
            'form.color' => 'nullable|string|max:20',
            'form.is_active' => 'boolean'
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $type = StoreConditionType::findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'code' => $type->code,
            'name' => $type->name,
            'traits' => $type->traits,
            'color' => $type->color,
            'is_active' => $type->is_active
        ];
        $this->showForm = true;
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            $type = StoreConditionType::findOrFail($this->editingId);
            $type->update($data['form']);
        } else {
            StoreConditionType::create($data['form']);
        }
        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(int $id)
    {
        StoreConditionType::whereKey($id)->delete();
        session()->flash('message', 'Deleted');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->form = [
            'code' => '',
            'name' => '',
            'traits' => '',
            'color' => '',
            'is_active' => true
        ];
        $this->editingId = null;
        $this->showForm = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.store-condition-types-page', [
            'items' => StoreConditionType::orderBy('name')->paginate(10)
        ])->layout('layouts.app', ['title' => 'Store Condition Types']);
    }
}
