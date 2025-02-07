<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rules\Password;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $attributes = [
        'is_admin' => false,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function imobiliarias(): BelongsToMany
    {
        return $this->belongsToMany(Imobiliaria::class, 'imobiliaria_user')
            ->using(Role::class)
            ->as('role') // 'role' pivot model reference (user->imobiliarias->role)
            ->withPivot('role'); // 'role' attribute name
    }

    /**
     * Gets the imobiliarias the user has access to. If the user is an administrator he will have all imobiliarias
     *
     * @return void
     */
    protected function allImobiliarias(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_admin ? Imobiliaria::all() : $this->imobiliarias
        );
    }

    public static function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'min:3', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers(),
            ],
            'is_admin' => ['required', 'boolean'],
        ];
    }
}
