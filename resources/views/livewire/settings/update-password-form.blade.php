<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Alterar Senha
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Cerfique-se de utilizar uma senha longa e segura
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-6">
        <x-password wire:model='current_password' label='Senha Atual' />
        <x-password wire:model='password' label='Nova Senha' />
        <x-password wire:model='password_confirmation' label='Confirmar Senha' />

        <div class="flex items-center gap-4">
            <x-primary-button>Salvar</x-primary-button>

            <x-action-message class="me-3" on="password-updated">
                Salvo
            </x-action-message>
        </div>
    </form>
</section>
