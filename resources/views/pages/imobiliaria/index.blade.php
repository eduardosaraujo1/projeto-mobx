@php
    use App\Services\ImobiliariaService;
    $imobiliaria = ImobiliariaService::current_imobiliaria();
@endphp
<x-app-layout>
    <x-slot name="heading">
        Minha Imobiliária
    </x-slot>
    <x-card>
        <ul class="space-y-4">
            {{-- TODO: change to form with edit properties --}}
            <li>
                <span class="block text-xl font-bold">Nome da Imobiliária:</span>
                <span class="block text-gray-600">{{ $imobiliaria->name }}</span>
            </li>
            <li>
                <span class="block text-xl font-bold">Endereço:</span>
                <span class="block text-gray-600">{{ $imobiliaria->address }}</span>
            </li>
            <li>
                <span class="block text-xl font-bold">Email:</span>
                <span class="block text-gray-600">{{ $imobiliaria->email }}</span>
            </li>
            <li>
                <span class="block text-xl font-bold">Contato:</span>
                <span class="block text-gray-600">{{ $imobiliaria->contact }}</span>
            </li>
        </ul>
    </x-card>
</x-app-layout>
