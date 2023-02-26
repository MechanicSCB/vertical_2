<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        {{-- <link rel="preload" href="{{ asset('fonts/circe/Regular.woff2') }}" as="font" crossorigin="anonymous">--}}
        {{-- <link rel="preload" href="{{ asset('fonts/circe/Bold.woff2') }}" as="font" crossorigin="anonymous">--}}
        {{-- <link rel="preload" href="{{ asset('fonts/circe/Black.woff2') }}" as="font" crossorigin="anonymous">--}}

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-ui-body text-ui-text-primary">
        @inertia
    </body>
</html>
