<?php

use Livewire\Volt\Component;
use App\Models\Client;
use App\Enums\ClientType;
use Illuminate\Validation\Rule;

new class extends Component {
    public Client $client;
    public bool $edit = false;

    // client attributes
    public string $name;
    public string $cpf;
    public string $email;
    public string $address;
    public string $type;

    public function mount(string $id)
    {
        $this->client = Client::find($id);
        $this->rebindValues();
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:255'],
            'cpf' => ['required', 'min:11', 'max:11'],
            'email' => ['email', 'min:3', 'max:255'],
            'address' => ['min:3', 'max:255'],
            'type' => ['required', Rule::enum(ClientType::class)],
        ];
    }

    protected function rebindValues()
    {
        $this->name = $this->client->name;
        $this->cpf = $this->client->cpf;
        $this->email = $this->client->email;
        $this->address = $this->client->address;
        $this->type = $this->client->type;
    }

    public function save()
    {
        $this->authorize('update', $this->client);
        $this->validate();
        $this->client->update([
            'name' => $this->name,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'address' => $this->address,
            'type' => $this->type,
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
    @can('view', $client)
        <form class="flex flex-col h-full gap-1" wire:submit='save'>
            <x-errors class='mb-4' />
            <x-card>
                <div class="flex items-center space-x-2">
                    <span class="block text-lg font-bold min-w-max">Nome:</span>
                    <input type="text" @disabled(!$edit) wire:model='name' required
                        class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
                </div>
            </x-card>
            <x-card>
                <div class="flex items-center space-x-2">
                    <span class="block text-lg font-bold min-w-max">CPF:</span>
                    <input type="text" @disabled(!$edit) wire:model='cpf' required
                        class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
                </div>
            </x-card>
            <x-card>
                <div class="flex items-center space-x-2">
                    <span class="block text-lg font-bold min-w-max">E-mail:</span>
                    <input type="text" @disabled(!$edit) wire:model='email' required
                        class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
                </div>
            </x-card>
            <x-card>
                <div class="flex items-center space-x-2">
                    <span class="block text-lg font-bold min-w-max">Endereço:</span>
                    <input type="text" @disabled(!$edit) wire:model='address' required
                        class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
                </div>
            </x-card>
            <x-card>
                <div class="flex items-center space-x-2">
                    <span class="block text-lg font-bold min-w-max">Tipo</span>
                    <select @disabled(!$edit) wire:model='type'
                        class="block py-0 border-transparent outline-none ring-0 {{ $edit ? 'border-b-black' : '' }}">
                        <option value="0">Locador</option>
                        <option value="1">Vendedor</option>
                    </select>
                </div>
            </x-card>
            @can('update', $client)
                <div class="flex mt-auto space-x-2">
                    @if ($edit)
                        <x-primary-button type="submit">Salvar</x-primary-button>
                        <x-secondary-button wire:click='cancelEdit'>Cancelar</x-secondary-button>
                    @else
                        <x-primary-button wire:click='startEdit'>Editar</x-primary-button>
                    @endif
                </div>
            @endcan
        </form>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
