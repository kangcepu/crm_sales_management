<div class="page">
    <div class="page-header">
        <h2>Store Assignments</h2>
        <button class="btn btn-primary" wire:click="create">New Assignment</button>
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
                        <label class="label">Role</label>
                        <select class="select" wire:model="form.assignment_role">
                            <option value="SALES">SALES</option>
                            <option value="MARKETING">MARKETING</option>
                            <option value="SUPERVISOR">SUPERVISOR</option>
                            <option value="OTHER">OTHER</option>
                        </select>
                        @error('form.assignment_role') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Assigned From</label>
                        <input class="input" type="datetime-local" wire:model="form.assigned_from">
                        @error('form.assigned_from') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Assigned To</label>
                        <input class="input" type="datetime-local" wire:model="form.assigned_to">
                        @error('form.assigned_to') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Primary</label>
                        <div class="checkbox">
                            <input type="checkbox" wire:model="form.is_primary">
                            <span>Primary</span>
                        </div>
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
                    <th>Area</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Primary</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->store?->store_name }}</td>
                        <td>{{ $item->store?->area?->area_name }}</td>
                        <td>{{ $item->user?->full_name }}</td>
                        <td>{{ $item->assignment_role }}</td>
                        <td>{{ $item->assigned_from }}</td>
                        <td>{{ $item->assigned_to }}</td>
                        <td>{{ $item->is_primary ? 'Yes' : 'No' }}</td>
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-secondary" wire:click="edit('{{ $item->store_id }}|{{ $item->user_id }}|{{ \Carbon\Carbon::parse($item->assigned_from)->format('Y-m-d H:i:s') }}')">Edit</button>
                                <button class="btn btn-danger" wire:click="delete('{{ $item->store_id }}|{{ $item->user_id }}|{{ \Carbon\Carbon::parse($item->assigned_from)->format('Y-m-d H:i:s') }}')">Delete</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $items->links('components.pagination') }}
    </div>
</div>
