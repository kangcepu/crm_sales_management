<div class="page">
    <div class="page-header">
        <h2>Roles</h2>
        <button class="btn btn-primary" wire:click="create">New Role</button>
    </div>

    @if(session()->has('message'))
        <div class="notice">{{ session('message') }}</div>
    @endif

    <div class="card">
        <div class="form-grid">
            <div class="form-group">
                <label class="label">Search</label>
                <input class="input" type="text" wire:model.live="search" placeholder="Search role">
            </div>
        </div>
    </div>

    @if($showForm)
        <div class="card">
            <form wire:submit.prevent="save">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="label">Name</label>
                        <input class="input" type="text" wire:model="form.name">
                        @error('form.name') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Description</label>
                        <input class="input" type="text" wire:model="form.description">
                        @error('form.description') <span>{{ $message }}</span> @enderror
                    </div>
                </div>
                <div style="margin-top: 12px;">
                    <label class="label">Permissions</label>
                    <div class="form-grid">
                        @foreach($permissions as $permission)
                            <label class="checkbox">
                                <input type="checkbox" value="{{ $permission->id }}" wire:model="permissionIds">
                                <span>{{ $permission->name }}</span>
                            </label>
                        @endforeach
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
                    <th>Description</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
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
