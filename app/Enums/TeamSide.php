<?php

namespace App\Enums;

enum TeamSide: string
{
    case Ally = 'ally';
    case Enemy = 'enemy';

    public function label(): string
    {
        return match ($this) {
            self::Ally => 'Ally',
            self::Enemy => 'Enemy',
        };
    }

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
