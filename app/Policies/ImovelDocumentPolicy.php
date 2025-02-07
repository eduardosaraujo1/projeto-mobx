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
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
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
        /**
         * @var \Illuminate\Database\Eloquent\Collection<\App\Models\Imovel>
         */
        $imoveis = SelectedImobiliaria::get($user)->imoveis->pluck('id');

        $is_manager = SelectedImobiliaria::accessLevel($user) === UserRole::GERENTE;
        $is_admin = $user->is_admin;
        $belongsToImobiliaria = $imoveis->contains($imovelDocument->imovel_id);

        return ($is_manager || $is_admin) && $belongsToImobiliaria;
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
