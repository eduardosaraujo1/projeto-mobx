<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Facades\SelectedImobiliaria;
use App\Models\Client;
use App\Models\User;

class ClientPolicy
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
    public function view(User $user, Client $client): bool
    {
        $verdict = $user->is_admin
            || SelectedImobiliaria::is($client->imobiliaria, $user);

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
    public function update(User $user, Client $client): bool
    {
        $verdict = $user->is_admin
            || SelectedImobiliaria::is($client->imobiliaria, $user)
            || SelectedImobiliaria::accessLevel($user) === UserRole::GERENTE;

        return $verdict;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Client $client): bool
    {
        $verdict = $user->is_admin;

        return $verdict;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Client $client): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Client $client): bool
    {
        return false;
    }
}
