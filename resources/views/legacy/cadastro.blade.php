<x-legacy-layout title="Upload de Planilha Excel">
    <!-- Conteúdo Principal -->
    <div class="flex-1 p-12 ml-64">
        <header class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">Upload de Planilha Excel</h1>
            <p class="text-gray-500 mt-2">Carregue suas planilhas com facilidade e agilidade</p>
        </header>

        <form action="upload.php" method="POST" enctype="multipart/form-data"
            class="bg-white p-10 rounded-lg shadow-md max-w-lg mx-auto space-y-6">
            <div>
                <label for="fileInput" class="block text-lg font-medium text-gray-700 mb-2">Selecione a planilha Excel
                    (.xlsx)</label>
                <input type="file" id="fileInput" name="file" accept=".xlsx"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                            file:cursor-pointer file:bg-blue-600 file:text-white file:py-2 file:px-4 file:border-0 file:rounded-md file:transition-colors hover:file:bg-blue-700">
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-200">
                Carregar Planilha
            </button>
        </form>
    </div>
</x-legacy-layout>
