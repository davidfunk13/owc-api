<?php

namespace App\Enums;

enum GameResult: string
{
    case Win = 'win';
    case Loss = 'loss';
    case Draw = 'draw';

    public function label(): string
    {
        return match ($this) {
            self::Win => 'Win',
            self::Loss => 'Loss',
            self::Draw => 'Draw',
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
