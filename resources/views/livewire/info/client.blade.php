<?php

use Livewire\Volt\Component;
use App\Models\Client;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public Client $client;

    // client attributes
    public string $name;
    public string $cpf;
    public string $email;
    public string $address;
    public string $type;
    public int $imobiliaria_id;

    // component state
    public bool $edit = false;

    public function mount()
    {
        $this->rebindValues();
    }

    public function rules()
    {
        return Client::rules();
    }

    public function rebindValues()
    {
        $this->name = $this->client->name;
        $this->cpf = $this->client->cpf;
        $this->email = $this->client->email ?? '';
        $this->address = $this->client->address ?? '';
        $this->type = $this->client->type;
        $this->imobiliaria_id = $this->client->imobiliaria->id;
    }

    public function save()
    {
        // ensure curent user can edit
        $this->authorize('update', $this->client);

        // validate the form values
        $validated = $this->validate();

        // save the validated values into the new client
        $this->client->fill($validated);
        $this->client->save();

        // stop the edit after finishing save
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
        Gerenciar Cliente
    </x-slot>
    @can('view', $client)
        <x-errors class='mb-4' />
        <div class="flex flex-col h-full gap-1">
            <div class="flex justify-between">
                <span class="block text-2xl">Dados do Imóvel</span>
                @can('update', $client)
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
            <x-card>
                <span class="block text-lg font-bold min-w-max">Nome:</span>
                <x-input :disabled='!$edit' wire:model='name' required autofocus />
            </x-card>
            <x-card>
                <span class="block text-lg font-bold min-w-max">CPF:</span>
                <x-maskable mask="###.###.###-##" :disabled='!$edit' wire:model='cpf' required autofocus />
            </x-card>
            <x-card>
                <span class="block text-lg font-bold min-w-max">E-mail:</span>
                <x-input :disabled='!$edit' wire:model='email' autofocus />
            </x-card>
            <x-card>
                <span class="block text-lg font-bold min-w-max">Endereço:</span>
                <x-input :disabled='!$edit' wire:model='address' autofocus />
            </x-card>
            <x-card>
                <span class="block text-lg font-bold min-w-max">Tipo</span>
                <x-select :disabled="!$edit" wire:model='type'>
                    <x-select.option value="0">Locador</x-select.option>
                    <x-select.option value="1">Vendedor</x-select.option>
                </x-select>
            </x-card>
        </div>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
