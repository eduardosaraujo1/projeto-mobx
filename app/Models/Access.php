<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Access extends Pivot
{
    public const ACCESS_LEVEL_COLABORADOR = 0;
    public const ACCESS_LEVEL_GERENTE = 1;
}
