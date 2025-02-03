<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Imovel;

new #[Layout('layouts.app')] class extends Component {
    //
}; ?>

<div class="space-y-3.5">
    <x-slot name="heading">
        Upload de Planilha Excel
    </x-slot>
    @can('create', Imovel::class)
        <div class="space-y-1">
            <x-alert secondary title="Cadastrando Imóveis">
                <x-slot name="slot">
                    <ol class="list-decimal">
                        <li>
                            A planilha deve incluir um <b>cabeçalho</b> em sua primeira linha
                        </li>
                        <li>
                            Certifique-se que a planilha possui colunas na <b>mesma ordem</b> que a pré-visualização abaixo
                        </li>
                        <li>
                            Verifique as informações através da pré-visualização antes de confirmar a importação
                        </li>
                    </ol>
                </x-slot>
            </x-alert>
            <input type="file" id="fileInput" name="file" accept=".xlsx"
                class="w-full p-3 border border-gray-300 rounded-lg file:mr-2.5 file:cursor-pointer file:bg-black file:text-white file:py-2 file:px-4 file:border-0 file:rounded-md">
            <x-alert negative outline title="Validation Error Sample" />
        </div>
        <div class="space-y-1">
            <span class="text-2xl font-medium">Preview</span>
            <div class="overflow-scroll bg-white rounded h-96">
                <table class="min-w-full table-auto w-max">
                    <thead class="sticky top-0 bg-white">
                        <tr class="text-left bg-emerald-800 text-gray-50 *:px-3 *:py-4">
                            <th scope="col">
                                <span>Endereço</span>
                            </th>
                            <th scope="col">
                                <span>Número</span>
                            </th>
                            <th scope="col">
                                <span>Bairro</span>
                            </th>
                            <th scope="col">
                                <abbr title="'Morro' ou 'Praia'">Localização</abbr>
                            </th>
                            <th scope="col">
                                <span>Valor (R$)</span>
                            </th>
                            <th scope="col">
                                <span>IPTU (R$)</span>
                            </th>
                            <th scope="col">
                                <abbr title="'Livre', 'Alugado' ou 'Vendido'">Status</abbr>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 25; $i++)
                            <tr class="*:px-3 *:py-4 border-b-2 border-gray-100">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
        <x-primary-button disabled class="disabled:opacity-75">Cadastrar</x-primary-button>
    @endcan
</div>


{{-- Must be live component because of error validation (no refreshing) --}}
{{-- CSV Upload --}}
