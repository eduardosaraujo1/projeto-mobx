<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobx - Sua Imobiliária</title>
    <!-- Importando Tailwind CSS para estilização rápida e responsiva -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Estilo personalizado para elementos específicos */
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.2s, transform 0.2s;
        }
        .nav-link:hover {
            background-color: #e5e7eb; /* Cinza claro */
            color: #1f2937;
            transform: scale(1.05);
        }
    </style>
</head>
<body class="flex bg-gray-50 min-h-screen font-sans overflow-hidden">
    
    <!-- Navbar Lateral (Responsiva) -->
    <nav class="w-64 bg-white text-gray-800 flex flex-col p-6 space-y-6 fixed h-full sm:w-1/3 lg:w-64 shadow-lg z-50 border-r border-gray-200">
        <div class="flex items-center mb-8">
            <img src="mobx.png" alt="Logo Mobx" class="w-12 h-12 mr-4 rounded-full shadow-md">
            <h2 class="text-2xl font-bold">Mobx</h2>
        </div>
        <!-- Links de navegação -->
        <ul class="space-y-4">
            <li><a href="index.html" class="nav-link">Início</a></li>
            <li><a href="imoveis.html" class="nav-link">Imóveis</a></li>
            <li><a href="cadastro.html" class="nav-link">Cadastrar Imóveis</a></li>
            <li><a href="sua_imobiliaria.html" class="nav-link">Sua Imobiliária</a></li>
            <li><a href="configuraçoes.html" class="nav-link">Configurações</a></li>
        </ul>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="flex-1 p-12 ml-64 sm:ml-1/3 lg:ml-64 transition-opacity duration-700 ease-in opacity-0" id="main-content">
        <header class="flex flex-col items-center mb-12">
            <!-- Logotipo com animação ao passar o mouse -->
            <img src="mobx.png" alt="Logo Imobiliária" class="w-32 h-32 mb-4 rounded-full shadow-lg transition-transform duration-300 hover:scale-110">
            <h1 class="text-4xl font-bold text-gray-800 text-center">Bem-vindo à Mobx</h1>
            <p class="text-gray-500 mt-2">Encontre e gerencie seus imóveis de forma simples e intuitiva.</p>
        </header>
        <!-- Links de ação -->
        <div class="grid gap-6 sm:grid-cols-2">
            <a href="imoveis.html" class="block bg-white border border-gray-300 shadow-lg rounded-lg p-6 text-center hover:shadow-xl transition hover:bg-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">Ver Imóveis Cadastrados</h2>
            </a>
            <a href="cadastrar.html" class="block bg-white border border-gray-300 shadow-lg rounded-lg p-6 text-center hover:shadow-xl transition hover:bg-gray-100">
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
</body>
</html>
