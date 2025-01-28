<x-legacy-layout title="Minha Imobiliária - Imóveis">
    <!-- Conteúdo Principal -->
    <main class="flex-1 p-10 ml-64 transition-opacity duration-700 ease-in opacity-0" id="main-content">
        <header class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800">Imóveis Cadastrados</h1>
            <p class="text-gray-500 mt-2">Veja os imóveis disponíveis e suas informações detalhadas</p>
        </header>

        <!-- Lista de Imóveis -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <!-- Card de Imóvel -->
            <article
                class="relative bg-white rounded-lg shadow-md hover:scale-105 hover:shadow-lg transition-transform duration-300 p-5">
                <img src="{{ asset('images/casa2.jpg') }}" alt="Imagem do Apartamento no Centro"
                    class="w-full h-48 object-cover rounded-md">
                <h3 class="text-xl font-semibold text-gray-800 mt-4">Apartamento no Centro</h3>
                <p class="text-lg text-gray-900 font-bold mt-2">R$ 500.000</p>

                <!-- Sobreposição de Informações -->
                <div
                    class="absolute inset-0 bg-white bg-opacity-90 text-gray-800 opacity-0 hover:opacity-100 transition-opacity duration-300 rounded-lg p-4 flex flex-col justify-between shadow-lg">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Detalhes do Imóvel</h3>
                        <ul class="space-y-1 text-sm">
                            <li><strong>Preço:</strong> R$ 500.000</li>
                            <li><strong>IPTU:</strong> R$ 1.200/ano</li>
                            <li><strong>Endereço:</strong> Av. Principal, 123</li>
                            <li><strong>Tipo:</strong> Apartamento</li>
                            <li><strong>Localização:</strong> Lado Praia</li>
                            <li><strong>Status:</strong> Em Negociação</li>
                        </ul>
                    </div>
                    <button
                        class="mt-4 w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 transition-colors transform hover:scale-105 active:scale-95">
                        Acessar Imóvel
                    </button>
                </div>
            </article>

            <!-- Outro Card de Imóvel -->
            <article
                class="relative bg-white rounded-lg shadow-md hover:scale-105 hover:shadow-lg transition-transform duration-300 p-5">
                <img src="{{ asset('images/casa1.jpg') }}" alt="Imagem da Casa com Piscina"
                    class="w-full h-48 object-cover rounded-md">
                <h3 class="text-xl font-semibold text-gray-800 mt-4">Casa com Piscina</h3>
                <p class="text-lg text-gray-900 font-bold mt-2">R$ 750.000</p>

                <!-- Sobreposição de Informações -->
                <div
                    class="absolute inset-0 bg-white bg-opacity-90 text-gray-800 opacity-0 hover:opacity-100 transition-opacity duration-300 rounded-lg p-4 flex flex-col justify-between shadow-lg">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Detalhes do Imóvel</h3>
                        <ul class="space-y-1 text-sm">
                            <li><strong>Preço:</strong> R$ 750.000</li>
                            <li><strong>IPTU:</strong> R$ 1.500/ano</li>
                            <li><strong>Endereço:</strong> Rua das Flores, 456</li>
                            <li><strong>Tipo:</strong> Casa</li>
                            <li><strong>Localização:</strong> Lado Morro</li>
                            <li><strong>Status:</strong> Alugado</li>
                        </ul>
                    </div>
                    <button
                        class="mt-4 w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 transition-colors transform hover:scale-105 active:scale-95">
                        Acessar Imóvel
                    </button>
                </div>
            </article>

            <!-- Mais cards podem ser adicionados com a mesma estrutura -->
        </section>
    </main>

    <!-- Script para animação de carregamento -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.getElementById('main-content');
            content.classList.remove('opacity-0');
        });
    </script>
</x-legacy-layout>
