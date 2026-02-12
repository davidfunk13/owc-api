<?php

namespace App\Enums;

enum MapType: string
{
    case Control = 'control';
    case Escort = 'escort';
    case Hybrid = 'hybrid';
    case Push = 'push';
    case Flashpoint = 'flashpoint';
    case Clash = 'clash';

    public function label(): string
    {
        return match ($this) {
            self::Control => 'Control',
            self::Escort => 'Escort',
            self::Hybrid => 'Hybrid',
            self::Push => 'Push',
            self::Flashpoint => 'Flashpoint',
            self::Clash => 'Clash',
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
