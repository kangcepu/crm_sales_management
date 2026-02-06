<div class="page">
    <div class="page-header">
        <h2>Areas</h2>
        <button class="btn btn-primary" wire:click="create">New Area</button>
    </div>

    @if(session()->has('message'))
        <div class="notice">{{ session('message') }}</div>
    @endif

    <div class="card" style="margin-bottom: 16px;">
        <input class="input" type="text" placeholder="Search area" wire:model.debounce.300ms="search">
    </div>

    @if($showForm)
        <div class="card" style="margin-bottom: 16px;">
            <form wire:submit.prevent="save">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="label">Area Code</label>
                        <input class="input" type="text" wire:model="form.area_code">
                        @error('form.area_code') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Area Name</label>
                        <input class="input" type="text" wire:model="form.area_name">
                        @error('form.area_name') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Description</label>
                        <input class="input" type="text" wire:model="form.description">
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
                    <th>Code</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->area_code }}</td>
                        <td>{{ $item->area_name }}</td>
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
