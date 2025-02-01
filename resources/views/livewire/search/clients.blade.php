<?php

use Livewire\Volt\Component;

function cpfFormat($value): string
{
    $CPF_LENGTH = 11;
    $cnpj_cpf = preg_replace('/\D/', '', $value);

    if (strlen($cnpj_cpf) === $CPF_LENGTH) {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', "\$1.\$2.\$3-\$4", $cnpj_cpf);
    }

    return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}

function clientSearch($clients, $searchString, $clientType = null)
{
    return $clients->filter(function ($client) use ($searchString, $clientType) {
        $verdict = true;

        // data
        $name = $client->name ?? '';
        $email = $client->email ?? '';
        $cpf = $client->cpf ?? '';

        // formatted queries
        $haystack = preg_replace('[.,]', '', strtolower("$name $email $cpf"));
        $needle = preg_replace('[.,]', '', strtolower($searchString ?? ''));

        // search filter
        $verdict = str_contains($haystack, $needle);

        // type filter
        if (isset($clientType)) {
            $verdict = $verdict && (string) $client->type === (string) $clientType;
        }

        return $verdict;
    });
}

new class extends Component {
    /**
     * Summary of imoveis
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\Client>
     */
    public $clients;
    public $clientType;
    public $searchString;
    public function mount()
    {
        $this->clients = current_imobiliaria()->clients;
    }
}; ?>

<div class="flex flex-col space-y-2">
    <div class="flex gap-2">
        <x-input type="text" id="searchBar" wire:model.live.debounce='searchString' class="flex-1"
            placeholder="Pesquisar (Nome, CPF, E-mail)" />
        <x-select placeholder="Selecione" wire:model.live='clientType' class="w-min">
            <x-select.option label="Locador" value="0" />
            <x-select.option label="Vendedor" value="1" />
        </x-select>
        <x-regular-button label="Cadastrar" href="{{ route('client.new') }}" />
    </div>
    <div class="h-full bg-white rounded shadow">
        <div class="flex flex-col gap-4 p-4 overflow-scroll h-[40rem]">
            @foreach (clientSearch($clients, $searchString, $clientType) as $client)
                <a href="{{ route('client.info', ['client' => $client->id]) }}"
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
