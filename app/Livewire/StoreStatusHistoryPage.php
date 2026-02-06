<?php

namespace App\Livewire;

use App\Models\Store;
use App\Models\StoreStatusHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class StoreStatusHistoryPage extends Component
{
    use WithPagination;

    public $form = [
        'store_id' => '',
        'status' => 'ACTIVE',
        'note' => '',
        'changed_by_user_id' => '',
        'changed_at' => ''
    ];

    public $editingId = null;
    public $showForm = false;

    protected function rules()
    {
        return [
            'form.store_id' => 'required|exists:stores,id',
            'form.status' => ['required', Rule::in(['ACTIVE', 'INACTIVE', 'CLOSED'])],
            'form.note' => 'nullable|string',
            'form.changed_by_user_id' => 'required|exists:users,id',
            'form.changed_at' => 'required|date'
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $history = StoreStatusHistory::findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'store_id' => $history->store_id,
            'status' => $history->status,
            'note' => $history->note,
            'changed_by_user_id' => $history->changed_by_user_id,
            'changed_at' => $this->formatDateTime($history->changed_at)
        ];
        $this->showForm = true;
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            $history = StoreStatusHistory::findOrFail($this->editingId);
            $history->update($data['form']);
        } else {
            StoreStatusHistory::create($data['form']);
        }
        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(int $id)
    {
        StoreStatusHistory::whereKey($id)->delete();
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
            'status' => 'ACTIVE',
            'note' => '',
            'changed_by_user_id' => auth()->id() ?? '',
            'changed_at' => $this->formatDateTime(now())
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
        return view('livewire.store-status-history-page', [
            'items' => StoreStatusHistory::with(['store', 'changedBy'])->orderByDesc('changed_at')->paginate(10),
            'stores' => Store::orderBy('store_name')->get(),
            'users' => User::orderBy('full_name')->get()
        ])->layout('layouts.app', ['title' => 'Store Status History']);
    }
}
