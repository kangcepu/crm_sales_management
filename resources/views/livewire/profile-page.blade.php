<div class="page">
    @if(session()->has('message'))
        <div class="notice">{{ session('message') }}</div>
    @endif

    <div class="card">
        <form wire:submit.prevent="save">
            <div class="form-grid">
                <div class="form-group">
                    <label class="label">Profile Photo</label>
                    <input class="input" type="file" wire:model="photo" accept="image/*">
                    @error('photo') <span>{{ $message }}</span> @enderror
                    @if($photo)
                        <div class="preview">
                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview">
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="label">Full Name</label>
                    <input class="input" type="text" wire:model="full_name">
                    @error('full_name') <span>{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="label">Phone</label>
                    <input class="input" type="text" wire:model="phone">
                    @error('phone') <span>{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="label">New Password</label>
                    <input class="input" type="password" wire:model="password">
                    @error('password') <span>{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="label">Confirm Password</label>
                    <input class="input" type="password" wire:model="password_confirmation">
                </div>
            </div>
            <div class="table-actions" style="margin-top: 16px;">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
