<?php

namespace App\Livewire;

use App\Models\Deal;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class DealsPage extends Component
{
    use WithPagination;

    public $form = [
        'store_id' => '',
        'owner_user_id' => '',
        'deal_name' => '',
        'amount' => '',
        'stage' => 'PROSPECT',
        'expected_close_date' => ''
    ];

    public $editingId = null;
    public $showForm = false;

    protected function rules()
    {
        return [
            'form.store_id' => 'required|exists:stores,id',
            'form.owner_user_id' => 'required|exists:users,id',
            'form.deal_name' => 'required|string|max:255',
            'form.amount' => 'required|numeric|min:0',
            'form.stage' => ['required', Rule::in(['PROSPECT', 'NEGOTIATION', 'WON', 'LOST'])],
            'form.expected_close_date' => 'nullable|date'
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $deal = Deal::findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'store_id' => $deal->store_id,
            'owner_user_id' => $deal->owner_user_id,
            'deal_name' => $deal->deal_name,
            'amount' => $deal->amount,
            'stage' => $deal->stage,
            'expected_close_date' => $this->formatDate($deal->expected_close_date)
        ];
        $this->showForm = true;
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            $deal = Deal::findOrFail($this->editingId);
            $deal->update($data['form']);
        } else {
            Deal::create($data['form']);
        }
        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(int $id)
    {
        Deal::whereKey($id)->delete();
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
            'owner_user_id' => '',
            'deal_name' => '',
            'amount' => '',
            'stage' => 'PROSPECT',
            'expected_close_date' => ''
        ];
        $this->editingId = null;
        $this->showForm = false;
        $this->resetValidation();
    }

    private function formatDate($value): string
    {
        if (!$value) {
            return '';
        }
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.deals-page', [
            'items' => Deal::with(['store', 'owner'])->orderByDesc('created_at')->paginate(10),
            'stores' => Store::orderBy('store_name')->get(),
            'users' => User::orderBy('full_name')->get()
        ])->layout('layouts.app', ['title' => 'Deals']);
    }
}
