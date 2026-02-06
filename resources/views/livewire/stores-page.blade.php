<div class="page">
    <div class="page-header">
        <h2>Stores</h2>
        <button class="btn btn-primary" wire:click="create">New Store</button>
    </div>

    @if(session()->has('message'))
        <div class="notice">{{ session('message') }}</div>
    @endif

    <div class="card">
        <div class="form-grid">
            <div class="form-group">
                <label class="label">Search</label>
                <input class="input" type="text" wire:model.live="search" placeholder="Search store">
            </div>
        </div>
    </div>

    @if($showForm)
        <div class="card">
            <form wire:submit.prevent="save">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="label">ERP Store ID</label>
                        <input class="input" type="text" wire:model="form.erp_store_id">
                    </div>
                    <div class="form-group">
                        <label class="label">Area</label>
                        <select class="select" wire:model="form.area_id">
                            <option value="">Select area</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Store Code</label>
                        <input class="input" type="text" wire:model="form.store_code">
                        @error('form.store_code') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Store Name</label>
                        <input class="input" type="text" wire:model="form.store_name">
                        @error('form.store_name') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Store Type</label>
                        <select class="select" wire:model="form.store_type">
                            <option value="CONSIGNMENT">CONSIGNMENT</option>
                            <option value="REGULAR">REGULAR</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Owner Name</label>
                        <input class="input" type="text" wire:model="form.owner_name">
                        @error('form.owner_name') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Phone</label>
                        <input class="input" type="text" wire:model="form.phone">
                        @error('form.phone') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Active</label>
                        <div class="checkbox">
                            <input type="checkbox" wire:model="form.is_active">
                            <span>Enabled</span>
                        </div>
                    </div>
                </div>

                <div class="form-grid" style="margin-top: 12px;">
                    <div class="form-group">
                        <label class="label">Address</label>
                        <input class="input" type="text" wire:model="address.address">
                        @error('address.address') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">City</label>
                        <input class="input" type="text" wire:model="address.city">
                        @error('address.city') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Province</label>
                        <input class="input" type="text" wire:model="address.province">
                        @error('address.province') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Latitude</label>
                        <input class="input" type="text" wire:model="address.latitude" data-store-lat>
                    </div>
                    <div class="form-group">
                        <label class="label">Longitude</label>
                        <input class="input" type="text" wire:model="address.longitude" data-store-lng>
                    </div>
                </div>
                <div style="margin-top: 12px;">
                    <div class="map-help">Click on the map to set coordinates</div>
                    <div id="store-map" class="map-box" wire:ignore></div>
                </div>

                <div class="table-actions" style="margin-top: 12px;">
                    <button class="btn btn-primary" type="submit">Save</button>
                    <button class="btn btn-secondary" type="button" wire:click="cancel">Cancel</button>
                </div>
            </form>
        </div>
    @endif

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Store</th>
                    <th>Code</th>
                    <th>Area</th>
                    <th>Type</th>
                    <th>Owner</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->store_name }}</td>
                        <td>{{ $item->store_code }}</td>
                        <td>{{ $item->area?->area_name }}</td>
                        <td>{{ $item->store_type }}</td>
                        <td>{{ $item->owner_name }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-secondary" wire:click="edit({{ $item->id }})">Edit</button>
                                <button class="btn btn-danger" wire:click="delete({{ $item->id }})">Delete</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $items->links('components.pagination') }}
    </div>
</div>
