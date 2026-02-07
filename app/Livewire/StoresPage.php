<?php

namespace App\Livewire;

use App\Models\Store;
use App\Models\Area;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use App\Services\OpenStreetMapService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
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
        'country_id' => '',
        'province_id' => '',
        'city_id' => '',
        'district_id' => '',
        'village_id' => '',
        'address' => '',
        'city' => '',
        'province' => '',
        'latitude' => '',
        'longitude' => ''
    ];

    public $addressSearch = '';
    public $addressResults = [];
    public $addressNotice = '';

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
            'address.country_id' => 'required|exists:countries,id',
            'address.province_id' => 'required|exists:provinces,id',
            'address.city_id' => 'required|exists:cities,id',
            'address.district_id' => 'required|exists:districts,id',
            'address.village_id' => 'required|exists:villages,id',
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
            'country_id' => $store->address?->country_id ?? '',
            'province_id' => $store->address?->province_id ?? '',
            'city_id' => $store->address?->city_id ?? '',
            'district_id' => $store->address?->district_id ?? '',
            'village_id' => $store->address?->village_id ?? '',
            'address' => $store->address?->address ?? '',
            'city' => $store->address?->city ?? '',
            'province' => $store->address?->province ?? '',
            'latitude' => $store->address?->latitude ?? '',
            'longitude' => $store->address?->longitude ?? ''
        ];
        $this->addressSearch = $this->address['address'] ?? '';
        $this->showForm = true;
        $this->dispatchMap();
    }

    public function save()
    {
        $data = $this->validate();
        $data['address'] = $this->hydrateAddressNames($data['address']);
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
            'country_id' => '',
            'province_id' => '',
            'city_id' => '',
            'district_id' => '',
            'village_id' => '',
            'address' => '',
            'city' => '',
            'province' => '',
            'latitude' => '',
            'longitude' => ''
        ];
        $this->addressSearch = '';
        $this->addressResults = [];
        $this->addressNotice = '';
        $this->editingId = null;
        $this->showForm = false;
        $this->resetValidation();
    }

    private function dispatchMap(): void
    {
        $this->dispatch('store-map:set', lat: $this->address['latitude'] ?? null, lng: $this->address['longitude'] ?? null);
    }

    public function updatedAddressCountryId()
    {
        $this->address['province_id'] = '';
        $this->address['city_id'] = '';
        $this->address['district_id'] = '';
        $this->address['village_id'] = '';
    }

    public function updatedAddressProvinceId()
    {
        $this->address['city_id'] = '';
        $this->address['district_id'] = '';
        $this->address['village_id'] = '';
        $this->syncProvinceName();
    }

    public function updatedAddressCityId()
    {
        $this->address['district_id'] = '';
        $this->address['village_id'] = '';
        $this->syncCityName();
    }

    public function updatedAddressDistrictId()
    {
        $this->address['village_id'] = '';
    }

    public function updatedAddressSearch()
    {
        $this->addressNotice = '';
        $term = trim($this->addressSearch);
        if (mb_strlen($term) < 3) {
            $this->addressResults = [];
            return;
        }

        $service = app(OpenStreetMapService::class);
        $results = $service->search($term);
        $this->addressResults = $results;
        if (!$results) {
            $this->addressNotice = 'No results';
        }
    }

    public function selectAddressResult(float $lat, float $lng)
    {
        $this->addressNotice = '';
        $this->reverseGeocode($lat, $lng);
        $this->addressResults = [];
    }

    public function reverseGeocode(float $lat, float $lng)
    {
        $service = app(OpenStreetMapService::class);
        $details = $service->reverse($lat, $lng);
        if (!$details) {
            $this->addressNotice = 'Unable to load address details';
            return;
        }
        $this->applyOsmAddress($details);
    }

    private function syncProvinceName(): void
    {
        if (!empty($this->address['province_id'])) {
            $province = Province::find($this->address['province_id']);
            if ($province) {
                $this->address['province'] = $province->name;
            }
        }
    }

    private function syncCityName(): void
    {
        if (!empty($this->address['city_id'])) {
            $city = City::find($this->address['city_id']);
            if ($city) {
                $this->address['city'] = $city->name;
            }
        }
    }

    private function hydrateAddressNames(array $address): array
    {
        if (!empty($address['province_id'])) {
            $province = Province::find($address['province_id']);
            if ($province) {
                $address['province'] = $province->name;
            }
        }
        if (!empty($address['city_id'])) {
            $city = City::find($address['city_id']);
            if ($city) {
                $address['city'] = $city->name;
            }
        }
        return $address;
    }

    private function applyOsmAddress(array $details): void
    {
        $address = $details['address'] ?? [];
        $ids = $this->upsertLocationIdsFromOsm($address);

        if (!empty($ids['country_id'])) {
            $this->address['country_id'] = $ids['country_id'];
        }
        if (!empty($ids['province_id'])) {
            $this->address['province_id'] = $ids['province_id'];
        }
        if (!empty($ids['city_id'])) {
            $this->address['city_id'] = $ids['city_id'];
        }
        if (!empty($ids['district_id'])) {
            $this->address['district_id'] = $ids['district_id'];
        }
        if (!empty($ids['village_id'])) {
            $this->address['village_id'] = $ids['village_id'];
        }

        $this->address['address'] = $details['display_name'] ?? $this->address['address'];
        $this->addressSearch = $details['display_name'] ?? $this->addressSearch;
        $this->address['latitude'] = $details['lat'] ?? $this->address['latitude'];
        $this->address['longitude'] = $details['lng'] ?? $this->address['longitude'];

        if (!empty($address['state'])) {
            $this->address['province'] = $address['state'];
        }
        if (!empty($address['city'])) {
            $this->address['city'] = $address['city'];
        } elseif (!empty($address['town'])) {
            $this->address['city'] = $address['town'];
        } elseif (!empty($address['municipality'])) {
            $this->address['city'] = $address['municipality'];
        } elseif (!empty($address['county'])) {
            $this->address['city'] = $address['county'];
        }

        $this->dispatchMap();
    }

    private function upsertLocationIdsFromOsm(array $address): array
    {
        $countryName = $address['country'] ?? null;
        if (!$countryName) {
            return [];
        }

        $countryCode = strtoupper($address['country_code'] ?? '');
        if (!$countryCode) {
            $countryCode = $this->buildCodeFromName($countryName, 10);
        }
        $country = Country::firstOrCreate(
            ['code' => $countryCode],
            ['name' => $countryName]
        );
        if ($country->name !== $countryName) {
            $country->update(['name' => $countryName]);
        }

        $province = null;
        $provinceName = $address['state'] ?? $address['region'] ?? $address['state_district'] ?? null;
        if ($provinceName) {
            $province = Province::firstOrCreate(
                ['country_id' => $country->id, 'name' => $provinceName],
                ['code' => null]
            );
        }

        $city = null;
        $cityName = $address['city'] ?? $address['town'] ?? $address['municipality'] ?? $address['county'] ?? null;
        if ($province && $cityName) {
            $city = City::firstOrCreate(
                ['province_id' => $province->id, 'name' => $cityName],
                ['code' => null]
            );
        }

        $district = null;
        $districtName = $address['city_district'] ?? $address['district'] ?? $address['suburb'] ?? null;
        if ($city && $districtName) {
            $district = District::firstOrCreate(
                ['city_id' => $city->id, 'name' => $districtName],
                ['code' => null]
            );
        }

        $village = null;
        $villageName = $address['village'] ?? $address['hamlet'] ?? $address['neighbourhood'] ?? null;
        if ($district && $villageName) {
            $village = Village::firstOrCreate(
                ['district_id' => $district->id, 'name' => $villageName],
                ['code' => null]
            );
        }

        return [
            'country_id' => $country->id,
            'province_id' => $province?->id,
            'city_id' => $city?->id,
            'district_id' => $district?->id,
            'village_id' => $village?->id,
        ];
    }

    private function buildCodeFromName(string $name, int $limit): string
    {
        $clean = preg_replace('/[^A-Za-z]/', '', $name) ?? '';
        $code = Str::upper(substr($clean, 0, $limit));
        return $code ?: Str::upper(Str::random(min(2, $limit)));
    }

    public function render()
    {
        $query = Store::with(['address', 'area', 'assignments.user']);
        if ($this->search) {
            $query->where('store_name', 'like', '%'.$this->search.'%')
                ->orWhere('store_code', 'like', '%'.$this->search.'%');
        }

        $countries = Country::orderBy('name')->get();
        $provinces = $this->address['country_id']
            ? Province::where('country_id', $this->address['country_id'])->orderBy('name')->get()
            : collect();
        $cities = $this->address['province_id']
            ? City::where('province_id', $this->address['province_id'])->orderBy('name')->get()
            : collect();
        $districts = $this->address['city_id']
            ? District::where('city_id', $this->address['city_id'])->orderBy('name')->get()
            : collect();
        $villages = $this->address['district_id']
            ? Village::where('district_id', $this->address['district_id'])->orderBy('name')->get()
            : collect();

        return view('livewire.stores-page', [
            'items' => $query->orderByDesc('created_at')->paginate(10),
            'areas' => Area::orderBy('area_name')->get(),
            'countries' => $countries,
            'provinces' => $provinces,
            'cities' => $cities,
            'districts' => $districts,
            'villages' => $villages
        ])->layout('layouts.app', ['title' => 'Stores']);
    }
}
