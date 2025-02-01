<?php

use Livewire\Volt\Component;
use App\Enums\ClientType;
use Illuminate\Validation\Rule;
use App\Models\Client;
use App\Services\ImobiliariaService;

new class extends Component {
    public string $name;
    public string $cpf;
    public string $email;
    public string $address;
    public string $type;

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

    public function create()
    {
        $this->validate();

        $imobiliaria = ImobiliariaService::current_imobiliaria();
        if (!isset($imobiliaria)) {
            abort(500);
        }

        $client = Client::create([
            'name' => $this->name,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'address' => $this->address,
            'type' => $this->type,
            'imobiliaria_id' => $imobiliaria->id,
        ]);

        if ($client) {
            session()->flash('message', 'Cliente cadastrado com sucesso');
        } else {
            session()->flash('error', 'Não foi possivel cadastrar o cliente');
        }
        $this->redirect(route('client.index'));
    }
}; ?>

<div class="flex-1">
    @can('create', Client::class)
        <form class="flex flex-col items-start h-full gap-2 p-4 bg-white rounded shadow " wire:submit='create'>
            <x-errors class="mb-4" />
            <x-input errorless label="Nome: " wire:model='name' />
            <x-maskable errorless mask="###.###.###-##" placeholder="123.456.789-10" label="CPF: " wire:model='cpf' />
            <x-input errorless label="E-mail: " wire:model='email' />
            <x-input errorless label="Endereço: " wire:model='address' />
            <x-select errorless label="Tipo: " placeholder="Selecione" class="py-0" wire:model='type'>
                <x-select.option selected value="0">Locador</x-select.option>
                <x-select.option value="1">Vendedor</x-select.option>
            </x-select>
            <x-button type="submit" class="!ring-0 mt-auto" black label="Cadastrar" />
        </form>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>

{{-- Must be live component because of error validation (no refreshing) --}}
