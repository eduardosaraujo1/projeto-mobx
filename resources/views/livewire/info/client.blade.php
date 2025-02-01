<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Client;
use App\Enums\ClientType;
use Illuminate\Validation\Rule;

new #[Layout('layouts.app')] class extends Component {
    public Client $client;
    public bool $edit = false;

    // client attributes
    public string $clientName;
    public string $clientCpf;
    public string $clientEmail;
    public string $clientAddress;
    public string $clientType;

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->rebindValues();
    }

    protected function rules()
    {
        return [
            'clientName' => ['required', 'min:3', 'max:255'],
            'clientCpf' => ['required', 'min:11', 'max:11'],
            'clientEmail' => ['email', 'min:3', 'max:255'],
            'clientAddress' => ['min:3', 'max:255'],
            'clientType' => ['required', Rule::enum(ClientType::class)],
        ];
    }

    protected function rebindValues()
    {
        $this->clientName = $this->client->name;
        $this->clientCpf = $this->client->cpf;
        $this->clientEmail = $this->client->email;
        $this->clientAddress = $this->client->address;
        $this->clientType = $this->client->type;
    }

    public function save()
    {
        $this->validate();
        $this->client->update([
            'name' => $this->clientName,
            'cpf' => $this->clientCpf,
            'email' => $this->clientEmail,
            'address' => $this->clientAddress,
            'type' => $this->clientType,
        ]);
        $this->edit = false;
    }
    public function startEdit()
    {
        $this->edit = true;
    }

    public function cancelEdit()
    {
        $this->rebindValues();
        $this->edit = false;
    }
}; ?>

<div class="flex-1">
    <x-slot name="heading">
        Dados do Cliente
    </x-slot>
    <form class="flex flex-col h-full space-y-1" wire:submit='save'>
        <x-errors class='mb-4' />
        <x-card>
            <div class="flex items-center space-x-2">
                <span class="block text-lg font-bold min-w-max">Nome:</span>
                <input type="text" @disabled(!$edit) wire:model='clientName' required
                    class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
            </div>
        </x-card>
        <x-card>
            <div class="flex items-center space-x-2">
                <span class="block text-lg font-bold min-w-max">Cpf:</span>
                <input type="text" @disabled(!$edit) wire:model='clientCpf' required
                    class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
            </div>
        </x-card>
        <x-card>
            <div class="flex items-center space-x-2">
                <span class="block text-lg font-bold min-w-max">E-mail:</span>
                <input type="text" @disabled(!$edit) wire:model='clientEmail' required
                    class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
            </div>
        </x-card>
        <x-card>
            <div class="flex items-center space-x-2">
                <span class="block text-lg font-bold min-w-max">Endereço:</span>
                <input type="text" @disabled(!$edit) wire:model='clientAddress' required
                    class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
            </div>
        </x-card>
        <x-card>
            <div class="flex items-center space-x-2">
                <span class="block text-lg font-bold min-w-max">Tipo</span>
                <select @disabled(!$edit) wire:model='clientType'
                    class="block py-0 border-transparent outline-none ring-0 {{ $edit ? 'border-b-black' : '' }}">
                    <option value="0">Locador</option>
                    <option value="1">Vendedor</option>
                </select>
            </div>
        </x-card>
        <div class="flex flex-col items-start justify-end flex-1 mt-auto">
            <div class="flex space-x-2">
                @if ($edit)
                    <x-regular-button label="Salvar" type="submit" />
                    <x-button outline interaction:solid label="Cancelar" wire:click='cancelEdit' class="!ring-0" />
                @else
                    <x-regular-button label="Editar" wire:click='startEdit' />
                    <x-button outline interaction:solid red label="Excluir" class="!ring-0" />
                @endif
            </div>
        </div>
    </form>
</div>
