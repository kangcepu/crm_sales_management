<div class="page">
    <div class="page-header">
        <h2>Store Statuses</h2>
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
                        <label class="label">Code</label>
                        <input class="input" type="text" wire:model="form.code">
                        @error('form.code') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Name</label>
                        <input class="input" type="text" wire:model="form.name">
                        @error('form.name') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Color</label>
                        <input class="input" type="text" wire:model="form.color" placeholder="#22c55e">
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
                        <label class="label">Traits</label>
                        <textarea class="textarea" wire:model="form.traits"></textarea>
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
                    <th>Traits</th>
                    <th>Active</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->traits }}</td>
                        <td>{{ $item->is_active ? 'Yes' : 'No' }}</td>
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
