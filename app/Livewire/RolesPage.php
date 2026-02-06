<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class RolesPage extends Component
{
    use WithPagination;

    public $form = [
        'name' => '',
        'description' => ''
    ];

    public $permissionIds = [];
    public $editingId = null;
    public $showForm = false;
    public $search = '';

    protected function rules()
    {
        return [
            'form.name' => ['required', 'string', 'max:100', Rule::unique('roles', 'name')->ignore($this->editingId)],
            'form.description' => 'required|string|max:255'
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
        $role = Role::findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'name' => $role->name,
            'description' => $role->description
        ];
        $this->permissionIds = $role->permissions()->pluck('permissions.id')->toArray();
        $this->showForm = true;
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            $role = Role::findOrFail($this->editingId);
            $role->update($data['form']);
        } else {
            Role::create($data['form']);
        }
        $role = $this->editingId ? Role::findOrFail($this->editingId) : Role::where('name', $data['form']['name'])->first();
        if ($role) {
            $role->permissions()->sync($this->permissionIds);
        }
        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(int $id)
    {
        Role::whereKey($id)->delete();
        session()->flash('message', 'Deleted');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->form = [
            'name' => '',
            'description' => ''
        ];
        $this->permissionIds = [];
        $this->editingId = null;
        $this->showForm = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Role::query();
        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        return view('livewire.roles-page', [
            'items' => $query->orderBy('name')->paginate(10),
            'permissions' => Permission::orderBy('name')->get()
        ])->layout('layouts.app', ['title' => 'Roles']);
    }
}
