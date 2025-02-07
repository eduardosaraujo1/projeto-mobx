<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class ImovelDocument extends Model
{
    /** @use HasFactory<\Database\Factories\ImovelDocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'filepath',
        'filename',
        'filesize',
        'imovel_id',
    ];

    public function imovel()
    {
        return $this->belongsTo(Imovel::class);
    }

    public static function rules()
    {
        return [
            'filename' => ['required', 'min:3', 'max:255'],
            'filepath' => ['required', 'max:255'],
            'imovel_id' => ['required', 'exists:imoveis,id'],
        ];
    }

    public static function validator(array $data)
    {
        return Validator::make($data, static::rules());
    }
}
