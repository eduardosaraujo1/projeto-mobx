<?php

use Livewire\Volt\Component;
use App\Services\ImobiliariaService;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Imovel;

function currencyFormat(float $number)
{
    return 'R$ ' . number_format($number, 2, ',', '.');
}

new #[Layout('layouts.app')] class extends Component {
    /**
     * Summary of imoveis
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\Imovel>
     */
    public Collection $imoveisFull;
    public string $searchString;
    public ?string $imovelStatus;

    public function mount()
    {
        $this->imoveisFull = ImobiliariaService::current_imobiliaria()->imoveis;
    }

    public function with()
    {
        return [
            'imoveis' => $this->imovelSearch(),
        ];
    }

    public function imovelSearch()
    {
        return $this->imoveisFull->filter(function ($imovel) {
            $verdict = true;

            // data
            $address = $imovel->fullAddress() ?? '';
            $lado = $imovel->lado() ?? '';

            // formatted queries
            $haystack = strtolower("$address $lado");
            $needle = strtolower($this->searchString ?? '');

            // perform search
            $verdict = str_contains($haystack, $needle);

            // category filter
            if (isset($this->imovelStatus)) {
                $verdict = $verdict && (string) $imovel->status === (string) $this->imovelStatus;
            }

            return $verdict;
        });
    }
}; ?>

<div class="space-y-2">
    <x-slot name="heading">
        Imóveis Cadastrados
    </x-slot>
    <div class="flex gap-2">
        <x-input type="text" id="searchBar" wire:model.live.debounce='searchString' class="flex-1"
            placeholder="Pesquisar (Rua, localização ou status)" />
        <x-select placeholder="Selecione" wire:model.live='imovelStatus' class="w-min">
            <x-select.option label="Livre" value="0" />
            <x-select.option label="Alugado" value="1" />
            <x-select.option label="Vendido" value="2" />
        </x-select>
        <x-primary-button href="{{ route('imovel.new') }}" wire:navigate>Cadastrar</x-primary-button>
    </div>
    <div class="h-full bg-white rounded shadow">
        <div class="grid grid-cols-auto-fill-64 justify-center gap-4 p-4 overflow-scroll h-[40rem] auto-rows-min">
            @foreach ($imoveis as $imovel)
                <article class="relative w-64 p-5 bg-white rounded-lg shadow">
                    <div>
                        <img src="{{ $imovel->photo_path }}"
                            class="object-cover w-full h-48 bg-gray-300 bg-center bg-contain rounded-md aspect-square "
                            style="background-image:url('{{ asset('images/placeholder-image.svg') }}');">
                    </div>
                    <span class="inline mt-4 text-base">
                        <b>{{ $imovel->bairro }}</b> -
                        <span>{{ Str::limit($imovel->address_number . ' ' . $imovel->address_name, 20) }}</span>
                    </span>

                    {{-- Overlay de Informações --}}
                    <a href="{{ route('imovel.info', ['imovel' => $imovel->id]) }}" wire:navigate
                        class="absolute inset-0 flex flex-col items-start p-4 transition-opacity duration-300 bg-white rounded-lg opacity-0 bg-opacity-95 hover:opacity-100">
                        <div class="flex-1 overflow-hidden">
                            <h3 class="mb-2 text-xl font-bold">Detalhes do Imóvel</h3>
                            <ul class="space-y-1 leading-tight">
                                <li>
                                    <span class="font-bold">Preço:</span>
                                    <span>{{ currencyFormat($imovel->value ?? 0) }}</span>
                                </li>
                                <li>
                                    <span class="font-bold">IPTU:</span>
                                    <span> {{ currencyFormat($imovel->iptu ?? 0) }}/ano</span>
                                </li>
                                <li>
                                    <span class="font-bold">Endereço:</span>
                                    <span>{{ Str::limit($imovel->fullAddress() ?? 'N/A', 20) }}</span>
                                </li>
                                <li>
                                    <span class="font-bold">Localização:</span>
                                    <span>{{ $imovel->is_lado_praia ? 'Praia' : 'Morro' }}</span>
                                </li>
                                <li>
                                    <span class="font-bold">Status:</span>
                                    <span>{{ $imovel->statusName() }}</span>
                                </li>
                            </ul>
                        </div>
                        <x-primary-button>Acessar Imóvel</x-primary-button>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</div>
