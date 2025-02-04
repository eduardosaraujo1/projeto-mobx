<?php

use Livewire\Volt\Component;
use App\Models\Client;
use App\Services\ImobiliariaService;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public string $name;
    public string $cpf;
    public string $email;
    public string $address;
    public int $imobiliaria_id;

    public function mount()
    {
        $this->imobiliaria_id = ImobiliariaService::current_imobiliaria()->id;
    }

    public function rules()
    {
        return Client::rules();
    }

    public function create()
    {
        // Ensure authorization (even if redundant)
        $this->authorize('create', Client::class);

        // validate fields
        $validated = $this->validate();

        // create instance
        $client = Client::create($validated);

        // display user message
        if ($client) {
            session()->flash('message', 'Cliente cadastrado com sucesso');
        } else {
            session()->flash('error', 'Não foi possivel cadastrar o cliente');
        }

        // redirect to main page
        $this->redirect(route('client.index'));
    }
}; ?>

<x-slot name="heading">
    Novo Cliente
</x-slot>
<div>
    @can('create', Client::class)
        <x-errors class="mb-4" />
        <form class="flex flex-col items-start gap-2 p-4 bg-white rounded shadow " wire:submit='create'>
            <x-input errorless label="Nome: " wire:model='name' />
            <x-maskable errorless mask="###.###.###-##" placeholder="123.456.789-10" label="CPF: " wire:model='cpf' />
            <x-input errorless label="E-mail: " wire:model='email' />
            <x-input errorless label="Endereço: " wire:model='address' />
            <x-primary-button class="mt-4" type="submit">Cadastrar</x-primary-button>
        </form>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
