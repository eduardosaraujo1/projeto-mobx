<?php

namespace App\Policies;

use App\Facades\SelectedImobiliaria;
use App\Models\Imovel;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Enums\UserRole;

class ImovelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Imovel $imovel): bool
    {
        return SelectedImobiliaria::get($user)->id === $imovel->imobiliaria->id || $user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return SelectedImobiliaria::accessLevel($user) === UserRole::GERENTE || $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Imovel $imovel): bool
    {
        return SelectedImobiliaria::accessLevel($user) === UserRole::GERENTE || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Imovel $imovel): bool
    {
        return SelectedImobiliaria::accessLevel($user) === UserRole::GERENTE || $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Imovel $imovel): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Imovel $imovel): bool
    {
        return false;
    }
}
