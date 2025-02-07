<?php

namespace App\Enums;

use Str;

enum UserRole: int
{
    case COLABORADOR = 0;
    case GERENTE = 1;

    public static function randomId()
    {
        return array_rand(self::cases());
    }

    public static function fromName(string $name): ?UserRole
    {
        return match (strtolower($name)) {
            'colaborador' => self::COLABORADOR,
            'gerente' => self::GERENTE,
            default => null
        };
    }

    public function getName()
    {
        return Str::title($this->name);
    }
}
