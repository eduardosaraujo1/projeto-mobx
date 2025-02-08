<?php

use App\Models\Imobiliaria;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    // imobiliaria attributes
    public string $name;

    public string $cnpj;

    public string $address;

    public string $email;

    public string $contact;

    public function rules()
    {
        return Imobiliaria::rules();
    }

    public function create()
    {
        // Ensure authorization (even if redundant)
        $this->authorize('create', Imobiliaria::class);

        // validate fields
        $validated = $this->validate();

        // create instance
        $imobiliaria = Imobiliaria::create($validated);

        // display user message
        if ($imobiliaria) {
            session()->flash('message', 'Imobiliária cadastrada com sucesso');
        } else {
            session()->flash('error', 'Não foi possivel cadastrar a imobiliária');
        }

        // redirect to main page
        $this->redirect(route('admin.index'));
    }
}; ?>


<div>
    <h2 class="my-4 text-4xl font-semibold leading-tight">Nova Imobiliária</h2>
    @can("create", User::class)
        <x-errors class="mb-4" />
        <form class="flex flex-col items-start gap-2 p-4 bg-white rounded shadow" wire:submit="create">
            <x-input label="Nome: " wire:model="name" />
            <x-maskable mask="##.###.###/####-##" placeholder="00.00.000/0000-00" label="CNPJ: " wire:model="cnpj" />
            <x-input label="Endereço: " wire:model="address" />
            <x-input label="E-mail: " wire:model="email" />
            <x-input label="Contato: " wire:model="contact" />
            <x-primary-button class="mt-4" type="submit">Cadastrar</x-primary-button>
        </form>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
