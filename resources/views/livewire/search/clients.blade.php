<?php

use Livewire\Volt\Component;
use App\Services\ImobiliariaService;
use App\Models\Client;
use Livewire\Attributes\Layout;

function cpfFormat($value): string
{
    $CPF_LENGTH = 11;
    $cnpj_cpf = preg_replace('/\D/', '', $value);

    if (strlen($cnpj_cpf) === $CPF_LENGTH) {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', "\$1.\$2.\$3-\$4", $cnpj_cpf);
    }

    return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}

new #[Layout('layouts.app')] class extends Component {
    /**
     * Summary of imoveis
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\Client>
     */
    public $clientsFull;
    public $clientType;
    public $searchString;

    public function mount()
    {
        $this->clientsFull = ImobiliariaService::current_imobiliaria()->clients;
    }

    public function with()
    {
        return [
            'clients' => $this->clientSearch(),
        ];
    }

    public function clientSearch()
    {
        return $this->clientsFull->filter(function ($client) {
            $verdict = true;

            // data
            $name = $client->name ?? '';
            $email = $client->email ?? '';
            $cpf = $client->cpf ?? '';

            // formatted queries
            $haystack = preg_replace('[.,]', '', strtolower("$name $email $cpf"));
            $needle = preg_replace('[.,]', '', strtolower($this->searchString ?? ''));

            // search filter
            $verdict = str_contains($haystack, $needle);

            // type filter
            if (isset($this->clientType)) {
                $verdict = $verdict && (string) $client->type === (string) $this->clientType;
            }

            return $verdict;
        });
    }
}; ?>

<div class="space-y-2">
    <x-slot name="heading">
        Clientes
    </x-slot>
    <div class="flex gap-2">
        <x-input type="text" id="searchBar" wire:model.live.debounce='searchString' class="flex-1"
            placeholder="Pesquisar (Nome, CPF, E-mail)" />
        <x-select placeholder="Selecione" wire:model.live='clientType' class="w-min">
            <x-select.option label="Locador" value="0" />
            <x-select.option label="Vendedor" value="1" />
        </x-select>
        @can('create', Client::class)
            <x-primary-button href="{{ route('client.new') }}">Cadastrar</x-primary-button>
        @endcan
    </div>
    <div class="h-full bg-white rounded shadow">
        <div class="flex flex-col gap-4 p-4 overflow-scroll h-[40rem]">
            @foreach ($clients as $client)
                <a href="{{ route('client.info', ['client' => $client->id]) }}" wire:navigate
                    class="flex w-full px-4 py-2 space-x-2 bg-white border rounded shadow">
                    <div class="mr-2">
                        <x-avatar xl label="C" class="!bg-gray-700" />
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Nome:</span>
                        <span class="block">{{ $client->name ?? '' }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">CPF:</span>
                        <span class="block">{{ cpfFormat($client->cpf ?? '') }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">E-mail:</span>
                        <span class="block">{{ Str::limit($client->email, 22) }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Tipo: </span>
                        <span class="block">{{ $client->typeName() }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
