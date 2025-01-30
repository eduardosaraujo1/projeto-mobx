<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <wireui:scripts />
</head>

<body>
    <div class="flex min-h-screen bg-gray-50">
        <livewire:layout.sidebar />
        <div class="flex-1 p-12 ml-64">
            {{ $slot }}
        </div>
        <livewire:layout.profile-bar />
    </div>
</body>

</html>
