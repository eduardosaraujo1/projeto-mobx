<?php

use App\Facades\SelectedImobiliaria;
use App\Models\Client;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $name;

    public string $cpf;

    public string $email;

    public string $address;

    public int $imobiliaria_id;

    public function mount()
    {
        $this->imobiliaria_id = SelectedImobiliaria::get(auth()->user())->id;
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


<div>
    <h2 class="my-4 text-4xl font-semibold leading-tight">Novo Cliente</h2>
    @can("create", Client::class)
        <x-errors class="mb-4" />
        <form class="flex flex-col items-start gap-2 p-4 bg-white rounded shadow" wire:submit="create">
            <x-input errorless label="Nome: " wire:model="name" />
            <x-maskable errorless mask="###.###.###-##" placeholder="123.456.789-10" label="CPF: " wire:model="cpf" />
            <x-input errorless label="E-mail: " wire:model="email" />
            <x-input errorless label="Endereço: " wire:model="address" />
            <x-primary-button class="mt-4" type="submit">Cadastrar</x-primary-button>
        </form>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
