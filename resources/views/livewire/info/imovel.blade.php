<?php

use Livewire\Volt\Component;
use App\Models\Imovel;
use App\Enums\ImovelStatus;
use Illuminate\Validation\Rule;

new class extends Component {
    public Imovel $imovel;
    public bool $edit = false;

    // client attributes
    public string $addressName;
    public int $addressNumber;
    public string $bairro;
    public string $lado;
    public string $value;
    public string $iptu;
    public string $status;
    public string $photoPath;

    public function mount(string $id)
    {
        $this->imovel = Imovel::find($id);
        $this->rebindValues();
    }

    protected function rules()
    {
        return [
            'addressName' => ['required', 'min:3', 'max:255'],
            'addressNumber' => ['integer', 'required', 'max_digits:4'],
            'bairro' => ['required', 'min:3', 'max:255'],
            'lado' => ['required', 'digits_between:0,1'],
            'value' => ['between:0,999999999.99'],
            'iptu' => ['between:0,999999999.99'],
            'status' => ['required', Rule::enum(ImovelStatus::class)],
            'photoPath' => [],
        ];
    }

    protected function rebindValues()
    {
        $this->addressName = $this->imovel->address_name;
        $this->addressNumber = $this->imovel->address_number;
        $this->bairro = $this->imovel->bairro;
        $this->lado = $this->imovel->is_lado_praia ? '1' : '0';
        $this->value = (string) $this->imovel->value;
        $this->iptu = (string) $this->imovel->iptu;
        $this->status = (string) $this->imovel->status;
        $this->photoPath = $this->imovel->photo_path;
    }

    public function save()
    {
        $this->authorize('update', $this->imovel);
        $this->validate();
        $this->imovel->update([
            'address_name' => $this->addressName,
            'address_number' => $this->addressNumber,
            'bairro' => $this->bairro,
            'is_lado_praia' => (string) $this->lado === '1',
            'value' => $this->value,
            'iptu' => $this->iptu,
            'status' => $this->status,
            'photo_path' => $this->photoPath,
        ]);

        $this->edit = false;
        // $this->imovel->update([
        //     'name' => $this->name,
        //     'cpf' => $this->cpf,
        //     'email' => $this->email,
        //     'address' => $this->address,
        //     'type' => $this->type,
        // ]);
        // $this->edit = false;
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
        Dados do Imóvel
    </x-slot>
    @can('view', $imovel)
        <form class="flex flex-col h-full gap-1" wire:submit='save'>
            <x-errors class='mb-4' />
            <x-card>
                <div class="flex items-center gap-2">
                    <img src="{{ $photoPath }}" alt="" class="w-32 aspect-square">
                    <span class="block text-lg font-bold min-w-max">Caminho Foto:</span>
                    <input type="text" @disabled(!$edit) wire:model='photoPath' required
                        class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
                </div>
            </x-card>
            <x-card class="flex flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <span class="block text-lg font-bold min-w-max">Endereço:</span>
                    <input type="text" @disabled(!$edit) wire:model='addressName' required
                        class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
                </div>
                <div class="flex items-center gap-2">
                    <span class="block text-lg font-bold min-w-max">Número:</span>
                    <input type="text" @disabled(!$edit) wire:model='addressNumber' required
                        class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
                </div>
                <div class="flex items-center gap-2">
                    <span class="block text-lg font-bold min-w-max">Bairro:</span>
                    <input type="text" @disabled(!$edit) wire:model='bairro' required
                        class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
                </div>
            </x-card>
            <x-card>
                <div class="flex items-center gap-2">
                    <span class="block text-lg font-bold min-w-max">Localização:</span>
                    <select @disabled(!$edit) wire:model='lado'
                        class="block py-0 border-transparent outline-none ring-0 {{ $edit ? 'border-b-black' : '' }}">
                        <option value="0">Morro</option>
                        <option value="1">Praia</option>
                    </select>
                </div>
            </x-card>
            <x-card>
                <div class="flex items-center gap-2">
                    <span class="block text-lg font-bold min-w-max">Valor:</span>
                    <input type="number" @disabled(!$edit) wire:model='value' required
                        class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
                </div>
                <div class="flex items-center gap-2">
                    <span class="block text-lg font-bold min-w-max">IPTU: </span>
                    <input type="number" @disabled(!$edit) wire:model='iptu' required
                        class="block w-full p-1 border-transparent outline-none {{ $edit ? 'border-b-black' : '' }} focus:ring-0">
                </div>
            </x-card>
            <x-card>
                <div class="flex items-center gap-2">
                    <span class="block text-lg font-bold min-w-max">Status: </span>
                    <select @disabled(!$edit) wire:model='status'
                        class="block py-0 border-transparent outline-none ring-0 {{ $edit ? 'border-b-black' : '' }}">
                        <option value="0" selected>Livre</option>
                        <option value="1">Alugado</option>
                        <option value="2">Vendido</option>
                    </select>
                </div>
            </x-card>
            @can('update', $imovel)
                <div class="flex gap-2 mt-auto">
                    @if ($edit)
                        <x-regular-button label="Salvar" type="submit" />
                        <x-button outline interaction:solid label="Cancelar" wire:click='cancelEdit' class="!ring-0" />
                    @else
                        <x-regular-button label="Editar" wire:click='startEdit' />
                    @endif
                </div>
            @endcan
        </form>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
