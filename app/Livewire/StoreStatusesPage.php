<?php

namespace App\Livewire;

use App\Models\StoreStatus;
use Livewire\Component;
use Livewire\WithPagination;

class StoreStatusesPage extends Component
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
            'form.code' => 'required|string|max:50|unique:store_statuses,code,'.$this->editingId,
            'form.name' => 'required|string|max:120',
            'form.traits' => 'nullable|string',
            'form.color' => 'nullable|string|max:20',
            'form.is_active' => 'boolean'
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->form['code'] = StoreStatus::nextCode();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $status = StoreStatus::findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'code' => $status->code,
            'name' => $status->name,
            'traits' => $status->traits,
            'color' => $status->color,
            'is_active' => $status->is_active
        ];
        $this->showForm = true;
    }

    public function save()
    {
        if (!$this->editingId && empty($this->form['code'])) {
            $this->form['code'] = StoreStatus::nextCode();
        }
        $data = $this->validate();
        if ($this->editingId) {
            $status = StoreStatus::findOrFail($this->editingId);
            $status->update($data['form']);
        } else {
            StoreStatus::create($data['form']);
        }
        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(int $id)
    {
        StoreStatus::whereKey($id)->delete();
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
        return view('livewire.store-statuses-page', [
            'items' => StoreStatus::orderBy('name')->paginate(10)
        ])->layout('layouts.app', ['title' => 'Store Statuses']);
    }
}
