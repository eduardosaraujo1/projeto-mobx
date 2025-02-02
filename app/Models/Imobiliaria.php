<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Imobiliaria extends Model
{
    /** @use HasFactory<\Database\Factories\ImobiliariaFactory> */
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'address',
        'email',
        'contact',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_imobiliaria_access')->as('access')->withPivot('level');
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function imoveis(): HasManyThrough
    {
        return $this->hasManyThrough(Imovel::class, Client::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules()
    {
        return [
            'name' => ['string', 'required', 'min:3', 'max:255'],
            'address' => ['string', 'required', 'min:3', 'max:255'],
            'email' => ['email', 'required', 'min:3', 'max:255'],
            'contact' => ['string', 'required', 'min:3', 'max:255'],
        ];
    }
}
