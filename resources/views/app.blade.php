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
    <body class="font-sans antialiased bg-[#0a0a0a] text-white">
        @inertia
    </body>
</html>
