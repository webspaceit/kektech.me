<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ config('app.name', 'KekTech') }}</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
        @if(\App\Models\Setting::get()->favicon)
            <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get()->favicon }}">
        @endif
        @inertiaHead
    </head>
    <body class="font-sans antialiased text-white" style="background: linear-gradient(135deg, #0a1a12 0%, #0d2818 30%, #0f2d1b 50%, #0a1f14 75%, #0a0f0c 100%); min-height: 100vh;">
        @inertia
    </body>
</html>
