<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UsersPage extends Component
{
    use WithPagination;

    public $form = [
        'email' => '',
        'full_name' => '',
        'phone' => '',
        'password_hash' => '',
        'is_active' => true
    ];

    public $roleIds = [];
    public $editingId = null;
    public $showForm = false;
    public $search = '';

    protected function rules()
    {
        return [
            'form.full_name' => 'required|string|max:255',
            'form.email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'form.phone' => 'nullable|string|max:50',
            'form.password_hash' => $this->editingId ? 'nullable|string|min:6' : 'required|string|min:6',
            'form.is_active' => 'boolean',
            'roleIds' => 'array',
            'roleIds.*' => 'exists:roles,id'
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
        $user = User::with('roles')->findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'email' => $user->email,
            'full_name' => $user->full_name,
            'phone' => $user->phone,
            'password_hash' => '',
            'is_active' => $user->is_active
        ];
        $this->roleIds = $user->roles->pluck('id')->all();
        $this->showForm = true;
    }

    public function save()
    {
        $data = $this->validate();
        $payload = $data['form'];
        if ($this->editingId) {
            if (!$payload['password_hash']) {
                unset($payload['password_hash']);
            }
            $user = User::findOrFail($this->editingId);
            $user->update($payload);
        } else {
            $user = User::create($payload);
        }
        $user->roles()->sync($this->roleIds);
        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(int $id)
    {
        $user = User::findOrFail($id);
        $user->roles()->detach();
        $user->delete();
        session()->flash('message', 'Deleted');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->form = [
            'email' => '',
            'full_name' => '',
            'phone' => '',
            'password_hash' => '',
            'is_active' => true
        ];
        $this->roleIds = [];
        $this->editingId = null;
        $this->showForm = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = User::with('roles');
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('full_name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        return view('livewire.users-page', [
            'items' => $query->orderByDesc('created_at')->paginate(10),
            'roles' => Role::orderBy('name')->get()
        ])->layout('layouts.app', ['title' => 'Users']);
    }
}
