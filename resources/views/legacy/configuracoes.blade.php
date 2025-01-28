<x-legacy-layout title="Configurações">
    <!-- Conteúdo Principal -->
    <div class="flex-1 p-12 ml-64">
        <!-- Cabeçalho da Página -->
        <header class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">Configurações</h1>
            <p class="text-gray-500 mt-2">Gerencie as informações da sua imobiliária</p>
        </header>

        <!-- Formulário de Configurações -->
        <section
            class="bg-white p-8 rounded-lg shadow-md max-w-3xl mx-auto space-y-6 transition-transform duration-300 hover:shadow-lg">
            <form action="#" method="POST">
                <div class="mb-4">
                    <label for="nome" class="block text-lg font-medium text-gray-700">Nome da Imobiliária:</label>
                    <input type="text" id="nome" name="nome"
                        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                        placeholder="Nome da sua imobiliária">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-lg font-medium text-gray-700">E-mail de Contato:</label>
                    <input type="email" id="email" name="email"
                        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                        placeholder="contato@imobiliaria.com">
                </div>

                <div class="mb-4">
                    <label for="telefone" class="block text-lg font-medium text-gray-700">Telefone:</label>
                    <input type="tel" id="telefone" name="telefone"
                        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                        placeholder="(11) 1234-5678">
                </div>

                <div class="mb-4">
                    <label for="endereco" class="block text-lg font-medium text-gray-700">Endereço:</label>
                    <input type="text" id="endereco" name="endereco"
                        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                        placeholder="Endereço completo">
                </div>

                <div class="mb-6">
                    <label for="descricao" class="block text-lg font-medium text-gray-700">Descrição:</label>
                    <textarea id="descricao" name="descricao" rows="4"
                        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                        placeholder="Breve descrição sobre a sua imobiliária..."></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white font-medium p-3 rounded-md hover:bg-blue-700 transition-transform duration-200 hover:scale-105 active:scale-95">
                    Salvar Alterações
                </button>
            </form>
        </section>
    </div>

    <!-- Script para adicionar animação de opacidade após carregar -->
    <script>
        // Animação para mostrar o conteúdo ao carregar
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.getElementById('main-content');
            content.classList.remove('opacity-0');
        });
    </script>
</x-legacy-layout>
