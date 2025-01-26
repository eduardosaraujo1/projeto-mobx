<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imovel extends Model
{
    /** @use HasFactory<\Database\Factories\ImovelFactory> */
    use HasFactory;

    protected $table = 'imoveis';

    protected function casts(): array
    {
        return [
            'lado_praia' => 'boolean'
        ];
    }
}
