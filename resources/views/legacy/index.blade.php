<x-legacy-layout title="Mobx - Sua Imobiliaria">
    <!-- Conteúdo Principal -->
    <div class="flex-1 p-12 ml-64 transition-opacity duration-700 ease-in opacity-0 sm:ml-1/3 lg:ml-64" id="main-content">
        <header class="flex flex-col items-center mb-12">
            <!-- Logotipo com animação ao passar o mouse -->
            <img src="{{ asset('images/mobx.svg') }}" alt="Logo Imobiliária"
                class="w-32 h-32 mb-4 transition-transform duration-300 bg-black rounded-full shadow-lg hover:scale-110">
            <h1 class="text-4xl font-bold text-center text-gray-800">Bem-vindo à Mobx</h1>
            <p class="mt-2 text-gray-500">Encontre e gerencie seus imóveis de forma simples e intuitiva.</p>
        </header>
        <!-- Links de ação -->
        <div class="grid gap-6 sm:grid-cols-2">
            <a href="{{ route('legacy.imoveis') }}"
                class="block p-6 text-center transition bg-white border border-gray-300 rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">Ver Imóveis Cadastrados</h2>
            </a>
            <a href="{{ route('legacy.cadastro') }}"
                class="block p-6 text-center transition bg-white border border-gray-300 rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-100">
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
