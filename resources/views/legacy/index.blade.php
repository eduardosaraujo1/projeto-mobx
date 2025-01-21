<x-legacy-layout>
    <x-slot:title>
        Mobx - Sua Imobiliária
    </x-slot:title>

    <!-- Conteúdo Principal -->
    <div class="flex-1 p-12 ml-64 sm:ml-1/3 lg:ml-64 transition-opacity duration-700 ease-in opacity-0" id="main-content">
        <header class="flex flex-col items-center mb-12">
            <!-- Logotipo com animação ao passar o mouse -->
            <img src="{{ asset('images/mobx.png') }}" alt="Logo Imobiliária"
                class="w-32 h-32 mb-4 rounded-full shadow-lg transition-transform duration-300 hover:scale-110">
            <h1 class="text-4xl font-bold text-gray-800 text-center">Bem-vindo à Mobx</h1>
            <p class="text-gray-500 mt-2">Encontre e gerencie seus imóveis de forma simples e intuitiva.</p>
        </header>
        <!-- Links de ação -->
        <div class="grid gap-6 sm:grid-cols-2">
            <a href="{{ route('legacy.imoveis') }}"
                class="block bg-white border border-gray-300 shadow-lg rounded-lg p-6 text-center hover:shadow-xl transition hover:bg-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">Ver Imóveis Cadastrados</h2>
            </a>
            <a href="{{ route('legacy.cadastro') }}"
                class="block bg-white border border-gray-300 shadow-lg rounded-lg p-6 text-center hover:shadow-xl transition hover:bg-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">Cadastrar Novo Imóvel</h2>
            </a>
        </div>
    </div>

    <!-- Script para animação de carregamento -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.getElementById('main-content');
            content.classList.remove('opacity-0');
        });
    </script>
</x-legacy-layout>
