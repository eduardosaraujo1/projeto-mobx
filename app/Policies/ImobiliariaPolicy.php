<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Facades\SelectedImobiliaria;
use App\Models\Imobiliaria;
use App\Models\User;

class ImobiliariaPolicy
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
    public function view(User $user, Imobiliaria $imobiliaria): bool
    {
        return SelectedImobiliaria::get($user)->id === $imobiliaria->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Imobiliaria $imobiliaria): bool
    {
        return SelectedImobiliaria::accessLevel($user) === UserRole::GERENTE || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Imobiliaria $imobiliaria): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Imobiliaria $imobiliaria): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Imobiliaria $imobiliaria): bool
    {
        return false;
    }
}
