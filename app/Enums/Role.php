<?php

namespace App\Enums;

enum Role: string
{
    case Tank = 'tank';
    case Damage = 'damage';
    case Support = 'support';

    public function label(): string
    {
        return match ($this) {
            self::Tank => 'Tank',
            self::Damage => 'Damage',
            self::Support => 'Support',
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
