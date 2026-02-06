<div class="page">
    @if(session()->has('message'))
        <div class="notice">{{ session('message') }}</div>
    @endif

    <div class="card">
        <form wire:submit.prevent="save">
            <div class="form-grid">
                <div class="form-group">
                    <label class="label">Site Title</label>
                    <input class="input" type="text" wire:model="site_title">
                    @error('site_title') <span>{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="label">Description</label>
                    <input class="input" type="text" wire:model="site_description">
                </div>
                <div class="form-group">
                    <label class="label">Logo</label>
                    <input class="input" type="file" wire:model="logo_upload" accept="image/*">
                    @if($currentLogo)
                        <div class="preview"><img src="{{ $currentLogo }}" alt="Logo"></div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="label">Favicon</label>
                    <input class="input" type="file" wire:model="favicon_upload" accept="image/png,image/x-icon">
                    @if($currentFavicon)
                        <div class="preview"><img src="{{ $currentFavicon }}" alt="Favicon"></div>
                    @endif
                </div>
            </div>
            <div class="table-actions" style="margin-top: 16px;">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
