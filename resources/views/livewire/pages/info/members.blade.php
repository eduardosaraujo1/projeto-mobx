<?php

use App\Enums\UserRole;
use App\Models\Imobiliaria;
use App\Models\Role;
use App\Models\User;
use App\Services\SearchService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    /**
     * list of users with their respective access level to this imobiliaria
     *
     * @var Collection<User>
     */
    public Collection $userList;

    public Imobiliaria $imobiliaria;

    public string $searchString = '';

    public function mount(Imobiliaria $imobiliaria)
    {
        $this->authorize('view', $imobiliaria);
        $this->userList = $this->orderByAccess($imobiliaria);
        $this->imobiliaria = $imobiliaria;
    }

    public function with(SearchService $search)
    {
        return [
            'users' => $search->userSearch($this->userList, $this->searchString, null),
        ];
    }

    /**
     * @param  Illuminate\Database\Eloquent\Collection<User>  $users
     */
    public function orderByAccess(Imobiliaria $imobiliaria)
    {
        // SQL QUERY:
        // select u.id, u.name, u.email, u.is_admin, iu.imobiliaria_id, iu.role
        // from users as u
        // left join
        //   (select user_id, imobiliaria_id, role from imobiliaria_user where imobiliaria_id = @CURRENT_IMOBILIARIA) as iu
        // on u.id = iu.user_id
        // where u.id != @CURRENT_USER or u.is_admin != 1
        // order by iu.imobiliaria_id desc, u.name;

        // get all the accesses set for this imobiliaria (the subquery in the select statement)
        $accesses = Role::where('imobiliaria_id', '=', (string) $imobiliaria->id);

        // get all the users along side their access (the outer query in the select statement)
        $result = User::select(['id', 'name', 'email', 'is_admin', 'imobiliaria_id', 'role'])
            ->leftJoinSub($accesses, 'imobiliaria_user', function (JoinClause $join) {
                $join->on('users.id', '=', 'imobiliaria_user.user_id');
            })
            ->withCasts(['role' => UserRole::class])
            ->whereNot('id', '=', auth()->user()->id) // disallow management of the current user
            ->whereNot('is_admin', '=', true) // disalow management of admins
            ->orderBy('imobiliaria_user.imobiliaria_id', 'desc')
            ->orderBy('users.name')
            ->get();

        return $result;
    }

    public function updateRole(int $userId, ?string $roleName)
    {
        // treat incoming data
        /**
         * @var ?User|null
         */
        $user = User::find($userId);
        $role = UserRole::fromName($roleName);

        // authorize user action without the standard error modal
        if (Gate::denies('update', $user)) {
            $this->addError('authorization_error', 'Ocorreu um erro de permissão. Tente novamente mais tarde');

            return;
        }

        // validate user exists
        if (! isset($user)) {
            $this->addError('authorization_error', 'Ocorreu um erro desconhecido. Tente novamente mais tarde');

            return;
        }

        // reference the current imobiliaria for detaching and attaching rows
        $imobiliaria = $this->imobiliaria;

        if (isset($role)) {
            // syncWithoutDetaching either updates or adds a new registry to the pivot table
            $user->imobiliarias()->syncWithoutDetaching([$imobiliaria->id => ['role' => $role->value]]);
        } else {
            // remove the imobiliaria from the user's imobiliaria list, in other words, deleting his permission row from the pivot table
            $user->imobiliarias()->detach($imobiliaria->id);
        }
    }
}; ?>


<div class="space-y-2">
    <x-errors title="Erro" />
    <x-slot name="heading">{{ $imobiliaria->name }}: Gerenciar Membros</x-slot>
    <div class="flex gap-2">
        <x-input type="text" id="searchBar" wire:model.live.debounce="searchString" class="flex-1" placeholder="Pesquisar (Nome ou e-mail)" />
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
                        <span class="block font-bold">Permissões:</span>
                        <form
                            class="flex gap-2"
                            x-data="{
                                role: @js($user->role?->getName() ?? "None"),
                                onChange() {
                                    $wire.updateRole(@js($user->id), this.role)
                                },
                            }"
                            x-on:change="onChange()"
                        >
                            <x-radio id="none-{{$loop->index}}" name="role-{{$loop->index}}" label="Nenhum" value="None" x-model="role" />
                            <x-radio id="colaborador-{{$loop->index}}" name="role-{{$loop->index}}" label="Colaborador" value="Colaborador" x-model="role" />
                            <x-radio id="gerente-{{$loop->index}}" name="role-{{$loop->index}}" label="Gerente" value="Gerente" x-model="role" />
                        </form>
                        <span class="block">{{ App\Utils\StringUtils::cpfFormat($user->cpf ?? "") }}</span>
                    </div>
                </div>
            @empty
                <x-alert title="Nenhum cliente foi encontrado" />
            @endforelse
        </div>
    </div>
</div>
