<?php

namespace Tests\Unit\Enums;

use App\Enums\QueueType;
use PHPUnit\Framework\TestCase;

class QueueTypeTest extends TestCase
{
    public function test_queue_type_has_expected_cases(): void
    {
        $this->assertCount(5, QueueType::cases());
        $this->assertEquals([
            'competitive_role_queue',
            'competitive_open_queue',
            'quick_play',
            'arcade',
            'custom',
        ], QueueType::values());
    }

    public function test_queue_type_has_correct_labels(): void
    {
        $this->assertEquals('Competitive (Role Queue)', QueueType::CompetitiveRoleQueue->label());
        $this->assertEquals('Competitive (Open Queue)', QueueType::CompetitiveOpenQueue->label());
        $this->assertEquals('Quick Play', QueueType::QuickPlay->label());
        $this->assertEquals('Arcade', QueueType::Arcade->label());
        $this->assertEquals('Custom', QueueType::Custom->label());
    }

    public function test_queue_type_from_valid_string(): void
    {
        $this->assertEquals(QueueType::CompetitiveRoleQueue, QueueType::from('competitive_role_queue'));
        $this->assertEquals(QueueType::QuickPlay, QueueType::from('quick_play'));
    }

    public function test_queue_type_try_from_invalid_string_returns_null(): void
    {
        $this->assertNull(QueueType::tryFrom('invalid'));
    }

    public function test_queue_type_team_size(): void
    {
        $this->assertEquals(5, QueueType::CompetitiveRoleQueue->teamSize());
        $this->assertEquals(6, QueueType::CompetitiveOpenQueue->teamSize());
        $this->assertEquals(5, QueueType::QuickPlay->teamSize());
        $this->assertEquals(5, QueueType::Arcade->teamSize());
        $this->assertEquals(5, QueueType::Custom->teamSize());
    }
}
