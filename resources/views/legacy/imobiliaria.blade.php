<x-legacy-layout title="Sua Imobiliaria">
    <!-- Conteúdo Principal -->
    <div class="flex-1 p-12 ml-64">
        <!-- Cabeçalho da Página -->
        <header class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">Sua Imobiliária</h1>
            <p class="text-gray-500 mt-2">Informações detalhadas sobre a sua imobiliária</p>
        </header>

        <!-- Informações da Imobiliária -->
        <section class="bg-white p-8 rounded-lg shadow-md max-w-3xl mx-auto space-y-6">
            <!-- Logo da Imobiliária -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/casa1.jpg') }}" alt="Logo da Imobiliária" class="w-32 h-32 object-contain">
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-700">Nome da Imobiliária:</h2>
                <p class="text-gray-600">Imobiliária Exemplo</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-700">Endereço:</h2>
                <p class="text-gray-600">Rua Exemplo, 123, Centro, Cidade, Estado</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-700">Telefone:</h2>
                <p class="text-gray-600">(11) 1234-5678</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-700">E-mail:</h2>
                <p class="text-gray-600">contato@imobiliariaexemplo.com</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-700">Sobre Nós:</h2>
                <p class="text-gray-600">A Imobiliária Exemplo oferece os melhores serviços para você encontrar o imóvel
                    dos seus sonhos. Temos uma equipe qualificada e uma ampla variedade de imóveis disponíveis.</p>
            </div>
        </section>
    </div>
</x-legacy-layout>
