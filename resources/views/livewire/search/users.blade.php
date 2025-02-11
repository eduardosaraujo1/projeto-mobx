<?php

use App\Models\User;
use App\Services\SearchService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Volt\Component;

/**
 * Summary of userSearch
 *
 * @param  \Illuminate\Database\Eloquent\Collection<User>  $users
 * @param  string  $searchString
 * @param  string  $searchType
 * @return \Illuminate\Database\Eloquent\Collection<User>
 */
new class extends Component
{
    /**
     * Summary of imoveis
     *
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\User>
     */
    public $userList;

    public $searchType;

    public $searchString;

    public function mount()
    {
        $this->userList = User::all();
    }

    public function with(SearchService $search)
    {
        $filteredList = $search->userSearch($this->userList, $this->searchString ?? '', $this->searchType);
        $formatted = $this->userFormat($filteredList);

        return [
            'users' => $formatted,
        ];
    }

    public function searchUsers()
    {
        return User::all();
    }

    public function userFormat(Collection $users): array
    {
        return $users->map(function (User $user) {
            $arr = $user->toArray();
            $arr['updated_at'] = Carbon::create($arr['updated_at'])
                ->setTimezone('America/Sao_Paulo')
                ->format('d-m-Y H:i:s');

            return $arr;
        })->toArray();
    }
}; ?>


<div class="flex flex-col space-y-2">
    <div class="flex gap-2">
        <x-input type="text" id="searchBar" wire:model.live.debounce="searchString" class="flex-1" placeholder="Pesquisar (Nome ou E-mail)" />
        <div class="w-36">
            <x-select placeholder="Tipo de Usuário" class="" wire:model.live="searchType">
                <x-select.option label="Administrador" value="1" />
                <x-select.option label="Regular" value="0" />
            </x-select>
        </div>
        <x-primary-button href="{{ route('user.new') }}">Cadastrar</x-primary-button>
    </div>
    <div class="h-full bg-white rounded shadow">
        <div class="flex flex-col gap-1 p-4 overflow-scroll h-[40rem]">
            @forelse ($users as $user)
                <a
                    class="flex w-full px-4 py-2 space-x-2 bg-white border rounded shadow-sm"
                    href="{{ route("user.info", ["user" => $user["id"]]) }}"
                    wire:navigate
                    wire:key="{{ $user["id"] }}"
                >
                    <div class="mr-2">
                        <x-avatar xl label="U" @class(["!bg-gray-700", "!bg-red-700" => $user["is_admin"]]) />
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Nome:</span>
                        <span class="block">{{ $user["name"] }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">E-mail:</span>
                        <span class="block">{{ $user["email"] }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold">Ultima Atualização:</span>
                        <span class="block">{{ $user["updated_at"] }}</span>
                    </div>
                </a>
            @empty
                <x-alert title="Nenhumo usuário foi encontrado" />
            @endforelse
        </div>
    </div>
</div>
