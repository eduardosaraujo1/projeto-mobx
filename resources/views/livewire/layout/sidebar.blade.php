<?php
use Livewire\Volt\Component;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Imobiliaria;

/**
 *
 * @return Collection<Imobiliaria>
 */
function getUserImobiliarias(): Collection
{
    return auth()->user()->imobiliarias;
}

new class extends Component {
    //
}; ?>

<!-- Navbar Lateral (Responsiva) -->
<nav
    class="fixed z-50 flex flex-col w-64 h-full p-6 space-y-6 text-gray-800 bg-white border-r border-gray-200 shadow-lg sm:w-1/3 lg:w-64">
    <a class="flex items-center" href="{{ route('imobiliaria.index') }}">
        <img src="{{ asset('images/mobx.svg') }}" alt="Logo Mobx" class="w-12 h-12 mr-4 bg-black rounded shadow-md">
        <h2 class="text-2xl font-bold">Mobx</h2>
    </a>
    <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

    <x-native-select label="Imobiliaria Selecionada" wire:model.change=''>
        @foreach (getUserImobiliarias() as $imobiliaria)
            <option value="{{ $imobiliaria->id }}">{{ Str::limit($imobiliaria->name, 20) }}</option>
        @endforeach
    </x-native-select>

    <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
    <!-- Links de navegação -->
    <ul>
        <li>
            <x-nav-link href="{{ route('imobiliaria.index') }}" :active="request()->routeIs('imobiliaria.index')" wire:navigate>
                Minha Imobiliaria
            </x-nav-link>
        </li>
        <li>
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" wire:navigate>
                Dashboard
            </x-nav-link>
        </li>
        <li>
            <x-nav-link href="{{ route('client.index') }}" :active="request()->routeIs('client.index')" wire:navigate>
                Imóveis
            </x-nav-link>
        </li>
        <li>
            <x-nav-link href="{{ route('imovel.index') }}" :active="request()->routeIs('imovel.index')" wire:navigate>
                Clientes
            </x-nav-link>
        </li>
    </ul>
</nav>
