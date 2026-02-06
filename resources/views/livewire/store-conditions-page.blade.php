<div class="page">
    <div class="page-header">
        <h2>Store Conditions</h2>
        <button class="btn btn-primary" wire:click="create">New Condition</button>
    </div>

    @if(session()->has('message'))
        <div class="notice">{{ session('message') }}</div>
    @endif

    @if($showForm)
        <div class="card">
            <form wire:submit.prevent="save">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="label">Visit</label>
                        <select class="select" wire:model="form.visit_id">
                            <option value="">Select visit</option>
                            @foreach($visits as $visit)
                                <option value="{{ $visit->id }}">{{ $visit->store?->store_name }} - {{ $visit->visit_at }}</option>
                            @endforeach
                        </select>
                        @error('form.visit_id') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Exterior</label>
                        <select class="select" wire:model="form.exterior_condition">
                            <option value="GOOD">GOOD</option>
                            <option value="FAIR">FAIR</option>
                            <option value="BAD">BAD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Interior</label>
                        <select class="select" wire:model="form.interior_condition">
                            <option value="GOOD">GOOD</option>
                            <option value="FAIR">FAIR</option>
                            <option value="BAD">BAD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Display Quality</label>
                        <input class="input" type="text" wire:model="form.display_quality">
                    </div>
                    <div class="form-group">
                        <label class="label">Cleanliness</label>
                        <input class="input" type="text" wire:model="form.cleanliness">
                    </div>
                    <div class="form-group">
                        <label class="label">Shelf Availability</label>
                        <input class="input" type="text" wire:model="form.shelf_availability">
                    </div>
                    <div class="form-group">
                        <label class="label">Overall Status</label>
                        <select class="select" wire:model="form.overall_status">
                            <option value="ACTIVE">ACTIVE</option>
                            <option value="RISK">RISK</option>
                            <option value="POTENTIAL">POTENTIAL</option>
                            <option value="DROPPED">DROPPED</option>
                        </select>
                    </div>
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
                    <th>Visit</th>
                    <th>Exterior</th>
                    <th>Interior</th>
                    <th>Overall</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->visit?->store?->store_name }}</td>
                        <td>{{ $item->exterior_condition }}</td>
                        <td>{{ $item->interior_condition }}</td>
                        <td>{{ $item->overall_status }}</td>
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
