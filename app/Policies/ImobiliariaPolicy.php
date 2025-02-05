<?php

namespace App\Policies;

use App\Enums\AccessLevel;
use App\Models\Imobiliaria;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Services\ImobiliariaService;

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
        return ImobiliariaService::current_imobiliaria()->id === $imobiliaria->id || $user->is_admin;
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
    public function update(User $user, Imobiliaria $imobiliaria): bool
    {
        return ImobiliariaService::current_access_level() === AccessLevel::GERENTE || $user->is_admin;
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
