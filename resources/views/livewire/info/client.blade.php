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

    // component state
    public bool $edit = false;

    public function mount()
    {
        $this->rebindValues();
    }

    protected function rules()
    {
        return Client::rules();
    }

    protected function rebindValues()
    {
        $this->name = $this->client->name;
        $this->cpf = $this->client->cpf;
        $this->email = $this->client->email ?? '';
        $this->address = $this->client->address ?? '';
        $this->type = $this->client->type;
    }

    public function save()
    {
        $this->authorize('update', $this->client);
        $validated = $this->validate();
        $this->client->fill($validated);
        $this->client->save();

        $this->edit = false;
    }

    public function startEdit()
    {
        $this->edit = true;
    }

    public function cancelEdit()
    {
        $this->rebindValues();
        $this->clearValidation();

        $this->edit = false;
    }
}; ?>

<div>
    <x-slot name="heading">
        Dados do Cliente
    </x-slot>
    @can('view', $client)
        <form class="flex flex-col h-full gap-1" wire:submit='save'>
            <x-errors class='mb-4' />
            <x-card>
                <div class="flex items-center space-x-2">
                    <span class="block text-lg font-bold min-w-max">Nome:</span>
                    <x-input :disabled='!$edit' wire:model='name' required autofocus />
                </div>
            </x-card>
            <x-card>
                <div class="flex items-center space-x-2">
                    <span class="block text-lg font-bold min-w-max">CPF:</span>
                    <x-input :disabled='!$edit' wire:model='cpf' required autofocus />
                </div>
            </x-card>
            <x-card>
                <div class="flex items-center space-x-2">
                    <span class="block text-lg font-bold min-w-max">E-mail:</span>
                    <x-input :disabled='!$edit' wire:model='email' autofocus />
                </div>
            </x-card>
            <x-card>
                <div class="flex items-center space-x-2">
                    <span class="block text-lg font-bold min-w-max">Endereço:</span>
                    <x-input :disabled='!$edit' wire:model='address' autofocus />
                </div>
            </x-card>
            <x-card>
                <div class="flex items-center space-x-2">
                    <span class="block text-lg font-bold min-w-max">Tipo</span>
                    <x-select :disabled="!$edit" wire:model='type'>
                        <x-select.option value="0">Locador</x-select.option>
                        <x-select.option value="1">Vendedor</x-select.option>
                    </x-select>
                </div>
            </x-card>
            @can('update', $client)
                <div class="flex mt-4 space-x-2">
                    @if ($edit)
                        <x-primary-button type="submit">Salvar</x-primary-button>
                        <x-secondary-button wire:click.prevent='cancelEdit'>Cancelar</x-secondary-button>
                    @else
                        <x-primary-button label="Editar" wire:click.prevent='startEdit'>Editar</x-primary-button>
                    @endif
                </div>
            @endcan
        </form>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
