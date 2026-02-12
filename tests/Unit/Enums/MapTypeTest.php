<?php

namespace Tests\Unit\Enums;

use App\Enums\MapType;
use PHPUnit\Framework\TestCase;

class MapTypeTest extends TestCase
{
    public function test_map_type_has_expected_cases(): void
    {
        $this->assertCount(6, MapType::cases());
        $this->assertEquals(['control', 'escort', 'hybrid', 'push', 'flashpoint', 'clash'], MapType::values());
    }

    public function test_map_type_has_correct_backing_values(): void
    {
        $this->assertEquals('control', MapType::Control->value);
        $this->assertEquals('escort', MapType::Escort->value);
        $this->assertEquals('hybrid', MapType::Hybrid->value);
        $this->assertEquals('push', MapType::Push->value);
        $this->assertEquals('flashpoint', MapType::Flashpoint->value);
        $this->assertEquals('clash', MapType::Clash->value);
    }

    public function test_map_type_has_correct_labels(): void
    {
        $this->assertEquals('Control', MapType::Control->label());
        $this->assertEquals('Escort', MapType::Escort->label());
        $this->assertEquals('Hybrid', MapType::Hybrid->label());
        $this->assertEquals('Push', MapType::Push->label());
        $this->assertEquals('Flashpoint', MapType::Flashpoint->label());
        $this->assertEquals('Clash', MapType::Clash->label());
    }

    public function test_map_type_from_valid_string(): void
    {
        $this->assertEquals(MapType::Control, MapType::from('control'));
        $this->assertEquals(MapType::Clash, MapType::from('clash'));
    }

    public function test_map_type_try_from_invalid_string_returns_null(): void
    {
        $this->assertNull(MapType::tryFrom('invalid'));
    }
}
