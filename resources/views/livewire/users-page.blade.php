<div class="page">
    <div class="page-header">
        <h2>Users</h2>
        <button class="btn btn-primary" wire:click="create">New User</button>
    </div>

    @if(session()->has('message'))
        <div class="notice">{{ session('message') }}</div>
    @endif

    <div class="card">
        <div class="form-grid">
            <div class="form-group">
                <label class="label">Search</label>
                <input class="input" type="text" wire:model.live="search" placeholder="Search name or email">
            </div>
        </div>
    </div>

    @if($showForm)
        <div class="card">
            <form wire:submit.prevent="save">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="label">Full Name</label>
                        <input class="input" type="text" wire:model="form.full_name">
                        @error('form.full_name') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Email</label>
                        <input class="input" type="email" wire:model="form.email">
                        @error('form.email') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Phone</label>
                        <input class="input" type="text" wire:model="form.phone">
                        @error('form.phone') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Password</label>
                        <input class="input" type="password" wire:model="form.password_hash">
                        @error('form.password_hash') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Roles</label>
                        <select class="select" multiple wire:model="roleIds">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('roleIds') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Active</label>
                        <div class="checkbox">
                            <input type="checkbox" wire:model="form.is_active">
                            <span>Enabled</span>
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
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->full_name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->roles->pluck('name')->implode(', ') }}</td>
                        <td>{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>{{ $item->created_at }}</td>
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
