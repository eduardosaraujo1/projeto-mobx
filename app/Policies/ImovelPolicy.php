<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Facades\SelectedImobiliaria;
use App\Models\Imovel;
use App\Models\User;

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
        $verdict = $user->is_admin
            || SelectedImobiliaria::get($user)?->is($imovel->imobiliaria);

        return $verdict;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewLogs(User $user, Imovel $imovel): bool
    {
        $verdict = $user->is_admin
            || $user->getRole($imovel->imobiliaria) === UserRole::GERENTE
            && SelectedImobiliaria::is($imovel->imobiliaria, $user);

        return $verdict;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $verdict = $user->is_admin
            || SelectedImobiliaria::accessLevel($user) === UserRole::GERENTE;

        return $verdict;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Imovel $imovel): bool
    {
        $verdict = $user->is_admin
            || $user->getRole($imovel->imobiliaria) === UserRole::GERENTE
            && SelectedImobiliaria::is($imovel->imobiliaria, $user);

        return $verdict;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Imovel $imovel): bool
    {
        $verdict = $user->is_admin
            || $user->getRole($imovel->imobiliaria) === UserRole::GERENTE // manager in the imovel's imobiliaria
            && SelectedImobiliaria::is($imovel->imobiliaria, $user); // selecting the imovel's imobiliaria

        return $verdict;
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
