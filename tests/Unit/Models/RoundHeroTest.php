<?php

namespace Tests\Unit\Models;

use App\Models\GameRound;
use App\Models\Hero;
use App\Models\RoundHero;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoundHeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_round_hero_can_be_created_with_factory(): void
    {
        $roundHero = RoundHero::factory()->create();

        $this->assertDatabaseHas('round_heroes', ['id' => $roundHero->id]);
    }

    public function test_round_hero_belongs_to_game_round(): void
    {
        $round = GameRound::factory()->create();
        $roundHero = RoundHero::factory()->create(['game_round_id' => $round->id]);

        $this->assertInstanceOf(GameRound::class, $roundHero->gameRound);
        $this->assertEquals($round->id, $roundHero->gameRound->id);
    }

    public function test_round_hero_belongs_to_hero(): void
    {
        $hero = Hero::factory()->create();
        $roundHero = RoundHero::factory()->create(['hero_id' => $hero->id]);

        $this->assertInstanceOf(Hero::class, $roundHero->hero);
        $this->assertEquals($hero->id, $roundHero->hero->id);
    }

    public function test_round_hero_unique_per_round(): void
    {
        $round = GameRound::factory()->create();
        $hero = Hero::factory()->create();

        RoundHero::factory()->create(['game_round_id' => $round->id, 'hero_id' => $hero->id]);

        $this->expectException(QueryException::class);
        RoundHero::factory()->create(['game_round_id' => $round->id, 'hero_id' => $hero->id]);
    }

    public function test_round_hero_cascades_on_game_round_delete(): void
    {
        $roundHero = RoundHero::factory()->create();
        $roundId = $roundHero->game_round_id;

        GameRound::find($roundId)->delete();

        $this->assertDatabaseMissing('round_heroes', ['id' => $roundHero->id]);
    }
}
