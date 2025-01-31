<?php

use Livewire\Volt\Component;
use App\Models\User;

/**
 * Summary of userSearch
 * @param \Illuminate\Database\Eloquent\Collection<User> $users
 * @param string $searchString
 * @param string $searchType
 * @return \Illuminate\Database\Eloquent\Collection<User>
 */
function userSearch($users, $searchString, $searchType)
{
    return $users->filter(function (User $user) use ($searchString, $searchType) {
        $verdict = true;

        // data
        $userName = strtolower($user->name ?? '');
        $userEmail = strtolower($user->email ?? '');
        $userType = $user->is_admin ? '1' : '0';

        // formatted queries
        $haystack = "$userName $userEmail";
        $needle = $searchString ?? '';

        // search filter
        $verdict = str_contains($haystack, $needle);

        // type filter
        if (isset($searchType)) {
            $verdict = $verdict && $userType === $searchType;
        }

        return $verdict;
    });
}

new class extends Component {
    /**
     * Summary of imoveis
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\User>
     */
    public $users;
    public $searchType;
    public $searchString;
    public function mount()
    {
        $this->users = User::all();
    }
}; ?>

<div class="flex flex-col h-full space-y-2">
    <div class="flex gap-2">
        <x-input type="text" id="searchBar" wire:model.live.debounce='searchString' class="flex-1"
            placeholder="Pesquisar (Nome ou E-mail)" />
        <x-select placeholder="Tipo de Usuário" class="w-min" wire:model.live='searchType'>
            <x-select.option label="Administrador" value="1" />
            <x-select.option label="Regular" value="0" />
        </x-select>
        <x-regular-button label="Cadastrar" href="{{ route('user.new') }}" />
    </div>
    <div class="h-full bg-white rounded shadow">
        <div class="flex flex-col gap-4 p-4 overflow-scroll h-[40rem]">
            @foreach (userSearch($users, $searchString, $searchType) as $user)
                <a href="#" class="flex w-full px-4 py-2 space-x-2 bg-white border rounded shadow ">
                    <div class="mr-2">
                        <x-avatar xl label="U" @class(['!bg-gray-700', '!bg-red-700' => $user->is_admin]) />
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Nome:</span>
                        <span class="block">{{ $user->name }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">E-mail:</span>
                        <span class="block">{{ $user->email }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Ultima Atualização</span>
                        <span class="block">{{ $user->updated_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
