<?php

namespace App\Livewire;

use App\Models\Store;
use App\Models\StoreAssignment;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class StoreAssignmentsPage extends Component
{
    use WithPagination;

    public $form = [
        'store_id' => '',
        'user_id' => '',
        'assignment_role' => 'MARKETING',
        'assigned_from' => '',
        'assigned_to' => '',
        'is_primary' => false
    ];

    public $editingKey = null;
    public $showForm = false;

    protected function rules()
    {
        return [
            'form.store_id' => 'required|exists:stores,id',
            'form.user_id' => 'required|exists:users,id',
            'form.assignment_role' => 'required|in:SALES,MARKETING,SUPERVISOR,OTHER',
            'form.assigned_from' => 'required|date',
            'form.assigned_to' => 'nullable|date|after_or_equal:form.assigned_from',
            'form.is_primary' => 'boolean'
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(string $key)
    {
        [$storeId, $userId, $assignedFrom] = $this->splitKey($key);
        $assignment = StoreAssignment::where('store_id', $storeId)
            ->where('user_id', $userId)
            ->where('assigned_from', $assignedFrom)
            ->firstOrFail();

        $this->editingKey = $key;
        $this->form = [
            'store_id' => $assignment->store_id,
            'user_id' => $assignment->user_id,
            'assignment_role' => $assignment->assignment_role,
            'assigned_from' => $this->formatDateTime($assignment->assigned_from),
            'assigned_to' => $this->formatDateTime($assignment->assigned_to),
            'is_primary' => $assignment->is_primary
        ];
        $this->showForm = true;
    }

    public function save()
    {
        $data = $this->validate();
        $payload = $data['form'];
        if ($this->editingKey) {
            [$storeId, $userId, $assignedFrom] = $this->splitKey($this->editingKey);
            StoreAssignment::where('store_id', $storeId)
                ->where('user_id', $userId)
                ->where('assigned_from', $assignedFrom)
                ->update($payload);
        } else {
            StoreAssignment::create($payload);
        }
        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(string $key)
    {
        [$storeId, $userId, $assignedFrom] = $this->splitKey($key);
        StoreAssignment::where('store_id', $storeId)
            ->where('user_id', $userId)
            ->where('assigned_from', $assignedFrom)
            ->delete();
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
            'assignment_role' => 'MARKETING',
            'assigned_from' => $this->formatDateTime(now()),
            'assigned_to' => '',
            'is_primary' => false
        ];
        $this->editingKey = null;
        $this->showForm = false;
        $this->resetValidation();
    }

    private function splitKey(string $key): array
    {
        return explode('|', $key, 3);
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
        $items = StoreAssignment::with(['store.area', 'user'])->orderByDesc('assigned_from')->paginate(10);
        $stores = Store::orderBy('store_name')->get();
        $users = User::orderBy('full_name')->get();

        return view('livewire.store-assignments-page', [
            'items' => $items,
            'stores' => $stores,
            'users' => $users
        ])->layout('layouts.app', ['title' => 'Store Assignments']);
    }
}
