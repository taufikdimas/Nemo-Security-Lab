<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#05070D">
    <meta name="description" content="{{ config('brand.site_description', 'SecureOps by Nemo Security: penetration testing, security assessment, incident response, and compliance audit.') }}">
    <title>{{ config('brand.site_title', 'SecureOps · Find the weaknesses before attackers do') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @include('partials.landing-styles')
</head>
<body>
    @include('partials.landing-bg')
    @include('partials.landing-svgs')
    @include('partials.navbar')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
    @include('partials.landing-scripts')
</body>
</html>
