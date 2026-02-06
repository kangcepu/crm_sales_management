<?php

namespace App\Livewire;

use App\Models\Store;
use App\Models\Area;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class StoresPage extends Component
{
    use WithPagination;

    public $form = [
        'erp_store_id' => '',
        'area_id' => '',
        'store_code' => '',
        'store_name' => '',
        'store_type' => 'REGULAR',
        'owner_name' => '',
        'phone' => '',
        'is_active' => true
    ];

    public $address = [
        'address' => '',
        'city' => '',
        'province' => '',
        'latitude' => '',
        'longitude' => ''
    ];

    public $editingId = null;
    public $showForm = false;
    public $search = '';

    protected function rules()
    {
        return [
            'form.erp_store_id' => 'nullable|string|max:255',
            'form.area_id' => 'nullable|exists:areas,id',
            'form.store_code' => ['required', 'string', 'max:50', Rule::unique('stores', 'store_code')->ignore($this->editingId)],
            'form.store_name' => 'required|string|max:255',
            'form.store_type' => ['required', Rule::in(['CONSIGNMENT', 'REGULAR'])],
            'form.owner_name' => 'required|string|max:255',
            'form.phone' => 'required|string|max:50',
            'form.is_active' => 'boolean',
            'address.address' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.province' => 'required|string|max:255',
            'address.latitude' => 'nullable|numeric',
            'address.longitude' => 'nullable|numeric'
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
        $this->dispatchMap();
    }

    public function edit(int $id)
    {
        $store = Store::with('address')->findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'erp_store_id' => $store->erp_store_id,
            'area_id' => $store->area_id,
            'store_code' => $store->store_code,
            'store_name' => $store->store_name,
            'store_type' => $store->store_type,
            'owner_name' => $store->owner_name,
            'phone' => $store->phone,
            'is_active' => $store->is_active
        ];
        $this->address = [
            'address' => $store->address?->address ?? '',
            'city' => $store->address?->city ?? '',
            'province' => $store->address?->province ?? '',
            'latitude' => $store->address?->latitude ?? '',
            'longitude' => $store->address?->longitude ?? ''
        ];
        $this->showForm = true;
        $this->dispatchMap();
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            $store = Store::findOrFail($this->editingId);
            $store->update($data['form']);
            if ($store->address) {
                $store->address->update($data['address']);
            } else {
                $store->address()->create($data['address']);
            }
        } else {
            $store = Store::create($data['form']);
            $store->address()->create($data['address']);
        }
        session()->flash('message', 'Saved');
        $this->resetForm();
    }

    public function delete(int $id)
    {
        Store::whereKey($id)->delete();
        session()->flash('message', 'Deleted');
    }

    public function cancel()
    {
        $this->resetForm();
        $this->dispatchMap();
    }

    private function resetForm()
    {
        $this->form = [
            'erp_store_id' => '',
            'area_id' => '',
            'store_code' => '',
            'store_name' => '',
            'store_type' => 'REGULAR',
            'owner_name' => '',
            'phone' => '',
            'is_active' => true
        ];
        $this->address = [
            'address' => '',
            'city' => '',
            'province' => '',
            'latitude' => '',
            'longitude' => ''
        ];
        $this->editingId = null;
        $this->showForm = false;
        $this->resetValidation();
    }

    private function dispatchMap(): void
    {
        $this->dispatch('store-map:set', lat: $this->address['latitude'] ?? null, lng: $this->address['longitude'] ?? null);
    }

    public function render()
    {
        $query = Store::with(['address', 'area']);
        if ($this->search) {
            $query->where('store_name', 'like', '%'.$this->search.'%')
                ->orWhere('store_code', 'like', '%'.$this->search.'%');
        }

        return view('livewire.stores-page', [
            'items' => $query->orderByDesc('created_at')->paginate(10),
            'areas' => Area::orderBy('area_name')->get()
        ])->layout('layouts.app', ['title' => 'Stores']);
    }
}
