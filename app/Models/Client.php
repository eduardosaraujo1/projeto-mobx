<?php

namespace App\Models;

use App\Enums\ClientType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    public function imobiliaria(): BelongsTo
    {
        return $this->belongsTo(Imobiliaria::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Imovel::class);
    }

    public function typeName()
    {
        return match (ClientType::from($this->type)) {
            ClientType::LOCADOR => 'Locador',
            ClientType::VENDEDOR => 'Vendedor',
        };
    }
}
