<?php

namespace App\Enums;

enum QueueType: string
{
    case CompetitiveRoleQueue = 'competitive_role_queue';
    case CompetitiveOpenQueue = 'competitive_open_queue';
    case QuickPlay = 'quick_play';
    case Arcade = 'arcade';
    case Custom = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::CompetitiveRoleQueue => 'Competitive (Role Queue)',
            self::CompetitiveOpenQueue => 'Competitive (Open Queue)',
            self::QuickPlay => 'Quick Play',
            self::Arcade => 'Arcade',
            self::Custom => 'Custom',
        };
    }

    public function teamSize(): int
    {
        return match ($this) {
            self::CompetitiveOpenQueue => 6,
            default => 5,
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
