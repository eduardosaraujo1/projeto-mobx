<?php

use App\Models\Imovel;
use App\Models\ImovelDocument;
use App\Services\ImovelLogService;
use App\Utils\StringUtils;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public Imovel $imovel;

    // document upload
    #[Validate('file|max:2048000')] // 200 MB
    public $file;

    // track clicked document from deletion
    public ?ImovelDocument $documentToDelete = null;

    public function with()
    {
        return [
            'documents' => $this->getDocuments(),
        ];
    }

    public function updating($name, $value)
    {
        if ($name === 'file') {
            $this->addDocument($value);
        }
    }

    public function getDocuments()
    {
        /**
         * @var \Illuminate\Database\Eloquent\Collection<ImovelDocument>
         */
        $documents = $this->imovel?->documents;

        // get empty array if documents were not found
        if (! $documents) {
            return [];
        }

        // filter for documents that are in the system to be downloaded
        $validDocs = $documents->filter(function (ImovelDocument $document) {
            return Storage::disk('local')->exists($document?->filepath ?? '')
                && Gate::allows('view', $document);
        });

        // parse documents for easier front end reading
        $result = $validDocs->map(function (ImovelDocument $doc) {
            return [
                'id' => $doc?->id,
                'filepath' => $doc->filepath,
                'filename' => File::name($doc->filename),
                'filesize' => $doc->filesize,
                'extension' => strtoupper(File::extension($doc->filepath)),
                'document' => $doc,
            ];
        })->toArray();

        return $result;
    }

    public function addDocument(?UploadedFile $file)
    {
        // validate if the user has access to this function
        $this->authorize('update', $this->imovel);

        // if file is null, fail
        if (! isset($file)) {
            return;
        }

        // if for any reason the imovel or the imovel ID is null, stop with error message
        if (! isset($this->imovel?->id)) {
            session()->flash('documentError');

            return;
        }

        // save file to disk
        $filename = $file->getClientOriginalName();
        $filesize = StringUtils::humanFileSize($file->getSize());
        $path = Storage::disk('local')->putFile('imovel_documents', $file);

        // if storing the file failed, send error message and stop propagation
        if (! $path) {
            session()->flash('documentError');

            return;
        }

        // validate the inputs through the standard document validator
        $validator = ImovelDocument::validator([
            'filename' => $filename,
            'filepath' => $path,
            'filesize' => $filesize,
            'imovel_id' => $this->imovel->id,
        ]);

        if ($validator->fails()) {
            session()->flash('documentError');

            return;
        }

        // get the validated output
        $validated = $validator->valid();

        // add path do the database
        ImovelDocument::create($validated);

        // log creation of the document with file size
        $logService = new ImovelLogService($this->imovel, auth()->user());
        $logService->logDocumentUpload($filename, $filesize);
    }

    public function download(ImovelDocument $document)
    {
        $this->authorize('download', $document);

        // get file path to download and file name
        $filePath = $document->filepath;
        $fileName = $document->filename;

        return Storage::disk('local')->download($filePath, $fileName);
    }

    public function deleteStagedDocument()
    {
        // reference staged document locally
        $document = $this->documentToDelete;

        // if it is null, abort operation
        if (! isset($document)) {
            return;
        }

        // follow deletion protocol as usual:
        $this->authorize('delete', $document);

        // get file path and file name
        $filename = $document->filename;
        $filepath = $document->filepath;
        $filesize = $document->filesize;

        // delete file
        Storage::disk('local')->delete($filepath);

        // remove entry from database
        $document->delete();

        // log deletion of the document
        $logService = new ImovelLogService($this->imovel, auth()->user());
        $logService->logDocumentDelete($filename, $filesize);
    }

    public function stageDocumentForDeletion(?int $id)
    {
        if (! isset($id)) {
            return;
        }

        $this->documentToDelete = ImovelDocument::find($id);

        // open modal
        $this->dispatch('open-modal', 'deleteDocument');
    }

    public function clearDocumentStage()
    {
        $this->documentToDelete = null;
    }
}; ?>


<div>
    <x-modal name="view-documents" focusable>
        <div class="p-6">
            <h1 class="mb-2 text-2xl font-medium">Documentos do imóvel</h1>
            @if (session("documentError"))
                <x-alert negative title="Não foi possivel fazer o upload do arquivo. Tente novamente mais tarde."></x-alert>
            @endif

            <input class="hidden" type="file" name="uploadedDocument" x-ref="uploadDoc" wire:model.change="file" />
            <div class="bg-white border border-gray-200 max-h-[40rem] space-y-2 overflow-y-auto p-2">
                @forelse ($documents as $document)
                    <x-card wire:key="{{$document['id'] ?? uuid_create()}}">
                        <div class="flex items-center gap-4">
                            <x-document-icon name="document" class="w-12 h-12" :extension="$document['extension'] ?? ''" />
                            <div class="flex flex-col self-stretch justify-between flex-1">
                                <span class="text-lg font-medium">{{ $document["filename"] ?? "" }}</span>
                                <span>{{ $document["extension"] ?? "" }} ({{ $document["filesize"] }})</span>
                            </div>
                            <div class="flex gap-2">
                                @can("delete", $document["document"])
                                    <x-mini-button icon="trash" lg rounded red outline interaction:solid wire:click="stageDocumentForDeletion({{ $document['id'] }})" />
                                @endcan

                                @can("download", $document["document"])
                                    <x-mini-button icon="arrow-down-tray" lg rounded black wire:click="download('{{ $document['id'] }}')" />
                                @endcan
                            </div>
                        </div>
                    </x-card>
                @empty
                    @can("update", $imovel)
                    @else
                        <x-alert title="Nenhum imóvel encontrado" />
                    @endcan
                @endforelse
                @can("update", $imovel)
                    <div class="block w-full my-2 text-center">
                        <x-secondary-button class="flex gap-1 h-min" x-on:click="$refs.uploadDoc.click()">
                            <x-icon name="plus" class="w-5 h-5" />
                            Adicionar
                        </x-secondary-button>
                    </div>
                @endcan
            </div>
            <div class="flex justify-end w-full mt-2">
                <x-primary-button x-on:click="$dispatch('close')">Fechar</x-primary-button>
            </div>
            <x-modal name="deleteDocument">
                <div class="flex flex-col p-6">
                    <h2 class="text-2xl font-medium">Deletar documento</h2>
                    <div class="text-lg">
                        <div>
                            Tem certeza que deseja deletar
                            <span class="font-bold">{{ $documentToDelete?->filename }}</span>
                            ?
                        </div>
                        <span class="text-red-700 underline">Essa ação é irreversivel</span>
                    </div>
                    <div class="flex justify-end w-full gap-2 mt-5">
                        <x-danger-button x-on:click="$dispatch('close')" wire:click="deleteStagedDocument">DELETAR</x-danger-button>
                        <x-secondary-button x-on:click="$dispatch('close')" wire:click="clearDocumentStage">Cancelar</x-secondary-button>
                    </div>
                </div>
            </x-modal>
        </div>
    </x-modal>
</div>
