<?php

namespace App\Models;

use App\Enums\ImovelStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Str;

class Imovel extends Model
{
    /** @use HasFactory<\Database\Factories\ImovelFactory> */
    use HasFactory;

    protected $table = 'imoveis';

    protected function casts(): array
    {
        return [
            'is_lado_praia' => 'boolean'
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Imovel::class);
    }

    public function imovelDocuments(): HasMany
    {
        return $this->hasMany(ImovelDocument::class);
    }

    public function imovelLogs(): HasMany
    {
        return $this->hasMany(ImovelLog::class);
    }

    public function fullAddress()
    {
        return implode(', ', [
            $this->address_name,
            $this->address_number,
            $this->bairro,
        ]);
    }

    public function getStatusName()
    {
        return match ($this->status) {
            ImovelStatus::LIVRE->value => 'Livre',
            ImovelStatus::ALUGADO->value => 'Alugado',
            ImovelStatus::VENDIDO->value => 'Vendido'
        };
    }
}
