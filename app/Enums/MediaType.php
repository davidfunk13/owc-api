<?php

namespace App\Enums;

enum MediaType: string
{
    case ScoreboardScreenshot = 'scoreboard_screenshot';
    case ProfileScreenshot = 'profile_screenshot';
    case GeneralScreenshot = 'general_screenshot';
    case VideoClip = 'video_clip';

    public function label(): string
    {
        return match ($this) {
            self::ScoreboardScreenshot => 'Scoreboard Screenshot',
            self::ProfileScreenshot => 'Profile Screenshot',
            self::GeneralScreenshot => 'General Screenshot',
            self::VideoClip => 'Video Clip',
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
