<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public $test;
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<!-- Navbar Lateral (Responsiva) -->
<nav
    class="fixed z-50 flex flex-col w-64 h-full p-6 space-y-6 text-gray-800 bg-white border-r border-gray-200 shadow-lg sm:w-1/3 lg:w-64">
    <a class="flex items-center" href="{{ route('imobiliaria.index') }}">
        <img src="{{ asset('images/mobx.svg') }}" alt="Logo Mobx" class="w-12 h-12 mr-4 bg-black rounded shadow-md">
        <h2 class="text-2xl font-bold">Mobx</h2>
    </a>
    <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

    <x-native-select label="Select Status" wire:model.change=''>
        <option selected>Placeholder</option>
    </x-native-select>

    <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
    <!-- Links de navegação -->
    <ul>
        <li>
            {{-- <a href="{{ route('legacy.index') }}"
                class="flex items-center p-3 font-semibold underline transition-all duration-200 hover:bg-gray-100">Início</a> --}}
            <x-nav-link href="{{ route('imobiliaria.index') }}" :active="request()->routeIs('imobiliaria.index')">
                Minha Imobiliaria
            </x-nav-link>
        </li>
        <li>
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-nav-link>
        </li>
        <li>
            <x-nav-link href="{{ route('client.index') }}" :active="request()->routeIs('client.index')">
                Imóveis
            </x-nav-link>
        </li>
        <li>
            <x-nav-link href="{{ route('imovel.index') }}" :active="request()->routeIs('imovel.index')">
                Clientes
            </x-nav-link>
        </li>
    </ul>
</nav>
