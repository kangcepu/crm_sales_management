<div class="page">
    <div class="page-header">
        <h2>Deals</h2>
        <button class="btn btn-primary" wire:click="create">New Deal</button>
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
                        <label class="label">Owner</label>
                        <select class="select" wire:model="form.owner_user_id">
                            <option value="">Select user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                            @endforeach
                        </select>
                        @error('form.owner_user_id') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Deal Name</label>
                        <input class="input" type="text" wire:model="form.deal_name">
                        @error('form.deal_name') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Amount</label>
                        <input class="input" type="number" step="0.01" wire:model="form.amount">
                        @error('form.amount') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">Stage</label>
                        <select class="select" wire:model="form.stage">
                            <option value="PROSPECT">PROSPECT</option>
                            <option value="NEGOTIATION">NEGOTIATION</option>
                            <option value="WON">WON</option>
                            <option value="LOST">LOST</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Expected Close</label>
                        <input class="input" type="date" wire:model="form.expected_close_date">
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
                    <th>Deal</th>
                    <th>Store</th>
                    <th>Owner</th>
                    <th>Amount</th>
                    <th>Stage</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->deal_name }}</td>
                        <td>{{ $item->store?->store_name }}</td>
                        <td>{{ $item->owner?->full_name }}</td>
                        <td>{{ $item->amount }}</td>
                        <td>{{ $item->stage }}</td>
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
