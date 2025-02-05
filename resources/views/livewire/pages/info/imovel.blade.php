<?php

use App\Models\Client;
use App\Models\Imovel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.app')] class extends Component
{
    use WithFileUploads;

    public Imovel $imovel;

    // client attributes
    public int $id;

    public string $address_name;

    public int $address_number;

    public string $bairro;

    public ?string $location_reference;

    public ?string $value;

    public ?string $iptu;

    public string $status;

    public ?string $photo_path;

    public ?int $client_id = null;

    // form attributes
    #[Validate('nullable|image|max:4096')]
    public $uploaded_photo;

    public bool $edit = false;

    public ?string $stored_photo_cache = null;

    public ?string $stored_photo = null;

    public function mount()
    {
        // get currently stored photo (if any)
        $this->stored_photo_cache = $this->imovel->base64Image();

        // bind variables to value from imovel
        $this->rebindValues();
    }

    protected function rules()
    {
        return Imovel::rules();
    }

    public function with()
    {
        return [
            'display_image' => $this->resolveImage(),
            'client' => Client::find($this?->client_id),
        ];
    }

    public function resolveImage()
    {
        if (isset($this->uploaded_photo)) {
            return $this->uploaded_photo->temporaryUrl();
        }

        if (isset($this->stored_photo)) {
            return $this->stored_photo;
        }

        return asset('images/placeholder-image.svg');
    }

    public function rebindValues()
    {
        $this->id = $this->imovel->id;
        $this->address_name = $this->imovel->address_name;
        $this->address_number = $this->imovel->address_number;
        $this->bairro = $this->imovel->bairro;
        $this->location_reference = $this->imovel->location_reference?->value ?? null;
        $this->value = $this->imovel->value;
        $this->iptu = $this->imovel->iptu;
        $this->status = $this->imovel->status->value;
        $this->photo_path = $this->imovel->photo_path;
        $this->client_id = $this->imovel->client?->id ?? null;
        $this->stored_photo = $this->stored_photo_cache;
    }

    public function save()
    {
        // ensure curent user can edit
        $this->authorize('update', $this->imovel);

        // validate form
        $this->validate();

        // store photo and save path
        if ($this->uploaded_photo) {
            $this->photo_path = Storage::disk('local')->put('imobiliaria/images', $this->uploaded_photo);
        }

        // validate once again, accounting for file path
        $validated = $this->validate();

        // convert empty strings to null
        foreach ($validated as $key => $value) {
            $validated[$key] = match ($value) {
                '' => null,
                default => $value,
            };
        }

        // save changes to object
        $this->imovel->fill($validated);
        $this->imovel->save();

        // revalidate cache since image may now be different
        $this->stored_photo_cache = $this->imovel->base64Image();

        // stop the edit after save is finished
        $this->stopEdit();
    }

    // #[On('select-client')]
    public function updateClient(?int $client_id)
    {
        if (! isset($client_id)) {
            return;
        }

        $this->client_id = $client_id;
    }

    public function startEdit()
    {
        $this->edit = true;
    }

    public function stopEdit()
    {
        $this->rebindValues();
        $this->clearValidation();
        $this->edit = false;
    }

    public function clearPhoto()
    {
        $this->uploaded_photo = null;
        $this->stored_photo = null;
        $this->photo_path = null;
    }

    public function clearClient()
    {
        $this->client_id = null;
    }
}; ?>


<div>
    <x-slot name="heading">Gerenciar Imóvel</x-slot>
    @can("view", $imovel)
        <x-errors class="mb-4" />
        <div class="space-y-2">
            <!-- action bar -->
            <div class="flex justify-between">
                <span class="block text-2xl">Dados do Imóvel</span>
                @can("update", $imovel)
                    <div class="flex gap-2 grid-span-3">
                        @if ($edit)
                            <x-primary-button wire:click="save">Salvar</x-primary-button>
                            <x-secondary-button wire:click.prevent="stopEdit">Cancelar</x-secondary-button>
                        @else
                            <x-primary-button label="Editar" wire:click.prevent="startEdit">Editar</x-primary-button>
                        @endif
                    </div>
                @endcan
            </div>
            <!-- grid with images -->
            <div class="grid grid-cols-3 gap-1">
                <x-card class="row-span-4">
                    <div class="grid items-center gap-2">
                        <div class="w-full bg-gray-200 bg-center bg-cover border border-gray-300 aspect-square" style="background-image: url('{{ $display_image }}')"></div>
                        @if ($edit)
                            <div class="grid items-center w-full min-w-0 gap-2">
                                <form class="flex gap-2">
                                    <x-primary-button x-on:click.prevent="$refs.photo.click()">Alterar Foto</x-primary-button>
                                    <x-secondary-button wire:click="clearPhoto">Limpar</x-secondary-button>
                                    <input x-ref="photo" type="file" accept="image/png, image/gif, image/jpeg" wire:model="uploaded_photo" @disabled(! $edit) class="hidden" />
                                </form>
                            </div>
                        @endif
                    </div>
                </x-card>
                <div class="flex col-span-2 gap-1">
                    <x-card class="grid items-center flex-1">
                        <span class="block text-lg font-bold min-w-max">Endereço:</span>
                        <x-input :disabled='!$edit' wire:model="address_name" required autofocus />
                    </x-card>
                    <x-card class="grid items-center">
                        <span class="block text-lg font-bold min-w-max">Número:</span>
                        <x-input :disabled='!$edit' wire:model="address_number" required autofocus />
                    </x-card>
                </div>
                <x-card class="grid items-center col-span-2">
                    <span class="block text-lg font-bold min-w-max">Bairro:</span>
                    <x-input :disabled='!$edit' wire:model="bairro" required autofocus />
                </x-card>
                <x-card class="grid items-center col-span-2">
                    <div class="flex items-center gap-2">
                        <span class="block text-lg font-bold min-w-max">Localização:</span>
                        <x-select :disabled="!$edit" wire:model="location_reference">
                            <x-select.option value="0">Morro</x-select.option>
                            <x-select.option value="1">Praia</x-select.option>
                        </x-select>
                    </div>
                </x-card>
                <x-card class="grid items-center col-span-2">
                    <div class="flex items-center gap-2">
                        <span class="block text-lg font-bold min-w-max">IPTU:</span>
                        <x-input prefix="R$" :disabled='!$edit' wire:model="iptu" autofocus />
                    </div>
                </x-card>
            </div>
            <!-- info about allocation -->
            <span class="block text-2xl">Informações de Locação</span>
            <div class="grid grid-cols-2 gap-1">
                <x-card class="grid items-center col-span-2">
                    <div class="flex items-center gap-2">
                        <span class="block text-lg font-bold min-w-max">Cliente:</span>
                        <span class="flex-1">{{ $client?->name ?? "Nenhum cliente atribuido" }}</span>
                        <x-primary-button :disabled="!$edit" x-on:click.prevent="$dispatch('open-modal', 'select-client')">Alterar</x-primary-button>
                        <x-secondary-button :disabled="!$edit" wire:click="clearClient">Limpar</x-secondary-button>
                    </div>
                </x-card>
                <x-card class="grid items-center">
                    <div class="flex items-center gap-2">
                        <span class="block text-lg font-bold min-w-max">Status:</span>
                        <x-select :disabled="!$edit" wire:model="status">
                            <x-select.option value="0">Livre</x-select.option>
                            <x-select.option value="1">Alugado</x-select.option>
                            <x-select.option value="2">Vendido</x-select.option>
                        </x-select>
                    </div>
                </x-card>
                <x-card class="grid items-center">
                    <div class="flex items-center gap-2">
                        <span class="block text-lg font-bold min-w-max">Valor:</span>
                        <x-input prefix="R$" :disabled='!$edit' wire:model="value" autofocus />
                    </div>
                </x-card>
            </div>
        </div>
        <x-secondary-button class="mt-2">Ver documentos</x-secondary-button>
        <x-modal name="select-client" focusable>
            <div class="p-6">
                <h1 class="mb-2 text-2xl">Selecionar Cliente</h1>
                <livewire:modals.select-imovel-client />
            </div>
        </x-modal>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
