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
    <div class="flex max-h-screen overflow-hidden bg-gray-50">
        <livewire:layout.sidebar />
        <div class="flex-1 overflow-scroll">
            <div class="flex flex-col h-full px-6 py-12 mx-auto max-w-7xl">
                @if ($message = session('message'))
                    <x-alert info title="{{ $message }}" />
                @elseif ($message = session('error'))
                    <x-alert negative title="{{ $message }}" />
                @endif
                @isset($heading)
                    <h2 {{ $heading->attributes->merge(['class' => 'my-4 text-4xl font-semibold leading-tight']) }}>
                        {{ $heading }}
                    </h2>
                @endisset

                {{ $slot }}
            </div>
        </div>
        <livewire:layout.profile-bar />
    </div>
</body>

</html>
