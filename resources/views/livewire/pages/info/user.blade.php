<?php
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    // client attributes
    public User $user;

    public string $name;

    public string $email;

    public bool $is_admin;

    // password update attributes
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    // component state
    public bool $edit = false;

    public string $userType; // to set 'is_admin', the radio will assign a string here and the save() method will convert to boolean

    public function mount()
    {
        $this->rebindValues();
    }

    protected function rebindValues()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->is_admin = $this->user?->is_admin ?? false;
        $this->userType = $this->is_admin ? 'admin' : 'member';
    }

    public function rules()
    {
        $rules = User::rules();
        unset($rules['password']); // for some reason, livewire (or laravel idk) does not validate is the password variable is not set, EVEN if I rule is 'missing' or 'sometimes'

        return $rules;
    }

    public function save()
    {
        // prevent non-mamangers from editing
        $this->authorize('update', $this->user);

        // parse is_admin from $userType string to boolean
        $this->is_admin = $this->userType === 'admin';

        // validating form
        $validated = $this->validate($this->rules());

        // inserting new data into
        $this->user->fill($validated);
        $this->user->save();

        // update form disableing edit
        $this->stopEdit();

        // tell action messages that a save has just happened
        $this->dispatch('saved');
    }

    public function updatePassword()
    {
        $this->authorize('updatePassword', $this->user);

        try {
            $validated = $this->validate(['password' => User::rules()['password']]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        $this->user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        // reset editting state (if it was on previously)
        $this->stopEdit();

        $this->dispatch('saved');
    }

    public function startEdit()
    {
        $this->edit = true;
    }

    public function stopEdit()
    {
        $this->rebindValues();
        $this->clearValidation();
        $this->edit = false;
    }
}; ?>


<div>
    <x-slot name="heading">
        <div class="flex justify-between">
            <span class="flex-1">Visualizar Usuário</span>
        </div>
    </x-slot>
    @can("view", $user)
        <form class="flex flex-col h-full gap-1" wire:submit="save">
            <x-errors class="mb-4" />
            <x-card>
                <div>
                    <span class="block text-lg font-bold min-w-max">Nome:</span>
                    <x-input :disabled='!$edit' wire:model="name" required autofocus />
                </div>
            </x-card>
            <x-card>
                <div>
                    <span class="block text-lg font-bold min-w-max">Email</span>
                    <x-input :disabled='!$edit' wire:model="email" required autofocus />
                </div>
            </x-card>
            <x-card>
                <div>
                    <span class="block text-lg font-bold min-w-max">Senha</span>
                    <div class="flex items-end gap-2">
                        <x-password disabled required autofocus value="***********" />
                        @can("updatePassword", $user)
                            <x-secondary-button x-on:click.prevent="$dispatch('open-modal', 'updatePassword')">Alterar</x-secondary-button>
                        @endcan
                    </div>
                </div>
            </x-card>
            <x-card>
                <div>
                    <span class="block text-lg font-bold min-w-max">Tipo</span>
                    <x-radio :disabled="!$edit" id="member" label="Membro" wire:model="userType" value="member" />
                    <x-radio :disabled="!$edit" id="admin" label="Administrador" wire:model="userType" value="admin" />
                </div>
            </x-card>
            @can("update", $user)
                <div class="flex items-center mt-4 space-x-2">
                    @if ($edit)
                        <x-primary-button type="submit">Salvar</x-primary-button>
                        <x-secondary-button wire:click.prevent="stopEdit">Cancelar</x-secondary-button>
                    @else
                        <x-primary-button label="Editar" wire:click.prevent="startEdit">Editar</x-primary-button>
                    @endif
                    <x-action-message class="me-3" on="saved">Salvo</x-action-message>
                </div>
            @endcan
        </form>
        <x-modal name="updatePassword" focusable>
            <div class="p-6 space-y-2">
                <h2 class="text-lg font-medium">Alterar Senha</h2>
                <x-password wire:model="password" label="Nova Senha" />
                <x-password wire:model="password_confirmation" label="Confirmar Senha" />
                <div class="flex items-center gap-4">
                    <x-primary-button wire:click="updatePassword()" x-on:click="$dispatch('close')">Salvar</x-primary-button>
                    <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                </div>
            </div>
        </x-modal>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
