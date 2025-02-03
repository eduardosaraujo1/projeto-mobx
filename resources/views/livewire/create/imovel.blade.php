<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Models\Imovel;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Http\UploadedFile;
use App\Services\ImovelExcelService;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    #[Validate('file|max:32768')]
    public $file;

    public array $parsedTable = [];

    // dependency injection
    private ImovelExcelService $excelService;

    public function boot(ImovelExcelService $excelService)
    {
        $this->excelService = $excelService;
    }

    // this lifecycle hook will run as soon as a form field is updated, and before rendering
    public function updating(string $name, ?UploadedFile $value)
    {
        if ($name === 'file' && !empty($value->path())) {
            $this->parsedTable = $this->parseFile($value->path());
        }
    }

    public function with()
    {
        return [
            'table' => $this->getFormattedTable(),
        ];
    }

    public function getFormattedTable()
    {
        $table = $this->parsedTable;
        $formatted = [];

        foreach ($table as $row) {
            $row['is_lado_praia'] = $row['is_lado_praia'] ? 'Praia' : 'Morro';

            $row['value'] = $row['value'] ?? 0;
            $row['value'] = $row['value'] < 0 ? '' : number_format(num: $row['value'], decimals: 2);

            $row['iptu'] = $row['iptu'] ?? 0;
            $row['iptu'] = $row['iptu'] < 0 ? '' : number_format(num: $row['iptu'], decimals: 2);

            $row['status'] = match ($row['status']) {
                0 => 'Livre',
                1 => 'Alugado',
                2 => 'Vendido',
                default => '',
            };

            $formatted[] = $row;
        }

        return $formatted;
    }

    public function parseFile($file_path)
    {
        $rows = SimpleExcelReader::create($file_path)->getRows();
        $processed = [];

        foreach ($rows as $rowIndex => $row) {
            // parse the current row
            $parsed = $this->excelService->parseRow($row);

            // validate the normalized row
            $errors = $this->excelService->getRowErrors($parsed);

            if ($errors) {
                // loop over errors and add them with the row information
                foreach ($errors as $field => $messages) {
                    foreach ($messages as $message) {
                        $this->addError($field, 'Row ' . ($rowIndex + 1) . ': ' . $message);
                    }
                }

                break;
            }

            $processed[] = $parsed; // alternative to array_push
        }

        return $processed;
    }
}; ?>

<div class="flex flex-col h-full gap-4">
    <x-slot name="heading">
        Upload de Planilha Excel
    </x-slot>
    @can('create', Imovel::class)
        <div>
            <div x-data="{ expanded: true }">
                <x-alert info class="relative cursor-pointer *:p-0" secondary x-on:click="expanded = ! expanded">
                    <x-slot name="title">
                        Instruções
                        <x-icon name="chevron-down" class="absolute transition top-4 right-4 size-5 shrink-0"
                            ::class="expanded ? 'rotate-180' : ''" x-bind:class="isExpanded ? 'rotate-180' : ''" />
                    </x-slot>
                    <x-slot name="slot" class="mt-4" x-cloak x-show="expanded" x-collapse>
                        <ol class="space-y-2 list-decimal">
                            <li>
                                <strong>Cabeçalho Obrigatório:</strong> A primeira linha da planilha deve conter um
                                cabeçalho,
                                pois será ignorada na importação.
                            </li>
                            <li>
                                <strong>Ordem das Colunas:</strong> Certifique-se de que as colunas seguem a mesma ordem
                                da
                                pré-visualização abaixo.
                            </li>
                            <li>
                                <strong>Regras de Formatação (valores inválidos serão rejeitados):</strong>
                                <ul class="ml-6 space-y-1 list-disc">
                                    <li>
                                        <strong>Localização:</strong> Deve ser
                                        <pre class="inline px-1 bg-gray-100 rounded">Morro</pre> ou
                                        <pre class="inline px-1 bg-gray-100 rounded">Praia</pre>.
                                    </li>
                                    <li>
                                        <strong>Status:</strong> Deve ser
                                        <pre class="inline px-1 bg-gray-100 rounded">Livre</pre>,
                                        <pre class="inline px-1 bg-gray-100 rounded">Alugado</pre> ou
                                        <pre class="inline px-1 bg-gray-100 rounded">Vendido</pre>.
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <strong>Confirmação:</strong> Revise os dados na pré-visualização antes de finalizar a
                                importação.
                            </li>
                        </ol>
                    </x-slot>
                </x-alert>
            </div>
        </div>
        <x-errors outline />
        <div class="space-y-1">
            <span class="text-2xl font-medium">Pré-visualização</span>
            <div class="overflow-auto bg-white rounded h-96">
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
                        @forelse ($table as $row)
                            <tr class="*:px-3 *:py-4 border-b-2 border-gray-100">
                                <td>{{ $row['address_name'] ?? 'ERROR' }}</td>
                                <td>{{ $row['address_number'] ?? 'ERROR' }}</td>
                                <td>{{ $row['bairro'] ?? 'ERROR' }}</td>
                                <td>{{ $row['is_lado_praia'] ? 'Praia' : 'Morro' }}</td>
                                <td>{{ $row['value'] ?? 'ERROR' }}</td>
                                <td>{{ $row['iptu'] ?? 'ERROR' }}</td>
                                <td>{{ $row['status'] ?? 'ERROR' }}</td>
                            </tr>
                        @empty
                            {{-- Blank table rows --}}
                            @for ($i = 0; $i < 10; $i++)
                                <tr class="*:px-3 *:py-4 border-b-2 border-gray-100">
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endfor
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <input type="file" id="fileInput" name="file" accept=".xlsx" wire:model='file'
            class="w-full p-3 border border-gray-300 rounded-lg file:mr-2.5 file:cursor-pointer file:bg-black file:text-white file:py-2 file:px-4 file:border-0 file:rounded-md">
        <x-primary-button disabled class="mt-auto disabled:opacity-75 w-min">Cadastrar</x-primary-button>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
