<?php
use Livewire\Volt\Component;
use App\Models\Imobiliaria;
use Livewire\Attributes\Layout;
use App\Facades\SelectedImobiliaria;

new #[Layout('layouts.app')] class extends Component {
    // client attributes
    public Imobiliaria $imobiliaria;
    public string $name;
    public string $cnpj;
    public string $address;
    public string $email;
    public string $contact;

    // component state
    public bool $edit = false;

    public function mount()
    {
        $this->imobiliaria = SelectedImobiliaria::get(auth()->user());
        $this->rebindValues();
    }

    protected function rebindValues()
    {
        $this->name = $this->imobiliaria->name;
        $this->address = $this->imobiliaria->address;
        $this->cnpj = $this->imobiliaria->cnpj;
        $this->email = $this->imobiliaria->email;
        $this->contact = $this->imobiliaria->contact;
    }

    public function save()
    {
        // prevent non-mamangers from editing
        $this->authorize('update', $this->imobiliaria);

        // validating form
        $validated = $this->validate(Imobiliaria::rules());

        // inserting new data into
        $this->imobiliaria->fill($validated);
        $this->imobiliaria->save();

        // update form disableing edit
        $this->stopEdit();
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
            <span class="flex-1">Imobiliaria {{ $imobiliaria->name }}</span>
        </div>
    </x-slot>
    @can('view', $imobiliaria)
        <form class="flex flex-col h-full gap-1" wire:submit='save'>
            <x-errors class='mb-4' />
            <x-card>
                <div>
                    <span class="block text-lg font-bold min-w-max">Nome:</span>
                    <x-input :disabled='!$edit' wire:model='name' required autofocus />
                </div>
            </x-card>
            <x-card>
                <div>
                    <span class="block text-lg font-bold min-w-max">CNPJ:</span>
                    <x-input :disabled='!$edit' wire:model='cnpj' required autofocus />
                </div>
            </x-card>
            <x-card>
                <div>
                    <span class="block text-lg font-bold min-w-max">Endereço</span>
                    <x-input :disabled='!$edit' wire:model='address' required autofocus />
                </div>
            </x-card>
            <x-card>
                <div>
                    <span class="block text-lg font-bold min-w-max">E-mail</span>
                    <x-input :disabled='!$edit' wire:model='email' autofocus />
                </div>
            </x-card>
            <x-card>
                <div>
                    <span class="block text-lg font-bold min-w-max">Contato:</span>
                    <x-input :disabled='!$edit' wire:model='contact' autofocus />
                </div>
            </x-card>
            @can('update', $imobiliaria)
                <div class="flex mt-4 space-x-2">
                    @if ($edit)
                        <x-primary-button type="submit">Salvar</x-primary-button>
                        <x-secondary-button wire:click.prevent='stopEdit'>Cancelar</x-secondary-button>
                    @else
                        <x-primary-button label="Editar" wire:click.prevent='startEdit'>Editar</x-primary-button>
                    @endif
                </div>
            @endcan
        </form>
    @else
        <x-alert negative title="Você não tem acesso a esse recurso. " />
    @endcan
</div>
