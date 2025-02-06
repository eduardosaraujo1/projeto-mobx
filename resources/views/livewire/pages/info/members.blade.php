<?php

use App\Models\Client;
use App\Models\Imobiliaria;
use App\Services\SearchService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    /**
     * Summary of imoveis
     *
     * @var Collection<Client>
     */
    public Collection $userList;

    public string $searchString = '';

    public function mount(Imobiliaria $imobiliaria)
    {
        $this->userList = $imobiliaria->users;
    }

    public function with(SearchService $search)
    {
        return [
            'users' => $search->userSearch($this->userList, $this->searchString, null),
        ];
    }
}; ?>


<div class="space-y-2">
    <x-slot name="heading">Gerenciar Membros da Imobiliaria</x-slot>
    <div class="flex gap-2">
        <x-input type="text" id="searchBar" wire:model.live.debounce="searchString" class="flex-1" placeholder="Pesquisar (Nome ou E-mail)" />
    </div>
    <div class="bg-white rounded shadow h-[40rem] overflow-y-scroll">
        <div class="flex flex-col gap-1 p-4">
            @forelse ($users as $user)
                <div class="flex w-full px-4 py-2 space-x-2 bg-white border rounded shadow-sm" wire:key="{{ $user->id }}">
                    <div class="me-2">
                        <x-avatar xl label="U" class="!bg-gray-700" />
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Nome:</span>
                        <span class="block">{{ $user->name ?? "" }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">E-mail:</span>
                        <span class="block">{{ $user->email }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Permissões</span>
                        <div class="flex gap-2">
                            <x-radio id="nenhum" label="Nenhum" value="none" />
                            <x-radio id="colaborador" label="Colaborador" value="colaborador" />
                            <x-radio id="gerente" label="Gerente" value="gerente" />
                        </div>
                        <span class="block">{{ App\Utils\StringUtils::cpfFormat($user->cpf ?? "") }}</span>
                    </div>
                </div>
            @empty
                <x-alert title="Nenhum cliente foi encontrado" />
            @endforelse
        </div>
    </div>
</div>
