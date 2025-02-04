<?php

namespace App\Services;

use App\Enums\AccessLevel;
use Illuminate\Database\Eloquent\Collection;
use Session;
use App\Models\Imobiliaria;

class ImobiliariaService
{
    /**
     * Summary of user_imobiliarias
     * @return Collection<Imobiliaria>
     */
    public static function user_imobiliarias(): Collection
    {
        $user = auth()->user();

        // get user imobiliarias
        return $user->is_admin
            ? Imobiliaria::all()
            : $user->imobiliarias;
    }
    /**
     * Returns the imobiliaria the user currently has selected
     * Middleware 'has-imobiliaria' will ensure the return value is not null
     * @return Imobiliaria|null
     */
    public static function current_imobiliaria(): Imobiliaria|null
    {
        // get user imobiliarias
        $imobiliarias = static::user_imobiliarias();

        // get stored index
        $index_imobiliaria = Session::get('index_imobiliaria', 0);

        // get the imobiliaria
        return $imobiliarias[$index_imobiliaria] ?? null;
    }

    public static function current_access_level(): AccessLevel|null
    {
        return AccessLevel::tryFrom(static::current_imobiliaria()->access->level ?? null);
    }
}
