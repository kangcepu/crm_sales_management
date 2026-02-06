<?php

namespace App\Livewire;

use App\Models\Area;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class AreasPage extends Component
{
    use WithPagination;

    public $form = [
        'area_code' => '',
        'area_name' => '',
        'description' => '',
        'is_active' => true
    ];

    public $editingId = null;
    public $showForm = false;
    public $search = '';

    protected function rules()
    {
        return [
            'form.area_code' => ['required', 'string', 'max:50', Rule::unique('areas', 'area_code')->ignore($this->editingId)],
            'form.area_name' => 'required|string|max:255',
            'form.description' => 'nullable|string',
            'form.is_active' => 'boolean'
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $area = Area::findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'area_code' => $area->area_code,
            'area_name' => $area->area_name,
            'description' => $area->description,
            'is_active' => $area->is_active
        ];
        $this->showForm = true;
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            $area = Area::findOrFail($this->editingId);
            $area->update($data['form']);
        } else {
            Area::create($data['form']);
        }
        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(int $id)
    {
        Area::whereKey($id)->delete();
        session()->flash('message', 'Deleted');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->form = [
            'area_code' => '',
            'area_name' => '',
            'description' => '',
            'is_active' => true
        ];
        $this->editingId = null;
        $this->showForm = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Area::query();
        if ($this->search) {
            $query->where('area_name', 'like', '%'.$this->search.'%')
                ->orWhere('area_code', 'like', '%'.$this->search.'%');
        }

        return view('livewire.areas-page', [
            'items' => $query->orderBy('area_name')->paginate(10)
        ])->layout('layouts.app', ['title' => 'Areas']);
    }
}
