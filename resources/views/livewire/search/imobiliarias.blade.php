<?php

use App\Models\Imobiliaria;
use App\Services\SearchService;
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
        return [
            'imobiliarias' => $search->imobiliariaSearch($this->imobiliariaList, $this->searchString ?? ''),
        ];
    }

    public function mount()
    {
        $this->imobiliariaList = Imobiliaria::all();
    }
}; ?>


<div class="flex flex-col space-y-2">
    <div class="flex gap-2">
        <x-input type="text" id="searchBar" wire:model.live.debounce="searchString" class="flex-1" placeholder="Pesquisar (Nome ou E-mail)" />
        <x-primary-button href="{{ route('imobiliaria.new') }}">Cadastrar</x-primary-button>
    </div>
    <div class="h-full bg-white rounded shadow">
        <div class="flex flex-col gap-4 p-4 overflow-scroll h-[40rem]">
            @foreach ($imobiliarias as $imobiliaria)
                <a href="{{ route("imobiliaria.info", ["imobiliaria" => $imobiliaria->id]) }}" class="flex w-full px-4 py-2 space-x-2 bg-white border rounded shadow">
                    <div class="mr-2">
                        <img src="{{ asset("images/placeholder-imobiliaria.png") }}" alt="Imobiliaria Logo" class="w-16 rounded shadow aspect-square" />
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Nome:</span>
                        <span class="block">{{ $imobiliaria->name }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Endereço</span>
                        <span class="block">{{ Str::limit($imobiliaria->address, 50) }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">E-mail:</span>
                        <span class="block">{{ $imobiliaria->email }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Contato:</span>
                        <span class="block">{{ $imobiliaria->contact }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Ultima Atualização</span>
                        <span class="block">{{ $imobiliaria->updated_at->format("d/m/Y H:i:s") }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
