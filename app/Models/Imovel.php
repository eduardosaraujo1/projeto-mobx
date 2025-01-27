<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
