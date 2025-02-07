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
        private ?User $user
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

    private function log(string $title, string $description)
    {
        $fields = [
            'title' => $title,
            'description' => $description,
            'imovel_id' => $this->imovel?->id,
            'user_id' => $this->user?->id,
        ];
        $validator = Validator::make($fields, ImovelLog::rules());
        $validated = $validator->validate();

        ImovelLog::create($validated);
    }

    public function logChanges(array $changes)
    {
        $title = 'Edição imóvel #'.$this->imovel->id;
        $description = $this->generateDescription($changes);

        if (empty($description)) {
            return false;
        }

        if (empty($this->imovel?->id) || empty($this->user?->id)) {
            return false;
        }

        $this->log($title, $description);
    }

    public function logDocumentUpload(string $filename, string $filesize)
    {
        // parse data
        $filename = substr($filename, 0, 50);
        $title = "Adição documento '$filename'";
        $description = "Adicionado '$filename' ($filesize) à lista de documentos do imóvel";

        $this->log($title, $description);
    }

    public function logDocumentDelete(string $filename, string $filesize)
    {
        // parse data
        $filename = substr($filename, 0, 50);
        $title = "Remoção documento '$filename'";
        $description = "Apagado '$filename' ($filesize) da lista de documentos do imóvel";

        $this->log($title, $description);
    }
}
