<div class="page">
    <div class="card" style="margin-bottom: 16px;">
        <div class="form-grid">
            <div class="form-group">
                <label class="label">Area</label>
                <select class="select" wire:model="areaId">
                    <option value="">Select area</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Store</th>
                    <th>Code</th>
                    <th>Address</th>
                    <th>Responsibles</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stores as $store)
                    <tr>
                        <td>{{ $store->store_name }}</td>
                        <td>{{ $store->store_code }}</td>
                        <td>{{ $store->address?->address }}</td>
                        <td>
                            @if($store->assignments->count())
                                @foreach($store->assignments as $assignment)
                                    <div>{{ $assignment->user?->full_name }} ({{ $assignment->assignment_role }}){{ $assignment->is_primary ? ' - Primary' : '' }}</div>
                                @endforeach
                            @else
                                <div>No assignments</div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No stores in this area</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
