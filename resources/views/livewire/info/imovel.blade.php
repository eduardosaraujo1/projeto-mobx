<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Imovel;

new #[Layout('layouts.app')] class extends Component {
    public Imovel $imovel;
    public bool $edit = false;

    public function mount() {}

    public function save() {}
}; ?>

<div class="flex-1">
    <x-slot name="heading">
        Dados do Imóvel
    </x-slot>
    <form class="flex flex-col h-full space-y-1" wire:submit='save'>
        <x-card>
            <div class="flex items-center space-x-2">
                <span class="block text-lg font-bold min-w-max">Endereço:</span>
                <input @class([
                    'block w-full p-1 outline-none focus:ring-0',
                    'border-none' => !$edit,
                    'border-0 border-b' => $edit,
                ]) type="text" value="{{ $imovel->fullAddress() }}" />
            </div>
        </x-card>
        <x-card>
            <div class="flex items-center space-x-2">
                <span class="block text-lg font-bold min-w-max">Localização:</span>
                <input @class([
                    'block w-full p-1 outline-none focus:ring-0',
                    'border-none' => !$edit,
                    'border-0 border-b' => $edit,
                ]) type="text" value="Ipsum" />
            </div>
        </x-card>
        <x-card>
            <div class="flex items-center space-x-2">
                <span class="block text-lg font-bold min-w-max">Valor:</span>
                <input @class([
                    'block w-full p-1 outline-none focus:ring-0',
                    'border-none' => !$edit,
                    'border-0 border-b' => $edit,
                ]) type="text" value="Ipsum" />
            </div>
        </x-card>
        <x-card>
            <div class="flex items-center space-x-2">
                <span class="block text-lg font-bold min-w-max">IPTU:</span>
                <input @class([
                    'block w-full p-1 outline-none focus:ring-0',
                    'border-none' => !$edit,
                    'border-0 border-b' => $edit,
                ]) type="text" value="Ipsum" />
            </div>
        </x-card>
        <x-card>
            <div class="flex items-center space-x-2">
                <span class="block text-lg font-bold min-w-max">Status</span>
                <input @class([
                    'block w-full p-1 outline-none focus:ring-0',
                    'border-none' => !$edit,
                    'border-0 border-b' => $edit,
                ]) type="text" value="Ipsum" />
            </div>
        </x-card>
        <x-card>
            <div class="flex items-center space-x-2">
                <span class="block text-lg font-bold min-w-max">Cliente</span>
                <input @class([
                    'block w-full p-1 outline-none focus:ring-0',
                    'border-none' => !$edit,
                    'border-0 border-b' => $edit,
                ]) type="text" value="Ipsum" />
                <x-button outline black interaction:solid label="Alterar" class="ml-auto !ring-0" />
            </div>
        </x-card>
        <div class="flex flex-col items-start justify-end flex-1 mt-auto">
            <div class="flex space-x-2">
                <x-regular-button label="{{ $edit ? 'Salvar' : 'Editar' }}" />
                @if (!$edit)
                    <x-button outline interaction:solid red label="Excluir" class="!ring-0" />
                @endif
            </div>
        </div>
    </form>
</div>
