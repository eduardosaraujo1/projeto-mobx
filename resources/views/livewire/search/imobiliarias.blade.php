<?php

use App\Models\Imobiliaria;
use App\Services\SearchService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Summary of imoveis
     *
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\User>
     */
    public $imobiliariaList;

    public $searchString;

    public function with(SearchService $search)
    {
        // perform search
        $filteredList = $search->imobiliariaSearch($this->imobiliariaList, $this->searchString ?? '');
        $formatted = $this->imobiliariaFormat($filteredList);

        return [
            'imobiliarias' => $formatted,
        ];
    }

    public function mount()
    {
        $this->imobiliariaList = Imobiliaria::all();
    }

    public function imobiliariaFormat(Collection $imobiliarias): array
    {
        return $imobiliarias->map(function (Imobiliaria $imobiliaria) {
            $arr = $imobiliaria->toArray();
            $arr['address'] = substr($arr['address'], 0, 20);
            $arr['updated_at'] = Carbon::create($arr['updated_at'])
                ->setTimezone('America/Sao_Paulo')
                ->format('d-m-Y H:i:s');

            return $arr;
        })->toArray();
    }
}; ?>


<div class="flex flex-col space-y-2">
    <div class="flex gap-2">
        <x-input type="text" id="searchBar" wire:model.live.debounce="searchString" class="flex-1" placeholder="Pesquisar (Nome ou E-mail)" />
        <x-primary-button href="{{ route('imobiliaria.new') }}">Cadastrar</x-primary-button>
    </div>
    <div class="h-full bg-white rounded shadow">
        <div class="flex flex-col gap-1 p-4 overflow-scroll h-[40rem]">
            @forelse ($imobiliarias as $imobiliaria)
                <a
                    class="flex w-full px-4 py-2 space-x-2 bg-white border rounded shadow-sm"
                    href="{{ route("imobiliaria.info", ["imobiliaria" => $imobiliaria["id"]]) }}"
                    wire:key="{{ $imobiliaria["id"] }}"
                    wire:navigate
                >
                    <div class="mr-2">
                        <img src="{{ asset("images/placeholder-imobiliaria.png") }}" alt="Imobiliaria Logo" class="w-16 rounded aspect-square" />
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Nome:</span>
                        <span class="block">{{ $imobiliaria["name"] }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Endereço:</span>
                        <span class="block">{{ $imobiliaria["address"] }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">E-mail:</span>
                        <span class="block">{{ $imobiliaria["email"] }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Contato:</span>
                        <span class="block">{{ $imobiliaria["contact"] }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Ultima Atualização</span>
                        <span class="block">{{ $imobiliaria["updated_at"] }}</span>
                    </div>
                </a>
            @empty
                <x-alert title="Nenhuma imobiliaria foi encontrada" />
            @endforelse
        </div>
    </div>
</div>
