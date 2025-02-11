<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Imobiliaria;
use Illuminate\Foundation\Auth\User;

class SelectedImobiliariaService
{
    private const SESSION_KEY = 'imobiliaria_index';

    private ?Imobiliaria $imobiliariaCache;

    public function __construct() {}

    private function revalidateCache(?User $user): ?Imobiliaria
    {
        // use authed by default
        $user ??= auth()->user();

        $this->imobiliariaCache = $user?->all_imobiliarias[$this->getIndex()] ?? null;

        return $this->imobiliariaCache;
    }

    /**
     * Gets the current selected imobiliaria.
     *
     * @param  User  $user  the user from whom the imobiliaria is selected
     */
    public function get(?User $user = null): ?Imobiliaria
    {
        // use authed by default
        $user ??= auth()->user();

        return $this->imobiliariaCache ?? $this->revalidateCache($user);
    }

    /**
     * Sets the current imobiliaria to be selected
     */
    public function set(int $index): void
    {
        session()->put(static::SESSION_KEY, $index);
    }

    public function is(Imobiliaria $imobiliaria, ?User $user = null): bool
    {
        $user ??= auth()->user();

        return static::get($user)?->is($imobiliaria) ?? false;
    }

    public function getIndex(): int
    {
        return session(static::SESSION_KEY, 0);
    }

    public function accessLevel(?User $user = null): ?UserRole
    {
        return $user->getRole($this->get($user));
    }
}
