<?php

namespace App\Services;

use App\Enums\ImovelLocation;
use App\Enums\ImovelStatus;
use App\Models\Imovel;
use App\Models\ImovelLog;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ImovelLogService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private Imovel $imovel,
        private User $user
    ) {
        //
    }

    private function translateFieldName($field)
    {
        return match ($field) {
            'id' => 'Identificador',
            'address_name' => 'Nome da rua',
            'address_number' => 'Número da rua',
            'bairro' => 'Bairro',
            'location_reference' => 'Localização',
            'value' => 'Valor',
            'iptu' => 'IPTU',
            'status' => 'Status',
            'photo_path' => 'Foto',
            'client_id' => 'ID Cliente',
            'imobiliaria_id' => 'ID Imobiliaria',
            default => $field,
        };
    }

    private function generateDescription(array $changes)
    {
        // remove 'updated at' field, which is irrelevant for logging
        unset($changes['updated_at']);

        // add each modified field to the description
        $description = '';
        foreach ($changes as $key => $value) {
            // parse fancy names
            if ($key === 'location_reference') {
                $value = ImovelLocation::tryFrom($value ?? -1)?->getName() ?? $value;
            } elseif ($key === 'status') {
                $value = ImovelStatus::tryFrom($value ?? -1)?->getName() ?? $value;
            }

            $name = $this->translateFieldName($key);
            if ($value === null) {
                $description .= "Definiu '$name' como vazio\n";
            } else {
                $description .= "Alterou '$name' para '$value'\n";
            }

        }

        return rtrim($description, "\n");
    }

    public function logChanges(array $changes)
    {
        $title = 'Edição imóvel #'.$this->imovel->id;
        $description = $this->generateDescription($changes);
        dump($description);

        if (empty($description)) {
            return;
        }

        if (empty($this->imovel?->id) || empty($this->user?->id)) {
            return;
        }

        $fields = [
            'title' => $title,
            'description' => $description,
            'imovel_id' => $this->imovel?->id,
            'user_id' => $this->user?->id,
        ];

        $validated = Validator::make($fields, [
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'imovel_id' => ['required', 'exists:imoveis,id'],
            'user_id' => ['required', 'exists:users,id'],
        ])->validate();

        ImovelLog::create($validated);
    }
}
