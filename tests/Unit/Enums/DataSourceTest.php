<?php

namespace Tests\Unit\Enums;

use App\Enums\DataSource;
use PHPUnit\Framework\TestCase;

class DataSourceTest extends TestCase
{
    public function test_data_source_has_expected_cases(): void
    {
        $this->assertCount(4, DataSource::cases());
        $this->assertEquals(['manual', 'scraped', 'imported', 'screenshot_ocr'], DataSource::values());
    }

    public function test_data_source_has_correct_labels(): void
    {
        $this->assertEquals('Manual', DataSource::Manual->label());
        $this->assertEquals('Scraped', DataSource::Scraped->label());
        $this->assertEquals('Imported', DataSource::Imported->label());
        $this->assertEquals('Screenshot (OCR)', DataSource::ScreenshotOcr->label());
    }

    public function test_data_source_from_valid_string(): void
    {
        $this->assertEquals(DataSource::Manual, DataSource::from('manual'));
        $this->assertEquals(DataSource::Scraped, DataSource::from('scraped'));
    }

    public function test_data_source_try_from_invalid_string_returns_null(): void
    {
        $this->assertNull(DataSource::tryFrom('invalid'));
    }
}
