<div class="page">
    <div class="page-header">
        <h2>Store Visits</h2>
        <button class="btn btn-primary" wire:click="create">New Visit</button>
    </div>

    @if(session()->has('message'))
        <div class="notice">{{ session('message') }}</div>
    @endif

    @if($showForm)
        <div class="card">
            <form wire:submit.prevent="save">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="label">Store</label>
                        <select class="select" wire:model="form.store_id">
                            <option value="">Select store</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                        @error('form.store_id') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">User</label>
                        <select class="select" wire:model="form.user_id">
                            <option value="">Select user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                            @endforeach
                        </select>
                        @error('form.user_id') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Visit At</label>
                        <input class="input" type="datetime-local" wire:model="form.visit_at">
                        @error('form.visit_at') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Latitude</label>
                        <input class="input" type="text" wire:model="form.latitude">
                    </div>
                    <div class="form-group">
                        <label class="label">Longitude</label>
                        <input class="input" type="text" wire:model="form.longitude">
                    </div>
                    <div class="form-group">
                        <label class="label">Distance From Store</label>
                        <input class="input" type="text" wire:model="form.distance_from_store">
                    </div>
                    <div class="form-group">
                        <label class="label">Status</label>
                        <select class="select" wire:model="form.visit_status">
                            <option value="ON_TIME">ON_TIME</option>
                            <option value="OUT_OF_RANGE">OUT_OF_RANGE</option>
                        </select>
                    </div>
                </div>
                <div class="form-grid" style="margin-top: 12px;">
                    <div class="form-group">
                        <label class="label">Summary</label>
                        <textarea class="textarea" wire:model="form.summary"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="label">Next Visit Plan</label>
                        <textarea class="textarea" wire:model="form.next_visit_plan"></textarea>
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
                    <th>Store</th>
                    <th>User</th>
                    <th>Visit At</th>
                    <th>Status</th>
                    <th>Distance</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->store?->store_name }}</td>
                        <td>{{ $item->user?->full_name }}</td>
                        <td>{{ $item->visit_at }}</td>
                        <td>{{ $item->visit_status }}</td>
                        <td>{{ $item->distance_from_store }}</td>
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
