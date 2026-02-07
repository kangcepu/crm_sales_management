<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $settings = \App\Models\Setting::allKeyed();
        $siteTitle = $settings['site_title'] ?? 'CR Sales';
        $siteDescription = $settings['site_description'] ?? 'Enterprise CRM System';
        $siteLogo = \App\Models\Setting::resolveMediaUrl($settings['site_logo'] ?? null);
        $siteFavicon = \App\Models\Setting::resolveMediaUrl($settings['site_favicon'] ?? null);
    @endphp
    <title>{{ $siteTitle }}</title>
    @if($siteFavicon)
        <link rel="icon" href="{{ $siteFavicon }}">
    @endif
    <link rel="stylesheet" href="{{ asset('app.css') }}?v={{ filemtime(public_path('app.css')) }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    @livewireStyles
</head>
<body class="body">
<div class="app">
    @php
        $user = auth()->user();
    @endphp
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-mark">
                @if($siteLogo)
                    <img class="brand-logo" src="{{ $siteLogo }}" alt="{{ $siteTitle }}">
                @else
                    <div class="brand-initial">{{ strtoupper(substr($siteTitle ?? 'C', 0, 1)) }}</div>
                @endif
            </div>
            <div class="brand-text">
                <div class="brand-name">{{ $siteTitle }}</div>
                <div class="brand-sub">{{ $siteDescription }}</div>
            </div>
        </div>
        <div class="nav-section">
            <div class="nav-label">Main Menu</div>
            <nav class="nav">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" wire:navigate>
                    <span class="nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M3 11.5L12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/>
                        </svg>
                    </span>
                    <span>Dashboard</span>
                </a>
            </nav>
        </div>
        @if($user && $user->hasAnyPermission(['conditions.manage','media.manage','deals.manage']))
            <div class="nav-section">
                <div class="nav-label">Resources</div>
                <nav class="nav">
                    @if($user->hasPermission('conditions.manage'))
                        <a class="nav-link {{ request()->routeIs('conditions') ? 'active' : '' }}" href="{{ route('conditions') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 7h16M4 12h16M4 17h16"/>
                                    <circle cx="9" cy="7" r="2"/>
                                    <circle cx="15" cy="12" r="2"/>
                                    <circle cx="8" cy="17" r="2"/>
                                </svg>
                            </span>
                            <span>Store Conditions</span>
                        </a>
                    @endif
                    @if($user->hasPermission('media.manage'))
                        <a class="nav-link {{ request()->routeIs('media') ? 'active' : '' }}" href="{{ route('media') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <path d="M8 13l3-3 4 5"/>
                                    <circle cx="9" cy="9" r="1.5"/>
                                </svg>
                            </span>
                            <span>Store Media</span>
                        </a>
                    @endif
                    @if($user->hasPermission('deals.manage'))
                        <a class="nav-link {{ request()->routeIs('deals') ? 'active' : '' }}" href="{{ route('deals') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M9 12h6M12 7v10"/>
                                </svg>
                            </span>
                            <span>Deals</span>
                        </a>
                    @endif
                </nav>
            </div>
        @endif
        @if($user && $user->hasAnyPermission(['status_history.manage','visits.manage','visit_reports.manage','report_tracking.view']))
            <div class="nav-section">
                <div class="nav-label">Analytics</div>
                <nav class="nav">
                    @if($user->hasPermission('status_history.manage'))
                        <a class="nav-link {{ request()->routeIs('status-history') ? 'active' : '' }}" href="{{ route('status-history') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 8v5l3 2"/>
                                    <circle cx="12" cy="12" r="9"/>
                                </svg>
                            </span>
                            <span>Status History</span>
                        </a>
                    @endif
                    @if($user->hasPermission('visits.manage'))
                        <a class="nav-link {{ request()->routeIs('visits') ? 'active' : '' }}" href="{{ route('visits') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 21s7-6 7-11a7 7 0 0 0-14 0c0 5 7 11 7 11z"/>
                                    <circle cx="12" cy="10" r="2.5"/>
                                </svg>
                            </span>
                            <span>Visits</span>
                        </a>
                    @endif
                    @if($user->hasPermission('visit_reports.manage'))
                        <a class="nav-link {{ request()->routeIs('visit-reports') ? 'active' : '' }}" href="{{ route('visit-reports') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 19V5a2 2 0 0 1 2-2h10l4 4v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/>
                                    <path d="M8 13h8M8 17h5M14 3v4h4"/>
                                </svg>
                            </span>
                            <span>Visit Reports</span>
                        </a>
                    @endif
                    @if($user->hasPermission('report_tracking.view'))
                        <a class="nav-link {{ request()->routeIs('report-tracking') ? 'active' : '' }}" href="{{ route('report-tracking') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 5h16v14H4z"/>
                                    <path d="M8 9h8M8 13h5M8 17h3"/>
                                </svg>
                            </span>
                            <span>Report Tracking</span>
                        </a>
                    @endif
                </nav>
            </div>
        @endif
        @if($user && $user->hasAnyPermission(['users.manage','roles.manage','settings.manage','stores.manage','assignments.manage','areas.manage','area_mapping.view','store_statuses.manage','condition_types.manage']))
            <div class="nav-section">
                <div class="nav-label">Master Data</div>
                <nav class="nav">
                    @if($user->hasPermission('users.manage'))
                        <a class="nav-link {{ request()->routeIs('users') ? 'active' : '' }}" href="{{ route('users') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M16 11a4 4 0 1 0-8 0 4 4 0 0 0 8 0z"/>
                                    <path d="M6 20a6 6 0 0 1 12 0"/>
                                </svg>
                            </span>
                            <span>Users</span>
                        </a>
                    @endif
                    @if($user->hasPermission('roles.manage'))
                        <a class="nav-link {{ request()->routeIs('roles') ? 'active' : '' }}" href="{{ route('roles') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 3l7 4v5c0 5-3.5 7.5-7 9-3.5-1.5-7-4-7-9V7z"/>
                                    <path d="M9 12l2 2 4-4"/>
                                </svg>
                            </span>
                            <span>Roles</span>
                        </a>
                    @endif
                    @if($user->hasPermission('store_statuses.manage'))
                        <a class="nav-link {{ request()->routeIs('store-statuses') ? 'active' : '' }}" href="{{ route('store-statuses') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 7h16M4 12h16M4 17h16"/>
                                    <circle cx="9" cy="7" r="2"/>
                                </svg>
                            </span>
                            <span>Status Toko</span>
                        </a>
                    @endif
                    @if($user->hasPermission('condition_types.manage'))
                        <a class="nav-link {{ request()->routeIs('condition-types') ? 'active' : '' }}" href="{{ route('condition-types') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 6h16v12H4z"/>
                                    <path d="M8 10h8M8 14h5"/>
                                </svg>
                            </span>
                            <span>Kondisi Toko</span>
                        </a>
                    @endif
                    @if($user->hasPermission('settings.manage'))
                        <a class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}" href="{{ route('settings') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7z"/>
                                    <path d="M19.4 15a1.7 1.7 0 0 0 .33 1.87l.05.05a2 2 0 1 1-2.83 2.83l-.05-.05A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .31 1.7 1.7 0 0 0-.8 1.46V21a2 2 0 1 1-4 0v-.08a1.7 1.7 0 0 0-1.8-1.77 1.7 1.7 0 0 0-1 .31l-.05.05a2 2 0 1 1-2.83-2.83l.05-.05A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.31-1 1.7 1.7 0 0 0-1.46-.8H2.8a2 2 0 1 1 0-4h.08a1.7 1.7 0 0 0 1.77-1.8 1.7 1.7 0 0 0-.31-1l-.05-.05A2 2 0 1 1 7.12 3.5l.05.05A1.7 1.7 0 0 0 9 4.6c.37 0 .73-.11 1-.31a1.7 1.7 0 0 0 .8-1.46V2.8a2 2 0 1 1 4 0v.08a1.7 1.7 0 0 0 1.8 1.77c.37 0 .73-.11 1-.31l.05-.05A2 2 0 1 1 20.5 7.12l-.05.05A1.7 1.7 0 0 0 19.4 9c0 .37.11.73.31 1 .2.27.31.63.31 1s-.11.73-.31 1a1.7 1.7 0 0 0-.31 1z"/>
                                </svg>
                            </span>
                            <span>Settings</span>
                        </a>
                    @endif
                    @if($user->hasPermission('stores.manage'))
                        <a class="nav-link {{ request()->routeIs('stores') ? 'active' : '' }}" href="{{ route('stores') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 21V8l8-5 8 5v13"/>
                                    <path d="M9 21v-6h6v6"/>
                                </svg>
                            </span>
                            <span>Stores</span>
                        </a>
                    @endif
                    @if($user->hasPermission('assignments.manage'))
                        <a class="nav-link {{ request()->routeIs('assignments') ? 'active' : '' }}" href="{{ route('assignments') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M9 3h6l1 2h4v16H4V5h4z"/>
                                    <path d="M9 12h6M9 16h6"/>
                                </svg>
                            </span>
                            <span>Assignments</span>
                        </a>
                    @endif
                    @if($user->hasPermission('areas.manage'))
                        <a class="nav-link {{ request()->routeIs('areas') ? 'active' : '' }}" href="{{ route('areas') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M3 6l7-3 7 3 4-2v14l-4 2-7-3-7 3-3-1z"/>
                                    <path d="M10 3v14M17 6v14"/>
                                </svg>
                            </span>
                            <span>Areas</span>
                        </a>
                    @endif
                    @if($user->hasPermission('area_mapping.view'))
                        <a class="nav-link {{ request()->routeIs('area-mapping') ? 'active' : '' }}" href="{{ route('area-mapping') }}" wire:navigate>
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M3 6l7-3 7 3 4-2v14l-4 2-7-3-7 3-3-1z"/>
                                    <circle cx="12" cy="12" r="2"/>
                                </svg>
                            </span>
                            <span>Area Mapping</span>
                        </a>
                    @endif
                </nav>
            </div>
        @endif
    </aside>
    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <div class="page-title">{{ $title ?? '' }}</div>
                @if(!empty($subtitle))
                    <div class="page-subtitle">{{ $subtitle }}</div>
                @endif
            </div>
            <div class="topbar-right">
                <div class="topbar-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="M20 20l-3.5-3.5"/>
                    </svg>
                    <input type="text" placeholder="Global search...">
                </div>
                <button class="theme-toggle" type="button" data-theme-toggle>
                    <svg class="theme-icon sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>
                    </svg>
                    <svg class="theme-icon moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M20 14.5A8.5 8.5 0 1 1 9.5 4a7 7 0 0 0 10.5 10.5z"/>
                    </svg>
                </button>
                <div class="topbar-avatar">
                    @if($user?->profile_photo_url)
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user?->full_name }}">
                    @else
                        <div class="avatar-circle">{{ strtoupper(substr($user?->full_name ?? 'U', 0, 1)) }}</div>
                    @endif
                </div>
                <div class="topbar-user">
                    <div class="user-name">{{ $user?->full_name }}</div>
                    <div class="user-role">{{ $user?->roles->pluck('name')->implode(', ') }}</div>
                </div>
                <a class="btn btn-outline" href="{{ route('profile') }}" wire:navigate>Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-dark" type="submit">Logout</button>
                </form>
            </div>
        </header>
        <main class="content">
            {{ $slot }}
        </main>
    </div>
</div>
@livewireScripts
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
function initThemeToggle() {
    if (window.__themeInit) {
        return
    }
    window.__themeInit = true
    const stored = localStorage.getItem('cr_theme')
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
    const startDark = stored ? stored === 'dark' : prefersDark
    document.body.classList.toggle('theme-dark', startDark)

    document.addEventListener('click', function (event) {
        const toggle = event.target.closest('[data-theme-toggle]')
        if (!toggle) {
            return
        }
        const nextDark = !document.body.classList.contains('theme-dark')
        document.body.classList.toggle('theme-dark', nextDark)
        localStorage.setItem('cr_theme', nextDark ? 'dark' : 'light')
    })
}

let storeMap
let storeMarker
let reverseTimer

function initStoreMap() {
    const el = document.getElementById('store-map')
    if (!el || !window.L) {
        return
    }
    const componentEl = el.closest('[wire\\:id]')
    const componentId = componentEl ? componentEl.getAttribute('wire:id') : null
    if (storeMap && storeMap._container !== el) {
        storeMap.remove()
        storeMap = null
        storeMarker = null
    }
    if (storeMap) {
        storeMap.invalidateSize()
        return
    }
    const latInput = document.querySelector('[data-store-lat]')
    const lngInput = document.querySelector('[data-store-lng]')
    const fallbackLat = -6.2000000
    const fallbackLng = 106.8166667
    const startLat = parseFloat(latInput?.value) || fallbackLat
    const startLng = parseFloat(lngInput?.value) || fallbackLng
    storeMap = L.map(el).setView([startLat, startLng], 13)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(storeMap)
    storeMarker = L.marker([startLat, startLng], { draggable: true }).addTo(storeMap)
    storeMarker.on('dragend', function (event) {
        const pos = event.target.getLatLng()
        setLatLng(pos.lat, pos.lng)
    })
    storeMap.on('click', function (event) {
        setLatLng(event.latlng.lat, event.latlng.lng)
        storeMarker.setLatLng(event.latlng)
    })

    function setLatLng(lat, lng) {
        if (latInput) {
            latInput.value = Number(lat).toFixed(7)
            latInput.dispatchEvent(new Event('input', { bubbles: true }))
        }
        if (lngInput) {
            lngInput.value = Number(lng).toFixed(7)
            lngInput.dispatchEvent(new Event('input', { bubbles: true }))
        }
        scheduleReverse(lat, lng)
    }

    function syncFromInputs() {
        const lat = parseFloat(latInput?.value)
        const lng = parseFloat(lngInput?.value)
        if (Number.isFinite(lat) && Number.isFinite(lng)) {
            storeMarker.setLatLng([lat, lng])
            storeMap.setView([lat, lng], storeMap.getZoom())
            scheduleReverse(lat, lng)
        }
    }

    latInput?.addEventListener('change', syncFromInputs)
    lngInput?.addEventListener('change', syncFromInputs)

    function scheduleReverse(lat, lng) {
        if (!componentId || !window.Livewire) {
            return
        }
        clearTimeout(reverseTimer)
        reverseTimer = setTimeout(function () {
            const component = window.Livewire.find(componentId)
            if (component) {
                component.call('reverseGeocode', Number(lat), Number(lng))
            }
        }, 600)
    }
}

document.addEventListener('DOMContentLoaded', function () {
    initThemeToggle()
    initStoreMap()
})
document.addEventListener('livewire:navigated', function () {
    initThemeToggle()
    initStoreMap()
})
window.addEventListener('store-map:set', function (event) {
    initStoreMap()
    if (!storeMap) {
        return
    }
    const lat = parseFloat(event.detail?.lat)
    const lng = parseFloat(event.detail?.lng)
    if (Number.isFinite(lat) && Number.isFinite(lng)) {
        storeMap.setView([lat, lng], 14)
        if (storeMarker) {
            storeMarker.setLatLng([lat, lng])
        } else {
            storeMarker = L.marker([lat, lng]).addTo(storeMap)
        }
    }
})
</script>
</body>
</html>
