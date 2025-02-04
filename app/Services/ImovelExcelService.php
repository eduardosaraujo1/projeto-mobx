<?php
namespace App\Services;

use App\Enums\ImovelLocation;
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

    private function validateCurrency(string $value = '')
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

    /**
     * Search a word in the string and return if it is valid
     * @param ?string $str
     * @param array{string:mixed} $match
     * @return ?string
     */
    private function fuzzyMatch(?string $str, array $match): mixed
    {
        // handle null or empty
        if (empty($str) || empty($match)) {
            return null;
        }

        // remove casing from search string
        $str = strtolower(trim($str));

        // remove irrelevent characters
        $keys = array_keys($match);
        $pattern = strtolower("[^" . implode($keys) . "]");
        $str = preg_replace($pattern, '', $str);

        // match string to key
        foreach ($match as $key => $value) {
            if (str_contains($str, $key)) {
                return $value;
            }
        }

        return null;
    }

    private function applyHeadersToRow(array $row): array
    {
        $cols = ['address_name', 'address_number', 'bairro', 'location_reference', 'value', 'iptu', 'status'];

        // remove original keys
        $values = array_values($row);

        // cut array to key length
        $values = array_slice($row, 0, count($values));

        // put null in missing keys
        $values = array_pad($row, count($values), null);

        // create associative array from the two arrays
        return array_combine(keys: $cols, values: $values);
    }

    private function normalizeRow(array $row)
    {
        // strip max length of all to 255
        $row = array_map(
            callback: fn($cell): string => substr(
                string: (string) $cell,
                offset: 0,
                length: 255
            ),
            array: $row
        );

        // remove forbidden characters from address number
        $row['address_number'] = preg_replace('[^0-9]', '', $row['address_number']);

        // turn location_reference into enum
        $location_reference = $row['location_reference'];
        $location_reference = $this->fuzzyMatch($location_reference, [
            'praia' => ImovelLocation::PRAIA,
            'morro' => ImovelLocation::MORRO
        ]);
        $row['location_reference'] = $location_reference;

        // strip non numeric values from 'value' and 'iptu'
        $value = $row['value'];
        $value = $this->validateCurrency($value);
        $row['value'] = $value;

        $iptu = $row['iptu'];
        $iptu = $this->validateCurrency($iptu);
        $row['iptu'] = $iptu;

        // parse 'status' into int
        $status = $row['status'];
        $status = $this->fuzzyMatch($status, [
            'livre' => ImovelStatus::LIVRE,
            'alugado' => ImovelStatus::ALUGADO,
            'vendido' => ImovelStatus::VENDIDO,
        ]);
        $row['status'] = $status;

        // return row
        return $row;
    }

    public function parseExcelRow(array $row)
    {
        // change header names and remove overflow
        $headered = $this->applyHeadersToRow($row);

        // parse row props from user friendly to database
        $normalized = $this->normalizeRow($headered);

        return $normalized;
    }
}
