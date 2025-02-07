<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>


<div class="h-screen p-2 space-y-4 bg-white shadow-lg w-min">
    <x-dropdown width="w-fit">
        <x-slot name="trigger">
            <div class="rounded-full cursor-pointer">
                <x-avatar md class="!bg-black" />
            </div>
        </x-slot>
        <x-slot name="content">
            <div class="px-4 py-3 text-base">
                <div>{{ auth()->user()->name }}</div>
                <div class="font-medium">{{ auth()->user()->email }}</div>
            </div>
            <hr class="my-2" />
            <ul>
                <x-dropdown-link href="{{ route('settings') }}" wire:navigate class="block px-4 py-2 hover:bg-gray-100">Configurações</x-dropdown-link>
                <hr class="my-2" />
                <x-dropdown-link href="#" wire:click="logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Sair</x-dropdown-link>
            </ul>
        </x-slot>
    </x-dropdown>
    <x-mini-button icon="bell" lg rounded black flat interaction:solid x-on:click="$dispatch('open-modal', 'notifications')" />
    <x-modal name="notifications">
        <div class="p-6">
            <h1 class="text-2xl font-bold">Notificações</h1>
            <h1>Work in Progress...</h1>
        </div>
    </x-modal>
</div>
