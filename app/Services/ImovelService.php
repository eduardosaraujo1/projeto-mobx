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
class ImovelService
{
    private $rules;

    public function __construct()
    {
        $this->rules = Imovel::rules();
    }

    /**
     * Takes a string and treats it so that it is a valid currency representation
     * Note that it does not validate against the database, meaning it will allow a number that surpasses the decimal constraints. This is a known issue that will be fixed when I figure out how Laravel deals with those constraints
     * @param string $value
     * @return int|string
     */
    private function validateCurrency(string $value = '')
    {
        if (empty($value)) {
            return null;
        }

        // Remove all characters except numbers, dots, and commas
        $value = preg_replace('/[^0-9.,]/', '', $value);

        // Replace all commas with dots
        $value = str_replace(',', '.', $value);

        // Keep only the last dot as the decimal separator
        if (substr_count($value, '.') > 1) {
            $parts = explode('.', $value);
            $lastPart = array_pop($parts); // ampersand means "pass-by-reference"
            $value = implode('', $parts) . '.' . $lastPart;
        }

        // Convert to float and then back to string
        $value = (string) floatval($value);

        return $value;
    }

    private function unsetNullEntries(array $row)
    {
        foreach ($row as $key => $value) {
            if (empty($value)) {
                unset($row[$key]);
            }
        }
        return $row;
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
        $pattern = strtolower("/[^" . implode($keys) . "]/");
        $str = preg_replace($pattern, '', $str);

        // match string to key
        foreach ($match as $key => $value) {
            if (str_contains($str, $key)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Truncate all string values in the row to a maximum length.
     */
    private function truncateRowValues(array $row, int $maxLength): array
    {
        return array_map(fn($cell): string => substr((string) $cell, 0, $maxLength), $row);
    }

    /**
     * Extract only numeric characters from a string.
     */
    private function extractNumbers(string $value): string
    {
        return preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Modify the passed array to make the keys of the entries match their database counterparts
     * @param array $row
     * @return array
     */
    private function applyExcelHeaders(array $row): array
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

    private function normalizeExcelRow(array $row)
    {
        // Limit all string values to 255 characters
        $row = $this->truncateRowValues($row, 255);

        // Normalize 'address_number' by removing non-numeric characters
        $row['address_number'] = $this->extractNumbers($row['address_number']);

        // Convert 'location_reference' into enum using fuzzy matching
        $row['location_reference'] = $this->fuzzyMatch($row['location_reference'], [
            'praia' => ImovelLocation::PRAIA,
            'morro' => ImovelLocation::MORRO
        ]);

        // Validate and normalize currency values
        $row['value'] = $this->validateCurrency($row['value']);
        $row['iptu'] = $this->validateCurrency($row['iptu']);

        // Convert 'status' into enum using fuzzy matching
        $row['status'] = $this->fuzzyMatch($row['status'], [
            'livre' => ImovelStatus::LIVRE,
            'alugado' => ImovelStatus::ALUGADO,
            'vendido' => ImovelStatus::VENDIDO,
        ]);

        return $row;
    }

    public function parseExcelRow(array $row)
    {
        // change header names and remove overflow
        $with_headers = $this->applyExcelHeaders($row);

        // parse row props from user friendly to database
        $normalized = $this->normalizeExcelRow($with_headers);

        // unset null instances
        $parsedRow = $this->unsetNullEntries($normalized);

        return $parsedRow;
    }
}
