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
    public string $type;

    protected function rules()
    {
        return Client::rules();
    }

    public function create()
    {
        $validated = $this->validate();

        // get current imobiliaria id
        $imobiliaria_id = ImobiliariaService::current_imobiliaria()->id ?? abort(500);

        $client = Client::create([...$validated, 'imobiliaria_id' => $imobiliaria_id]);

        if ($client) {
            session()->flash('message', 'Cliente cadastrado com sucesso');
        } else {
            session()->flash('error', 'Não foi possivel cadastrar o cliente');
        }
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
            <x-select errorless label="Tipo: " placeholder="Selecione" class="py-0" wire:model='type'>
                <x-select.option selected value="0">Locador</x-select.option>
                <x-select.option value="1">Vendedor</x-select.option>
            </x-select>
            <x-primary-button class="mt-4" type="submit">Cadastrar</x-primary-button>
        </form>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
