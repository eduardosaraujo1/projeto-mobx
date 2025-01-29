<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Importando Tailwind CSS para estilização rápida e responsiva -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen overflow-hidden font-sans bg-gray-50">
    <!-- Navbar Lateral (Responsiva) -->
    <nav
        class="fixed z-50 flex flex-col w-64 h-full p-6 space-y-6 text-gray-800 bg-white border-r border-gray-200 shadow-lg sm:w-1/3 lg:w-64">
        <div class="flex items-center mb-8">
            <img src="{{ asset('images/mobx.svg') }}" alt="Logo Mobx"
                class="w-12 h-12 mr-4 bg-black rounded-full shadow-md">
            <h2 class="text-2xl font-bold">Mobx</h2>
        </div>
        <!-- Links de navegação -->
        <ul class="space-y-4">
            <li><a href="{{ route('legacy.index') }}" class="legacy-nav-link">Início</a></li>
            <li><a href="{{ route('legacy.imoveis') }}" class="legacy-nav-link">Imóveis</a></li>
            <li><a href="{{ route('legacy.cadastro') }}" class="legacy-nav-link">Cadastrar Imóveis</a></li>
            <li><a href="{{ route('legacy.imobiliaria') }}" class="legacy-nav-link">Sua Imobiliária</a></li>
            <li><a href="{{ route('legacy.configuracoes') }}" class="legacy-nav-link">Configurações</a></li>
        </ul>
    </nav>

    {{ $slot }}
</body>

</html>
