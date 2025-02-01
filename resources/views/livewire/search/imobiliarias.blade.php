<?php

use Livewire\Volt\Component;
use App\Models\Imobiliaria;

/**
 * Summary of imobiliariaSearch
 * @param \Illuminate\Database\Eloquent\Collection<Imobiliaria> $imobiliarias
 * @param string $searchString
 * @param string $searchType
 * @return \Illuminate\Database\Eloquent\Collection<Imobiliaria>
 */
function imobiliariaSearch($imobiliarias, $searchString)
{
    return $imobiliarias->filter(function (Imobiliaria $imobiliaria) use ($searchString) {
        $verdict = true;

        // data
        $imobiliariaName = strtolower($imobiliaria->name ?? '');
        $imobiliariaEmail = strtolower($imobiliaria->email ?? '');
        $imobiliariaContact = strtolower($imobiliaria->contact ?? '');

        // formatted queries
        $haystack = "$imobiliariaName $imobiliariaEmail $imobiliariaContact";
        $needle = $searchString ?? '';

        // search filter
        $verdict = str_contains($haystack, $needle);

        return $verdict;
    });
}

new class extends Component {
    /**
     * Summary of imoveis
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\User>
     */
    public $imobiliarias;
    public $searchString;
    public function mount()
    {
        $this->imobiliarias = Imobiliaria::all();
    }
}; ?>

<div class="flex flex-col space-y-2">
    <div class="flex gap-2">
        <x-input type="text" id="searchBar" wire:model.live.debounce='searchString' class="flex-1"
            placeholder="Pesquisar (Nome, Contato ou E-mail)" />
        <x-regular-button label="Cadastrar" href="{{ route('imobiliaria.new') }}" />
    </div>
    <div class="h-full bg-white rounded shadow">
        <div class="flex flex-col gap-4 p-4 overflow-scroll h-[40rem]">
            @foreach (imobiliariaSearch($imobiliarias, $searchString) as $imobiliaria)
                <a href="#" class="flex w-full px-4 py-2 space-x-2 bg-white border rounded shadow ">
                    <div class="mr-2">
                        <img src="{{ asset('images/placeholder-imobiliaria.png') }}" alt="Imobiliaria Logo"
                            class="w-16 rounded shadow aspect-square">
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
                        <span class="block">{{ $imobiliaria->updated_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
