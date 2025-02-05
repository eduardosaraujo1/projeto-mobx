<?php

namespace App\Models;

use App\Enums\ImovelStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

class Imovel extends Model
{
    /** @use HasFactory<\Database\Factories\ImovelFactory> */
    use HasFactory;
    protected $fillable = [
        'address_name',
        'address_number',
        'bairro',
        'is_lado_praia',
        'value',
        'iptu',
        'status',
        'photo_path',
        'client_id',
    ];

    protected $table = 'imoveis';

    protected function casts(): array
    {
        return [
            'is_lado_praia' => 'boolean'
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
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

    public function statusName()
    {
        return match ($this->status) {
            ImovelStatus::LIVRE->value => 'Livre',
            ImovelStatus::ALUGADO->value => 'Alugado',
            ImovelStatus::VENDIDO->value => 'Vendido'
        };
    }

    public function lado()
    {
        return $this->is_lado_praia ? 'Praia' : 'Morro';
    }

    public static function rules()
    {
        return [
            'address_name' => ['required', 'min:3', 'max:255'],
            'address_number' => ['integer', 'required', 'max_digits:4'],
            'bairro' => ['required', 'min:3', 'max:255'],
            'is_lado_praia' => ['boolean', 'required', 'decimal:0,2'],
            'value' => ['nullable', 'decimal:0,2'],
            'iptu' => ['nullable', 'between:1,8'],
            'status' => ['required', Rule::enum(ImovelStatus::class)],
            'photo_path' => ['nullable'],
            'client_id' => ['exists:clients,id'],
        ];
    }
}
