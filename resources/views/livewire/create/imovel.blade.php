<?php

use App\Facades\SelectedImobiliaria;
use App\Models\Imovel;
use App\Services\ImovelExcelParserService;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Spatie\SimpleExcel\SimpleExcelReader;

new #[Layout('layouts.app')] class extends Component
{
    use WithFileUploads;

    // file upload
    #[Validate('file|max:32768')]
    public $file;

    // component state
    public bool $hasErrors = false;

    public array $parsedTable = [];

    public function with()
    {
        return [
            'table' => $this->getFormattedTable(),
        ];
    }

    // this lifecycle hook will run as soon as a form field is updated, and before rendering
    public function updating(string $name, ?UploadedFile $value)
    {
        if ($name === 'file') {
            $this->fileUploaded($value);
        }
    }

    public function save()
    {
        // check if user has permission to create new imoveis
        $this->authorize('create', Imovel::class);

        // If currently has errors, do not proceed
        if ($this->hasErrors) {
            session()->flash('error', 'upload error');

            return;
        }

        foreach ($this->parsedTable as $row) {
            // validate row one last time
            $errors = $this->getRowErrors($row);

            if ($errors) {
                session()->flash('error', 'upload error');

                return;
            } else {
                Imovel::create([...$row, 'imobiliaria_id' => SelectedImobiliaria::get(auth()->user())->id]);
            }

            // flash success message
            session()->flash('message', 'Importação concluida com sucesso');
            $this->redirect(route('imovel.index'));
        }
    }

    public function fileUploaded(?UploadedFile $file)
    {
        // stop invalid paths
        if (! isset($file) || empty($file->path())) {
            return;
        }

        if (! in_array($file->extension(), ['xlsx', 'csv'])) {
            return;
        }

        // init parser service
        $parser = new ImovelExcelParserService;

        // get excel row
        $rows = SimpleExcelReader::create($file->path())->getRows();

        // parse table
        $table = $parser->parse($rows);
        $processed = [];

        foreach ($table as $rowIndex => $row) {
            // validate the normalized row
            $errors = $this->getRowErrors($row);

            if ($errors) {
                $this->hasErrors = true;
                $this->addRowErrors($errors, $rowIndex + 1);
                break; // stop adding new rows after declaring the errors
            }

            // track the error state (if this code runs there were no errors on the column)
            $this->hasErrors = false;

            $processed[] = $row;
        }

        $this->parsedTable = $processed;
    }

    public function getRowErrors($row)
    {
        $validator = Validator::make($row, Imovel::rules());

        if ($validator->fails()) {
            return $validator->errors()->messages();
        }

        return null;
    }

    public function addRowErrors(array $errors, int $rowNumber = 0)
    {
        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $this->addError($field, "Linha $rowNumber:  $message");
            }
        }
    }

    public function getFormattedTable()
    {
        function formatCurrencyField($value): string
        {
            if (! isset($value) || (float) $value < 0) {
                return '';
            }

            return number_format((float) $value, 2);
        }

        $table = $this->parsedTable;
        $formatted = [];

        foreach ($table as $row) {
            // parse location name (unset friendly)
            if (isset($row['location_reference'])) {
                $row['location_reference'] = $row['location_reference']?->getName();
            }

            // parse currency values (unset friendly)
            if (isset($row['value'])) {
                $row['value'] = formatCurrencyField($row['value']);
            }

            if (isset($row['iptu'])) {
                $row['iptu'] = formatCurrencyField($row['iptu']);
            }

            // parse status name (unset friendly)
            if (isset($row['status'])) {
                $row['status'] = $row['status']?->getName();
            }

            // push changes to formatted table array
            $formatted[] = $row;
        }

        return $formatted;
    }
}; ?>


<div class="flex flex-col h-full gap-4">
    <x-slot name="heading">Upload de Planilha Excel</x-slot>
    @can("create", Imovel::class)
        <x-errors title="O sistema não conseguiu ler sua planilha" outline />
        <div>
            <div x-data="{ expanded: false }">
                <x-alert info class="relative cursor-pointer *:p-0" secondary x-on:click="expanded = ! expanded">
                    <x-slot name="title">
                        Instruções
                        <x-icon
                            name="chevron-down"
                            class="absolute transition top-4 right-4 size-5 shrink-0"
                            ::class="expanded ? 'rotate-180' : ''"
                            x-bind:class="isExpanded ? 'rotate-180' : ''"
                        />
                    </x-slot>
                    <x-slot name="slot" class="mt-4" x-cloak x-show="expanded" x-collapse>
                        <ol class="space-y-2 list-decimal">
                            <li>
                                <strong>Cabeçalho Obrigatório:</strong>
                                A primeira linha da planilha deve conter um cabeçalho, pois será ignorada na importação.
                            </li>
                            <li>
                                <strong>Ordem das Colunas:</strong>
                                Certifique-se de que as colunas seguem a mesma ordem da pré-visualização abaixo.
                            </li>
                            <li>
                                <strong>Regras de Formatação (valores inválidos serão rejeitados):</strong>
                                <ul class="ml-6 space-y-1 list-disc">
                                    <li>
                                        <strong>Localização:</strong>
                                        Deve ser
                                        <pre class="inline px-1 bg-gray-100 rounded">Morro</pre>
                                        ou
                                        <pre class="inline px-1 bg-gray-100 rounded">Praia</pre>
                                        .
                                    </li>
                                    <li>
                                        <strong>Status:</strong>
                                        Deve ser
                                        <pre class="inline px-1 bg-gray-100 rounded">Livre</pre>
                                        ,
                                        <pre class="inline px-1 bg-gray-100 rounded">Alugado</pre>
                                        ou
                                        <pre class="inline px-1 bg-gray-100 rounded">Vendido</pre>
                                        .
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <strong>Confirmação:</strong>
                                Revise os dados na pré-visualização antes de finalizar a importação.
                            </li>
                        </ol>
                    </x-slot>
                </x-alert>
            </div>
        </div>
        <div class="flex justify-between">
            <input
                type="file"
                id="fileInput"
                name="file"
                accept=".xlsx"
                wire:model="file"
                class="w-fit p-3 border border-gray-300 rounded-lg file:mr-2.5 file:cursor-pointer file:bg-black file:text-white file:py-2 file:px-4 file:border-0 file:rounded-md"
            />
            <div class="flex flex-row-reverse items-center gap-4">
                <x-primary-button :disabled="empty($parsedTable) || $hasErrors" class="mt-auto disabled:opacity-75 w-min" wire:click="save">Cadastrar</x-primary-button>
                @if (session()->exists("error"))
                    <div class="flex items-center gap-1 text-sm text-negative-700">
                        <x-icon name="exclamation-circle" class="inline w-4 h-4" />
                        <span>Não é possível cadastrar essa planilha. Tente novamente mais tarde.</span>
                    </div>
                @endif
            </div>
        </div>
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
                                <td>{{ $row["address_name"] ?? "" }}</td>
                                <td>{{ $row["address_number"] ?? "" }}</td>
                                <td>{{ $row["bairro"] ?? "" }}</td>
                                <td>{{ $row["location_reference"] ?? "" }}</td>
                                <td>{{ $row["value"] ?? "" }}</td>
                                <td>{{ $row["iptu"] ?? "" }}</td>
                                <td>{{ $row["status"] ?? "" }}</td>
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
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
