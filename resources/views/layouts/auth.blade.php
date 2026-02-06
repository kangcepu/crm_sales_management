<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $settings = \App\Models\Setting::allKeyed();
        $siteTitle = $settings['site_title'] ?? 'CR Sales';
        $siteFavicon = $settings['site_favicon'] ?? null;
    @endphp
    <title>{{ $siteTitle }} Login</title>
    @if($siteFavicon)
        <link rel="icon" href="{{ $siteFavicon }}">
    @endif
    <link rel="stylesheet" href="{{ asset('app.css') }}">
    @livewireStyles
</head>
<body class="body auth-body">
<div class="auth-wrap">
    {{ $slot }}
</div>
@livewireScripts
</body>
</html>
