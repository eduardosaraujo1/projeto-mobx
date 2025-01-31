<?php

use Livewire\Volt\Component;
use App\Services\ImobiliariaService;
use App\Models\Imobiliaria;

function currencyFormat(float $number)
{
    return 'R$ ' . number_format($number, 2, ',', '.');
}

function imovelSearch($imoveis, $searchString)
{
    return $imoveis->filter(function ($imovel) use ($searchString) {
        // data
        $address = $imovel->fullAddress() ?? '';
        $lado = $imovel->lado() ?? '';
        $status = $imovel->statusName() ?? '';

        // formatted queries
        $haystack = strtolower("$address $lado $status");
        $needle = strtolower($searchString ?? '');
        return str_contains($haystack, $needle);
    });
}

new class extends Component {
    /**
     * Summary of imoveis
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\Imovel>
     */
    public $imoveis;
    public $searchString;
    public function mount(ImobiliariaService $imobiliariaService)
    {
        $imobiliaria = $imobiliariaService->getSelectedImobiliaria();
        $this->imoveis = $imobiliaria->imoveis;
    }
}; ?>

<div class="flex flex-col h-full space-y-2">
    <div class="flex gap-2">
        <input type="text" id="searchBar" wire:model.live.debounce='searchString'
            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5"
            placeholder="Pesquisar (Rua, localização ou status)" />
        <x-regular-button label="Cadastrar" />
    </div>
    <div class="h-full bg-white rounded shadow">
        <div class="grid justify-center gap-4 p-4 overflow-scroll h-[40rem]"
            style="grid-template-columns: repeat(auto-fit, minmax(16rem, auto))">
            @foreach (imovelSearch($imoveis, $searchString) as $imovel)
                <article class="relative w-64 p-5 bg-white rounded-lg shadow-md hover:shadow-lg h-min">
                    <img src="{{ $imovel->photo_path }}" class="object-cover w-full h-48 rounded-md">
                    <p class="mt-4 text-xl font-semibold">{{ currencyFormat($imovel->value ?? 0) }}</p>
                    <!-- Sobreposição de Informações -->
                    <a href="#"
                        class="absolute inset-0 flex flex-col justify-between p-4 transition-opacity duration-300 bg-white rounded-lg shadow-lg opacity-0 bg-opacity-90 hover:opacity-100">
                        <div>
                            <h3 class="mb-2 text-lg font-semibold">Detalhes do Imóvel</h3>
                            <ul class="space-y-1 text-base leading-tight line">
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
                        <x-regular-button label="Acessar Imóvel" class="w-full" />
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</div>
