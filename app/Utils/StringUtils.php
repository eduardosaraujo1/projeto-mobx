<?php

namespace App\Utils;

class StringUtils
{
    public static function formatCurrencyField(string $value): string
    {
        if (! isset($value) || (float) $value < 0) {
            return '';
        }

        return number_format((float) $value, 2);
    }

    public static function cpfFormat(string $cpf)
    {
        $CPF_LENGTH = 11;
        $cnpj_cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cnpj_cpf) === $CPF_LENGTH) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cnpj_cpf);
        }

        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj_cpf);
    }
}
