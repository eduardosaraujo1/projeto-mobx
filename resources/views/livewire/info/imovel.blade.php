<?php

use Livewire\Volt\Component;
use App\Models\Imovel;
use App\Enums\ImovelStatus;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

new #[Layout('layouts.app')] class extends Component {
    public Imovel $imovel;
    public bool $edit = false;

    // client attributes
    public string $address_name;
    public int $address_number;
    public string $bairro;
    public string $is_lado_praia;
    public ?string $value;
    public ?string $iptu;
    public string $status;
    public string $photo_path;
    public int $client_id;

    public function mount()
    {
        $this->rebindValues();
    }

    protected function rules()
    {
        return Imovel::rules();
    }

    protected function messages()
    {
        return [
            'is_lado_praia.boolean' => "Field localização must be 'Morro' or 'Praia' \"",
        ];
    }

    protected function rebindValues()
    {
        $this->address_name = $this->imovel->address_name;
        $this->address_number = $this->imovel->address_number;
        $this->bairro = $this->imovel->bairro;
        $this->is_lado_praia = $this->imovel->is_lado_praia ? '1' : '0';
        $this->value = $this->imovel->value;
        $this->iptu = $this->imovel->iptu;
        $this->status = $this->imovel->status;
        $this->photo_path = $this->imovel->photo_path;
        $this->client_id = $this->imovel->client->id;
    }

    public function save()
    {
        // ensure curent user can edit
        $this->authorize('update', $this->imovel);

        // validate form
        $validated = $this->validate();

        // Convert empty strings to null
        foreach ($validated as $key => $value) {
            $validated[$key] = match ($value) {
                '' => null,
                default => $value,
            };
        }

        if (($validated['iptu'] ?? null) === '') {
            $validated['iptu'] = null;
        }

        if (($validated['value'] ?? null) === '') {
            $validated['value'] = null;
        }

        // save changes to object
        $this->imovel->fill($validated);
        $this->imovel->save();

        // stop the edit after save is finished
        $this->stopEdit();
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
}; ?>

<div>
    <x-slot name="heading">
        Gerenciar Imóvel
    </x-slot>
    @can('view', $imovel)
        <x-errors class='mb-4' />
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="block text-2xl">Dados do Imóvel</span>
                @can('update', $imovel)
                    <div class="flex gap-2 grid-span-3">
                        @if ($edit)
                            <x-primary-button wire:click='save'>Salvar</x-primary-button>
                            <x-secondary-button wire:click.prevent='stopEdit'>Cancelar</x-secondary-button>
                        @else
                            <x-primary-button label="Editar" wire:click.prevent='startEdit'>Editar</x-primary-button>
                        @endif
                    </div>
                @endcan
            </div>
            <div class="grid grid-cols-3 gap-1">
                <x-card class='row-span-4'>
                    <div class="grid items-center gap-2">
                        <img src="{{ $photo_path }}" alt="" class="w-full bg-center bg-cover aspect-square"
                            style="background-image:url('{{ asset('images/placeholder-image.svg') }}')">
                        <div class="grid items-center w-full gap-2">
                            <span class="block text-lg font-bold min-w-max">Caminho Foto:</span>
                            <x-input :disabled="!$edit" wire:model='photo_path' />
                        </div>
                    </div>
                </x-card>
                <div class="flex col-span-2 gap-1">
                    <x-card class="grid items-center flex-1">
                        <span class="block text-lg font-bold min-w-max">Endereço:</span>
                        <x-input :disabled='!$edit' wire:model='address_name' required autofocus />
                    </x-card>
                    <x-card class="grid items-center">
                        <span class="block text-lg font-bold min-w-max">Número:</span>
                        <x-input :disabled='!$edit' wire:model='address_number' required autofocus />
                    </x-card>
                </div>
                <x-card class="grid items-center col-span-2">
                    <span class="block text-lg font-bold min-w-max">Bairro:</span>
                    <x-input :disabled='!$edit' wire:model='bairro' required autofocus />
                </x-card>
                <x-card class="grid items-center col-span-2">
                    <div class="flex items-center gap-2">
                        <span class="block text-lg font-bold min-w-max">Localização:</span>
                        <x-select :disabled="!$edit" wire:model='is_lado_praia'>
                            <x-select.option value="0">Morro</x-select.option>
                            <x-select.option value="1">Praia</x-select.option>
                        </x-select>
                    </div>
                </x-card>
                <x-card class="grid items-center col-span-2">
                    <div class="flex items-center gap-2">
                        <span class="block text-lg font-bold min-w-max">IPTU: </span>
                        <x-input prefix="R$" :disabled='!$edit' wire:model='iptu' autofocus />
                    </div>
                </x-card>
            </div>
            <span class="block text-2xl">Informações de Locação</span>
            <div class="grid grid-cols-2 gap-1">
                <x-card class="grid items-center">
                    <div class="flex items-center gap-2">
                        <span class="block text-lg font-bold min-w-max">Status: </span>
                        <x-select :disabled="!$edit" wire:model='status'>
                            <x-select.option value="0">Livre</x-select.option>
                            <x-select.option value="1">Alugado</x-select.option>
                            <x-select.option value="2">Vendido</x-select.option>
                        </x-select>
                    </div>
                </x-card>
                <x-card class="grid items-center">
                    <div class="flex items-center gap-2">
                        <span class="block text-lg font-bold min-w-max">Valor:</span>
                        <x-input prefix="R$" :disabled='!$edit' wire:model='value' autofocus />
                    </div>
                </x-card>
                <x-card class="grid items-center col-span-2">
                    <div class="flex items-center gap-2">
                        <span class="block text-lg font-bold min-w-max">Cliente: </span>
                        <x-select placeholder="Cliente" option-label="name" option-value="id" :disabled="!$edit" />
                    </div>
                </x-card>
            </div>
            <x-secondary-button>Ver documentos</x-secondary-button>
        </div>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
