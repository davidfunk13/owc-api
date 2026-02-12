<?php

namespace Tests\Unit\Models;

use App\Models\GamePlayer;
use App\Models\GamePlayerHero;
use App\Models\Hero;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamePlayerHeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_player_hero_can_be_created_with_factory(): void
    {
        $gph = GamePlayerHero::factory()->create();

        $this->assertDatabaseHas('game_player_heroes', ['id' => $gph->id]);
    }

    public function test_game_player_hero_belongs_to_game_player(): void
    {
        $player = GamePlayer::factory()->create();
        $gph = GamePlayerHero::factory()->create(['game_player_id' => $player->id]);

        $this->assertInstanceOf(GamePlayer::class, $gph->gamePlayer);
        $this->assertEquals($player->id, $gph->gamePlayer->id);
    }

    public function test_game_player_hero_belongs_to_hero(): void
    {
        $hero = Hero::factory()->create();
        $gph = GamePlayerHero::factory()->create(['hero_id' => $hero->id]);

        $this->assertInstanceOf(Hero::class, $gph->hero);
        $this->assertEquals($hero->id, $gph->hero->id);
    }

    public function test_game_player_hero_unique_per_player(): void
    {
        $player = GamePlayer::factory()->create();
        $hero = Hero::factory()->create();

        GamePlayerHero::factory()->create(['game_player_id' => $player->id, 'hero_id' => $hero->id]);

        $this->expectException(QueryException::class);
        GamePlayerHero::factory()->create(['game_player_id' => $player->id, 'hero_id' => $hero->id]);
    }

    public function test_game_player_hero_cascades_on_game_player_delete(): void
    {
        $gph = GamePlayerHero::factory()->create();
        $playerId = $gph->game_player_id;

        GamePlayer::find($playerId)->delete();

        $this->assertDatabaseMissing('game_player_heroes', ['id' => $gph->id]);
    }

    public function test_game_player_hero_is_primary_defaults_to_true(): void
    {
        $gph = GamePlayerHero::factory()->create();

        $this->assertTrue($gph->is_primary);
    }
}
