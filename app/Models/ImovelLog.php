<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImovelLog extends Model
{
    /** @use HasFactory<\Database\Factories\ImovelLogFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'imovel_id',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function imovel(): BelongsTo
    {
        return $this->belongsTo(Imovel::class);
    }

    public static function rules(): array
    {
        return [
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'imovel_id' => ['required', 'exists:imoveis,id'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }
}
