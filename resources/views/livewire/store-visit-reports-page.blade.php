<div class="page">
    <div class="page-header">
        <h2>Visit Reports</h2>
        <button class="btn btn-primary" wire:click="create">New Report</button>
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
                        <label class="label">Consignment Qty</label>
                        <input class="input" type="number" wire:model="form.consignment_qty">
                    </div>
                    <div class="form-group">
                        <label class="label">Consignment Value</label>
                        <input class="input" type="number" step="0.01" wire:model="form.consignment_value">
                    </div>
                    <div class="form-group">
                        <label class="label">Sales Qty</label>
                        <input class="input" type="number" wire:model="form.sales_qty">
                    </div>
                    <div class="form-group">
                        <label class="label">Sales Value</label>
                        <input class="input" type="number" step="0.01" wire:model="form.sales_value">
                    </div>
                    <div class="form-group">
                        <label class="label">Payment Status</label>
                        <select class="select" wire:model="form.payment_status">
                            <option value="PAID">PAID</option>
                            <option value="PENDING">PENDING</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Report Media</label>
                        <input class="input" type="file" wire:model="uploads" multiple accept="image/*,video/*">
                        @error('uploads') <span>{{ $message }}</span> @enderror
                        @error('uploads.*') <span>{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-grid" style="margin-top: 12px;">
                    <div class="form-group">
                        <label class="label">Competitor Activity</label>
                        <textarea class="textarea" wire:model="form.competitor_activity"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="label">Notes</label>
                        <textarea class="textarea" wire:model="form.notes"></textarea>
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
                    <th>Sales Qty</th>
                    <th>Sales Value</th>
                    <th>Payment</th>
                    <th>Media</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->visit?->store?->store_name }}</td>
                        <td>{{ $item->sales_qty }}</td>
                        <td>{{ $item->sales_value }}</td>
                        <td>{{ $item->payment_status }}</td>
                        <td>
                            @if($item->media->count())
                                <div class="media-stack">
                                    @foreach($item->media->take(3) as $media)
                                        @if($media->media_type === 'VIDEO')
                                            <div class="media-thumb media-video">VIDEO</div>
                                        @else
                                            <img class="media-thumb" src="{{ $media->media_url }}" alt="Media">
                                        @endif
                                    @endforeach
                                </div>
                                <div class="media-count">{{ $item->media->count() }} files</div>
                            @else
                                <div class="media-count">No media</div>
                            @endif
                        </td>
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
