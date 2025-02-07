<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    // user attributes
    public string $name;

    public string $email;

    public string $password;

    public string $password_confirmation;

    public bool $is_admin;

    // form attributes
    public ?string $userType = null;

    public function rules()
    {
        return User::rules();
    }

    public function create()
    {
        // Ensure authorization (even if redundant)
        $this->authorize('create', User::class);

        // apply calculated user attributes
        $this->is_admin = $this->userType === 'admin';

        // validate fields
        $validated = $this->validate();

        // create instance
        $user = User::create($validated);

        // display user message
        if ($user) {
            session()->flash('message', 'Usuário cadastrado com sucesso');
        } else {
            session()->flash('error', 'Não foi possivel cadastrar o usuário');
        }

        // redirect to main page
        $this->redirect(route('admin.index'));
    }
}; ?>


<x-slot name="heading">Novo Usuário</x-slot>
<div>
    @can("create", User::class)
        <x-errors class="mb-4" />
        <form class="flex flex-col items-start gap-2 p-4 bg-white rounded shadow" wire:submit="create">
            <x-input label="Nome: " wire:model="name" />
            <x-input label="E-mail: " wire:model="email" />
            <div class="grid w-full grid-cols-2 gap-2">
                <x-password label="Senha: " wire:model="password" />
                <x-password label="Confirmar Senha: " wire:model="password_confirmation" />
            </div>
            <div>
                <span class="block text-sm font-medium text-gray-700 disabled:opacity-60 dark:text-gray-400 invalidated:text-negative-600 dark:invalidated:text-negative-700">
                    Tipo:
                </span>
                <x-radio id="member" label="Membro" wire:model="userType" value="member" />
                <x-radio id="admin" label="Administrador" wire:model="userType" value="admin" />
            </div>
            <x-primary-button class="mt-4" type="submit">Cadastrar</x-primary-button>
        </form>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
