<?php

namespace App\Enums;

enum DataSource: string
{
    case Manual = 'manual';
    case Scraped = 'scraped';
    case Imported = 'imported';
    case ScreenshotOcr = 'screenshot_ocr';

    public function label(): string
    {
        return match ($this) {
            self::Manual => 'Manual',
            self::Scraped => 'Scraped',
            self::Imported => 'Imported',
            self::ScreenshotOcr => 'Screenshot (OCR)',
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
