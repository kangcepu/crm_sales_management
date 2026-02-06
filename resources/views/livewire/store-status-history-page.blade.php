<div class="page">
    <div class="page-header">
        <h2>Store Status History</h2>
        <button class="btn btn-primary" wire:click="create">New Status</button>
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
                        <label class="label">Status</label>
                        <select class="select" wire:model="form.status">
                            <option value="ACTIVE">ACTIVE</option>
                            <option value="INACTIVE">INACTIVE</option>
                            <option value="CLOSED">CLOSED</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Changed By</label>
                        <select class="select" wire:model="form.changed_by_user_id">
                            <option value="">Select user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                            @endforeach
                        </select>
                        @error('form.changed_by_user_id') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Changed At</label>
                        <input class="input" type="datetime-local" wire:model="form.changed_at">
                        @error('form.changed_at') <span>{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-grid" style="margin-top: 12px;">
                    <div class="form-group">
                        <label class="label">Note</label>
                        <textarea class="textarea" wire:model="form.note"></textarea>
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
                    <th>Status</th>
                    <th>Changed By</th>
                    <th>Changed At</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->store?->store_name }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->changedBy?->full_name }}</td>
                        <td>{{ $item->changed_at }}</td>
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
