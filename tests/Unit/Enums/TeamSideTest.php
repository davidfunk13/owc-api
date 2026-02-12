<?php

namespace Tests\Unit\Enums;

use App\Enums\TeamSide;
use PHPUnit\Framework\TestCase;

class TeamSideTest extends TestCase
{
    public function test_team_side_has_expected_cases(): void
    {
        $this->assertCount(2, TeamSide::cases());
        $this->assertEquals(['ally', 'enemy'], TeamSide::values());
    }

    public function test_team_side_has_correct_labels(): void
    {
        $this->assertEquals('Ally', TeamSide::Ally->label());
        $this->assertEquals('Enemy', TeamSide::Enemy->label());
    }

    public function test_team_side_from_valid_string(): void
    {
        $this->assertEquals(TeamSide::Ally, TeamSide::from('ally'));
        $this->assertEquals(TeamSide::Enemy, TeamSide::from('enemy'));
    }

    public function test_team_side_try_from_invalid_string_returns_null(): void
    {
        $this->assertNull(TeamSide::tryFrom('invalid'));
    }
}
