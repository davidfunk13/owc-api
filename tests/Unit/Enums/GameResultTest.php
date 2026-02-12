<?php

namespace Tests\Unit\Enums;

use App\Enums\GameResult;
use PHPUnit\Framework\TestCase;

class GameResultTest extends TestCase
{
    public function test_game_result_has_expected_cases(): void
    {
        $this->assertCount(3, GameResult::cases());
        $this->assertEquals(['win', 'loss', 'draw'], GameResult::values());
    }

    public function test_game_result_has_correct_labels(): void
    {
        $this->assertEquals('Win', GameResult::Win->label());
        $this->assertEquals('Loss', GameResult::Loss->label());
        $this->assertEquals('Draw', GameResult::Draw->label());
    }

    public function test_game_result_from_valid_string(): void
    {
        $this->assertEquals(GameResult::Win, GameResult::from('win'));
        $this->assertEquals(GameResult::Loss, GameResult::from('loss'));
        $this->assertEquals(GameResult::Draw, GameResult::from('draw'));
    }

    public function test_game_result_try_from_invalid_string_returns_null(): void
    {
        $this->assertNull(GameResult::tryFrom('invalid'));
    }
}
