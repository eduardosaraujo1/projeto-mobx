<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Imobiliaria extends Model
{
    /** @use HasFactory<\Database\Factories\ImovelFactory> */
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'endereco',
        'logo_path',
        'contato',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'access');
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
}
