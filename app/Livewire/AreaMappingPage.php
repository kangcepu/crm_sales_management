<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\Store;
use Livewire\Component;

class AreaMappingPage extends Component
{
    public $areaId = '';

    public function mount()
    {
        if (!$this->areaId) {
            $area = Area::orderBy('area_name')->first();
            $this->areaId = $area?->id ?? '';
        }
    }

    public function render()
    {
        $areas = Area::orderBy('area_name')->get();
        $stores = collect();

        if ($this->areaId) {
            $stores = Store::with(['address', 'assignments.user'])
                ->where('area_id', $this->areaId)
                ->orderBy('store_name')
                ->get();
        }

        return view('livewire.area-mapping-page', [
            'areas' => $areas,
            'stores' => $stores
        ])->layout('layouts.app', [
            'title' => 'Area Mapping',
            'subtitle' => 'Daftar toko dan penanggung jawab per area.'
        ]);
    }
}
