<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    protected $fillable = [
        'cpf',
        'name',
        'email',
        'address',
        'type',
        'imobiliaria_id'
    ];

    public function imobiliaria(): BelongsTo
    {
        return $this->belongsTo(Imobiliaria::class);
    }

    public function imoveis(): HasMany
    {
        return $this->hasMany(Imovel::class);
    }

    public static function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:255'],
            'cpf' => ['required', 'size:11'],
            'email' => ['nullable', 'email', 'min:3', 'max:255'],
            'address' => ['nullable', 'min:3', 'max:255'],
            'imobiliaria_id' => ['exists:imobiliarias,id'],
        ];
    }
}
