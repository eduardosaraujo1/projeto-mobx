<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Deletar Conta
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Uma vez que sua conta for excluída, todos os seus recursos e dados serão
            permanentemente excluídos. Antes de excluir sua conta, por favor faça o download de quaisquer dados ou
            informações que você deseja manter.
        </p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">Deletar
        Conta</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">

            <h2 class="text-lg font-medium text-gray-900">
                Tem certeza que deseja deletar sua conta?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Todos seus dados serão permanentemente apagados. Digite sua senha novamente para confirmar a ação.
            </p>

            <div class="mt-6">
                <x-input wire:model='password' placeholder='Senha' />
            </div>
            <div class="flex justify-end mt-6">

                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>

                <x-danger-button class="ms-3">Apagar Conta</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
