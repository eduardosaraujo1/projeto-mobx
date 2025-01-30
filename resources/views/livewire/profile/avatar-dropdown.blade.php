<?php

use Livewire\Volt\Component;
use App\Livewire\Actions\Logout;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div x-data="{ open: false }" class="relative" @click.outside="open = false">
    <div class="rounded-full cursor-pointer" @click="open = true">
        <x-avatar sm label="A" class="!bg-green-400" />
    </div>

    <!-- Dropdown menu -->
    <div class="absolute right-0 z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600"
        :class="open || 'hidden'">
        <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
            <div>{{ auth()->user()->name }}</div>
            <div class="font-medium truncate">{{ auth()->user()->email }}</div>
        </div>
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
            @if (auth()->user()->is_admin ?? false)
                <li>
                    <a href="{{ route('admin.index') }}" wire:navigate
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                        Painel Administrativo
                    </a>
                </li>
            @endif
            <li>
                <a href="#"
                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                    Configurações
                </a>
            </li>
        </ul>
        <div class="py-1">
            <a href="#" wire:click='logout'
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                Sair
            </a>
        </div>
    </div>

</div>
