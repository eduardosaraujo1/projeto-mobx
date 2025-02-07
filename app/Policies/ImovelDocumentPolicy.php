<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Facades\SelectedImobiliaria;
use App\Models\ImovelDocument;
use App\Models\User;

class ImovelDocumentPolicy
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
    public function view(User $user, ImovelDocument $imovelDocument): bool
    {
        $verdict = $user->is_admin
            || SelectedImobiliaria::is($imovelDocument->imovel->imobiliaria, $user);

        return $verdict;
    }

    public function download(User $user, ImovelDocument $imovelDocument): bool
    {
        $verdict = $user->is_admin
            || SelectedImobiliaria::is($imovelDocument->imovel->imobiliaria, $user);

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
    public function update(User $user, ImovelDocument $imovelDocument): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ImovelDocument $imovelDocument): bool
    {
        $verdict = $user->is_admin
            || $user->getRole($imovelDocument->imovel->imobiliaria) === UserRole::GERENTE
            && SelectedImobiliaria::is($imovelDocument->imovel->imobiliaria);

        return $verdict;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ImovelDocument $imovelDocument): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ImovelDocument $imovelDocument): bool
    {
        return false;
    }
}
