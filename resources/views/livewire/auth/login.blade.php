@php
    $settings = \App\Models\Setting::allKeyed();
    $siteTitle = $settings['site_title'] ?? 'CRM';
    $siteLogo = $settings['site_logo'] ?? null;
@endphp
<div class="auth-screen">
    <div class="auth-hero">Welcome to CRM</div>
    <div class="auth-card">
        <div class="auth-logo">
            @if($siteLogo)
                <img src="{{ $siteLogo }}" alt="{{ $siteTitle }}">
            @else
                <div class="auth-logo-text">{{ $siteTitle }}</div>
            @endif
        </div>
        <form wire:submit.prevent="login">
            <div class="form-grid">
                <div class="form-group">
                    <label class="label">Email</label>
                    <input class="input" type="email" wire:model="email">
                    @error('email') <span>{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="label">Password</label>
                    <input class="input" type="password" wire:model="password">
                    @error('password') <span>{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="table-actions">
                <button class="btn auth-button" type="submit">Login</button>
            </div>
        </form>
    </div>
    <div class="auth-footer">Copyright © {{ date('Y') }}. All rights reserved.</div>
</div>
