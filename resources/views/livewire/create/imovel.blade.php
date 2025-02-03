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

    public function updating(string $name, UploadedFile $value)
    {
        if ($name === 'file') {
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
            // Normalize the current row
            $normalized = $this->excelService->parseRow($row);

            // Validate the normalized row
            $errors = $this->excelService->getRowErrors($normalized);

            if ($errors) {
                // loop over errors and add them with the row information
                foreach ($errors as $field => $messages) {
                    foreach ($messages as $message) {
                        $this->addError($field, 'Row ' . ($rowIndex + 1) . ': ' . $message);
                    }
                }

                break;
            }

            $processed[] = $normalized; // alternative to array_push
        }

        // Optionally, convert the array to a collection if needed:
        return $processed;
    }
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
                            A planilha deve incluir um <strong>cabeçalho</strong> em sua primeira linha, pois o programa irá
                            remove-la
                        </li>
                        <li>
                            Certifique-se que a planilha possui colunas na <strong>mesma ordem</strong> que a
                            pré-visualização abaixo
                        </li>
                        <li class="">
                            <strong>IMPORTANTE:</strong> as seguintes não seguirem essas normas
                            <ul>
                                <li>
                                    <strong>Localização:</strong> deve ser
                                    <pre class="inline">Morro</pre> ou
                                    <pre class="inline">Praia</pre>
                                </li>
                                <li>
                                    <strong>Status:</strong> deve ser
                                    <pre class="inline">Livre</pre> ou
                                    <pre class="inline">Alugado</pre> ou
                                    <pre class="inline">Vendido</pre>
                                </li>
                            </ul>
                        </li>
                        <li>
                            Verifique as informações através da pré-visualização antes de confirmar a importação
                        </li>
                    </ol>
                </x-slot>
            </x-alert>
            <input type="file" id="fileInput" name="file" accept=".xlsx" wire:model='file'
                class="w-full p-3 border border-gray-300 rounded-lg file:mr-2.5 file:cursor-pointer file:bg-black file:text-white file:py-2 file:px-4 file:border-0 file:rounded-md">
            <x-errors outline />
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
        <x-primary-button disabled class="disabled:opacity-75">Cadastrar</x-primary-button>
    @endcan
</div>


{{-- Must be live component because of error validation (no refreshing) --}}
{{-- CSV Upload --}}
