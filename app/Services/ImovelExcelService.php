<?php
namespace App\Services;

use App\Enums\ImovelStatus;
use App\Models\Imovel;
use Str;
use Validator;

/**
 * Service with tools for validating an excel uploaded from the Imovel create form
 */
class ImovelExcelService
{
    private $rules;

    public function __construct()
    {
        $this->rules = Imovel::rules();
    }
    private function applyHeadersToRow(array $row): array
    {
        $cols = ['address_name', 'address_number', 'bairro', 'is_lado_praia', 'value', 'iptu', 'status'];

        // remove original keys
        $values = array_values($row);

        // cut array to key length
        $values = array_slice($row, 0, count($values));

        // put null in missing keys
        $values = array_pad($row, count($values), null); //

        // create associative array from the two arrays
        return array_combine(keys: $cols, values: $values);
    }

    private function validateDecimal(string $value = '')
    {
        if (empty($value)) {
            return -1;
        }

        // Remove all characters except numbers, dots, and commas
        $value = preg_replace('/[^0-9.,]/', '', $value);

        // Replace all commas with dots
        $value = str_replace(',', '.', $value);

        // Keep only the last dot as the decimal separator
        if (substr_count($value, '.') > 1) {
            $parts = explode('.', $value);
            $lastPart = array_pop($parts);
            $value = implode('', $parts) . '.' . $lastPart;
        }

        // Convert to float and then back to string
        $value = (string) floatval($value);

        return $value;
    }

    private function normalizeRow(array $row)
    {
        // remove forbidden characters from numero
        $row['address_number'] = preg_replace('[^0-9]', '', $row['address_number']);

        // turn is_lado_praia into boolean
        $lado = trim(strtolower($row['is_lado_praia']));
        $lado = $lado === 'praia';
        $row['is_lado_praia'] = $lado;

        // strip non numeric values from 'value' and 'iptu'
        $value = $row['value'];
        $value = $this->validateDecimal($value);
        $row['value'] = $value;

        $iptu = $row['iptu'];
        $iptu = $this->validateDecimal($iptu);
        $row['iptu'] = $iptu;

        // parse 'status' into then int
        $status = $row['status'];
        $status = trim(strtolower($status));
        $status = match ($status) {
            'livre' => ImovelStatus::LIVRE->value,
            'alugado' => ImovelStatus::ALUGADO->value,
            'vendido' => ImovelStatus::VENDIDO->value,
            default => null
        };
        $row['status'] = $status;

        // return row
        return $row;
    }

    public function parseRow(array $row)
    {
        // change header names and remove overflow
        $headered = $this->applyHeadersToRow($row);

        // parse row props from user friendly to database
        $normalized = $this->normalizeRow($headered);

        return $normalized;
    }

    public function getRowErrors(array $row): array|null
    {
        $validator = Validator::make($row, $this->rules);

        if ($validator->fails()) {
            return $validator->errors()->messages(); // Return errors instead of throwing
        }

        return null; // No errors
    }
}
