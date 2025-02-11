<?php

use App\Models\Client;
use App\Models\Imobiliaria;
use App\Services\SearchService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    /**
     * Summary of imoveis
     *
     * @var Collection<Client>
     */
    public Collection $clientList;

    public $searchString;

    public function mount(Imobiliaria $imobiliaria)
    {
        $this->clientList = $imobiliaria->clients;
    }

    public function with(SearchService $search)
    {
        return [
            'clients' => $search->clientSearch($this->clientList, $this->searchString ?? ''),
        ];
    }
}; ?>


<div class="space-y-2">
    <h2 class="my-4 text-4xl font-semibold leading-tight">Clientes</h2>
    <div class="flex gap-2">
        <x-input type="text" id="searchBar" wire:model.live.debounce="searchString" class="flex-1" placeholder="Pesquisar (Nome, CPF, E-mail)" />
        @can("create", Client::class)
            <x-primary-button href="{{ route('client.new') }}" wire:navigate>Cadastrar</x-primary-button>
        @endcan
    </div>
    <div class="bg-white rounded shadow h-[40rem] overflow-y-scroll">
        <div class="flex flex-col gap-1 p-4">
            @forelse ($clients as $client)
                <a
                    class="flex w-full px-4 py-2 space-x-2 bg-white border rounded shadow-sm"
                    href="{{ route("client.info", ["client" => $client->id]) }}"
                    wire:navigate
                    wire:key="{{ $client->id }}"
                >
                    <div class="me-2">
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
                </a>
            @empty
                <x-alert title="Nenhum cliente foi encontrado" />
            @endforelse
        </div>
    </div>
</div>
