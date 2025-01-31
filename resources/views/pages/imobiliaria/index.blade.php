@props(['imobiliaria' => null])

<x-app-layout>
    @if (isset($imobiliaria))
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Imobiliaria' }}
        </h2>
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ $imobiliaria->name }}
            </div>
        </div>
    @else
        <x-alert
            title="Você não está em nenhuma imobiliária. Contate seu administrador para participar de sua primeira imobiliaria"
            negative />
    @endif
</x-app-layout>
