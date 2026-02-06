<div class="page">
    <div class="page-header">
        <h2>Store Media</h2>
        <button class="btn btn-primary" wire:click="create">New Media</button>
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
                        <label class="label">Media Type</label>
                        <select class="select" wire:model="form.media_type">
                            <option value="PHOTO">PHOTO</option>
                            <option value="VIDEO">VIDEO</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Upload</label>
                        @if($editingId)
                            <input class="input" type="file" wire:model="upload" accept="image/*,video/*">
                            @error('upload') <span>{{ $message }}</span> @enderror
                        @else
                            <input class="input" type="file" wire:model="uploads" multiple accept="image/*,video/*">
                            @error('uploads') <span>{{ $message }}</span> @enderror
                            @error('uploads.*') <span>{{ $message }}</span> @enderror
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="label">Caption</label>
                        <input class="input" type="text" wire:model="form.caption">
                    </div>
                    <div class="form-group">
                        <label class="label">Taken At</label>
                        <input class="input" type="datetime-local" wire:model="form.taken_at">
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
                    <th>Type</th>
                    <th>Media</th>
                    <th>Caption</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                    @php
                        $items = $mediaByVisit[$group->visit_id] ?? collect();
                        $first = $items->first();
                        $types = $items->pluck('media_type')->unique()->values();
                        $captions = $items->pluck('caption')->filter()->unique()->values();
                        $typeLabel = $types->count() === 1 ? $types->first() : 'MIXED';
                        $captionLabel = $captions->count() === 0 ? '-' : ($captions->count() === 1 ? $captions->first() : 'Multiple');
                    @endphp
                    <tr>
                        <td>{{ $first?->visit?->store?->store_name }}</td>
                        <td>{{ $typeLabel }}</td>
                        <td>
                            @if($items->count())
                                <div class="media-stack">
                                    @foreach($items->take(4) as $media)
                                        @if($media->media_type === 'VIDEO')
                                            <div class="media-thumb media-video">VIDEO</div>
                                        @else
                                            <img class="media-thumb" src="{{ $media->media_url }}" alt="Media">
                                        @endif
                                    @endforeach
                                </div>
                                <div class="media-count">{{ $items->count() }} files</div>
                            @else
                                <div class="media-count">No media</div>
                            @endif
                        </td>
                        <td>{{ $captionLabel }}</td>
                        <td>
                            <details class="inline-details">
                                <summary>Manage</summary>
                                <div class="manage-list">
                                    @foreach($items as $media)
                                        <div class="manage-item">
                                            <div class="media-stack">
                                                @if($media->media_type === 'VIDEO')
                                                    <div class="media-thumb media-video">VIDEO</div>
                                                @else
                                                    <img class="media-thumb" src="{{ $media->media_url }}" alt="Media">
                                                @endif
                                            </div>
                                            <div class="manage-meta">
                                                <div class="manage-title">{{ $media->caption ?: 'No caption' }}</div>
                                                <a class="link" href="{{ $media->media_url }}" target="_blank">Open</a>
                                            </div>
                                            <div class="table-actions">
                                                <button class="btn btn-secondary" wire:click="edit({{ $media->id }})">Edit</button>
                                                <button class="btn btn-danger" wire:click="delete({{ $media->id }})">Delete</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $groups->links('components.pagination') }}
    </div>
</div>
