<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Imobiliaria;
use Illuminate\Foundation\Auth\User;

class SelectedImobiliariaService
{
    private const SESSION_KEY = 'imobiliaria_index';

    public function __construct() {}

    /**
     * Gets the current selected imobiliaria.
     *
     * @param  User  $user  the user from whom the imobiliaria is selected
     */
    public function get(User $user): ?Imobiliaria
    {
        $imobiliarias = $user->all_imobiliarias;

        return $imobiliarias[$this->getIndex()] ?? null;
    }

    /**
     * Sets the current imobiliaria to be selected
     */
    public function set(int $index): void
    {
        session()->put(static::SESSION_KEY, $index);
    }

    public function getIndex(): int
    {
        return session(static::SESSION_KEY, 0);
    }

    public function accessLevel(User $user): ?UserRole
    {
        return $this->get($user)?->role?->role;
    }
}
