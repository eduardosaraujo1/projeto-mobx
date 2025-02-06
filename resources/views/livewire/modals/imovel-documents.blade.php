<?php

use App\Models\Imovel;
use App\Models\ImovelDocument;
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
            return Storage::disk('local')->exists($document?->filepath ?? '');
        });

        // parse documents for easier front end reading
        $result = $validDocs->map(function (ImovelDocument $doc) {
            return [
                'id' => $doc?->id,
                'filepath' => $doc->filepath,
                'filename' => File::name($doc->filename),
                'extension' => strtoupper(File::extension($doc->filepath)),
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
    }

    public function download(ImovelDocument $document)
    {
        $this->authorize('view', $this->imovel);

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

        // get file path
        $filePath = $document->filepath;

        // delete file
        Storage::disk('local')->delete($filePath);

        // remove entry from database
        $document->delete();
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
                        <span id="filename" class="text-lg font-medium">{{ $document["filename"] ?? "" }}</span>
                        <span id="fileformat">{{ $document["extension"] ?? "" }}</span>
                    </div>
                    <div class="flex gap-2">
                        <x-mini-button icon="trash" lg rounded red outline interaction:solid wire:click="stageDocumentForDeletion({{ $document['id'] }})" />
                        <x-mini-button icon="arrow-down-tray" lg rounded black wire:click="download('{{ $document['id'] }}')" />
                    </div>
                </div>
            </x-card>
        @empty
            <x-alert title="Nenhum documento encontrado" />
        @endforelse
        <div class="block w-full mt-2 text-center">
            <x-secondary-button class="flex gap-1 h-min" x-on:click="$refs.uploadDoc.click()">
                <x-icon name="plus" class="w-5 h-5" />
                Adicionar
            </x-secondary-button>
        </div>
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
