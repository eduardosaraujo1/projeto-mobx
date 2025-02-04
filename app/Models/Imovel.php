<?php

namespace App\Models;

use App\Enums\ImovelLocation;
use App\Enums\ImovelStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;
use Storage;

class Imovel extends Model
{
    /** @use HasFactory<\Database\Factories\ImovelFactory> */
    use HasFactory;

    protected $table = 'imoveis';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
    protected $attributes = [
        'status' => 0
    ];

    protected function casts(): array
    {
        return [
            'status' => ImovelStatus::class,
            'location_reference' => ImovelLocation::class,
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

    public function base64Image(): string|null
    {
        if (!isset($this->photo_path) || Storage::disk('local')->missing($this->photo_path)) {
            return null;
        }

        $mime_types = [
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'bmp' => 'image/bmp',
            'svg' => 'image/svg+xml',
        ];

        // access photo more easily
        $photo_path = $this->photo_path;

        // get file extension
        $extension = strtolower(pathinfo($photo_path, PATHINFO_EXTENSION));

        // get mime type of stored file
        $mime_type = $mime_types[$extension] ?? 'image/png';

        // read the image as base64
        $photo_bin = Storage::disk('local')->get($photo_path);
        $base64 = base64_encode($photo_bin);

        // append mime type and return
        return "data:{$mime_type};base64,{$base64}";
    }

    public static function rules()
    {
        return [
            'address_name' => ['required', 'min:3', 'max:255'],
            'address_number' => ['integer', 'required', 'max_digits:4'],
            'bairro' => ['required', 'min:3', 'max:255'],
            'location_reference' => ['nullable', Rule::enum(ImovelLocation::class)],
            'value' => ['nullable', 'numeric'],
            'iptu' => ['nullable', 'numeric'],
            'status' => [Rule::enum(ImovelStatus::class)],
            'photo_path' => ['nullable'],
            'client_id' => ['nullable', 'exists:clients,id'],
        ];
    }
}
