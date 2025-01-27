<?php

namespace App\Models;


enum AccessLevel: int
{
    case COLABORADOR = 0;
    case GERENTE = 1;
}
