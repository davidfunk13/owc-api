<?php

namespace Tests\Unit\Enums;

use App\Enums\RankTier;
use PHPUnit\Framework\TestCase;

class RankTierTest extends TestCase
{
    public function test_rank_tier_has_expected_cases(): void
    {
        $this->assertCount(8, RankTier::cases());
        $this->assertEquals([
            'bronze', 'silver', 'gold', 'platinum',
            'diamond', 'master', 'grandmaster', 'champion',
        ], RankTier::values());
    }

    public function test_rank_tier_has_correct_labels(): void
    {
        $this->assertEquals('Bronze', RankTier::Bronze->label());
        $this->assertEquals('Silver', RankTier::Silver->label());
        $this->assertEquals('Gold', RankTier::Gold->label());
        $this->assertEquals('Platinum', RankTier::Platinum->label());
        $this->assertEquals('Diamond', RankTier::Diamond->label());
        $this->assertEquals('Master', RankTier::Master->label());
        $this->assertEquals('Grandmaster', RankTier::Grandmaster->label());
        $this->assertEquals('Champion', RankTier::Champion->label());
    }

    public function test_rank_tier_rank_value_bronze_5(): void
    {
        $this->assertEquals(105, RankTier::Bronze->rankValue(5));
    }

    public function test_rank_tier_rank_value_bronze_1(): void
    {
        $this->assertEquals(125, RankTier::Bronze->rankValue(1));
    }

    public function test_rank_tier_rank_value_gold_1(): void
    {
        $this->assertEquals(325, RankTier::Gold->rankValue(1));
    }

    public function test_rank_tier_rank_value_grandmaster_1(): void
    {
        $this->assertEquals(725, RankTier::Grandmaster->rankValue(1));
    }

    public function test_rank_tier_rank_value_champion_null(): void
    {
        $this->assertEquals(800, RankTier::Champion->rankValue(null));
    }

    public function test_rank_tier_from_valid_string(): void
    {
        $this->assertEquals(RankTier::Bronze, RankTier::from('bronze'));
        $this->assertEquals(RankTier::Champion, RankTier::from('champion'));
    }

    public function test_rank_tier_try_from_invalid_string_returns_null(): void
    {
        $this->assertNull(RankTier::tryFrom('invalid'));
    }
}
