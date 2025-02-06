<?php

use App\Models\Imobiliaria;
use App\Services\SearchService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Volt\Component;

new class extends Component
{
    public string $searchString = '';

    public Collection $clientList;

    public function mount(Imobiliaria $imobiliaria)
    {
        $this->clientList = $imobiliaria->clients;
    }

    public function with(SearchService $search)
    {
        return [
            'clients' => $search->clientSearch($this->clientList, $this->searchString),
        ];
    }
}; ?>


<div>
    <div class="flex gap-2">
        <x-input type="text" id="searchBar" wire:model.live.debounce="searchString" class="flex-1" placeholder="Pesquisar (Nome, CPF, E-mail)" />
        <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
    </div>
    <div class="bg-white rounded shadow h-[40rem] overflow-y-scroll">
        <div class="flex flex-col gap-4 p-4">
            @forelse ($clients as $client)
                <button
                    wire:key="{{ $client->id }}"
                    wire:click="$parent.updateClient({{ $client?->id }})"
                    @@click="$dispatch('close')"
                    wire:navigate
                    class="flex w-full px-4 py-2 space-x-2 bg-white border rounded shadow"
                >
                    <div class="mr-2">
                        <x-avatar xl label="C" class="!bg-gray-700" />
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Nome:</span>
                        <span class="block">{{ $client->name ?? "" }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">CPF:</span>
                        <span class="block">{{ App\Utils\StringUtils::cpfFormat($client->cpf ?? "") }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">E-mail:</span>
                        <span class="block">{{ Str::limit($client->email, 22) }}</span>
                    </div>
                </button>
            @empty
                <x-alert title="Nenhum cliente foi encontrado" />
            @endforelse
        </div>
    </div>
</div>
