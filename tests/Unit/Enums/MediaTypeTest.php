<?php

namespace Tests\Unit\Enums;

use App\Enums\MediaType;
use PHPUnit\Framework\TestCase;

class MediaTypeTest extends TestCase
{
    public function test_media_type_has_expected_cases(): void
    {
        $this->assertCount(4, MediaType::cases());
        $this->assertEquals([
            'scoreboard_screenshot',
            'profile_screenshot',
            'general_screenshot',
            'video_clip',
        ], MediaType::values());
    }

    public function test_media_type_has_correct_labels(): void
    {
        $this->assertEquals('Scoreboard Screenshot', MediaType::ScoreboardScreenshot->label());
        $this->assertEquals('Profile Screenshot', MediaType::ProfileScreenshot->label());
        $this->assertEquals('General Screenshot', MediaType::GeneralScreenshot->label());
        $this->assertEquals('Video Clip', MediaType::VideoClip->label());
    }

    public function test_media_type_from_valid_string(): void
    {
        $this->assertEquals(MediaType::ScoreboardScreenshot, MediaType::from('scoreboard_screenshot'));
        $this->assertEquals(MediaType::VideoClip, MediaType::from('video_clip'));
    }

    public function test_media_type_try_from_invalid_string_returns_null(): void
    {
        $this->assertNull(MediaType::tryFrom('invalid'));
    }
}
