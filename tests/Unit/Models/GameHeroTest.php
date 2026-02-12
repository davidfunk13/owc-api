<?php

namespace Tests\Unit\Models;

use App\Models\Game;
use App\Models\GameHero;
use App\Models\Hero;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameHeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_hero_can_be_created_with_factory(): void
    {
        $gameHero = GameHero::factory()->create();

        $this->assertDatabaseHas('game_heroes', ['id' => $gameHero->id]);
    }

    public function test_game_hero_has_fillable_attributes(): void
    {
        $game = Game::factory()->create();
        $hero = Hero::factory()->create();

        $gameHero = GameHero::create([
            'game_id' => $game->id,
            'hero_id' => $hero->id,
            'is_primary' => true,
            'playtime_seconds' => 600,
        ]);

        $this->assertEquals($game->id, $gameHero->game_id);
        $this->assertEquals($hero->id, $gameHero->hero_id);
        $this->assertTrue($gameHero->is_primary);
        $this->assertEquals(600, $gameHero->playtime_seconds);
    }

    public function test_game_hero_belongs_to_game(): void
    {
        $game = Game::factory()->create();
        $gameHero = GameHero::factory()->create(['game_id' => $game->id]);

        $this->assertInstanceOf(Game::class, $gameHero->game);
        $this->assertEquals($game->id, $gameHero->game->id);
    }

    public function test_game_hero_belongs_to_hero(): void
    {
        $hero = Hero::factory()->create();
        $gameHero = GameHero::factory()->create(['hero_id' => $hero->id]);

        $this->assertInstanceOf(Hero::class, $gameHero->hero);
        $this->assertEquals($hero->id, $gameHero->hero->id);
    }

    public function test_game_hero_unique_game_hero_combination(): void
    {
        $game = Game::factory()->create();
        $hero = Hero::factory()->create();

        GameHero::factory()->create(['game_id' => $game->id, 'hero_id' => $hero->id]);

        $this->expectException(QueryException::class);
        GameHero::factory()->create(['game_id' => $game->id, 'hero_id' => $hero->id]);
    }

    public function test_game_hero_is_primary_defaults_to_false(): void
    {
        $gameHero = GameHero::factory()->create();

        $this->assertFalse($gameHero->is_primary);
    }

    public function test_game_hero_cascades_on_game_delete(): void
    {
        $gameHero = GameHero::factory()->create();
        $gameId = $gameHero->game_id;

        Game::find($gameId)->delete();

        $this->assertDatabaseMissing('game_heroes', ['id' => $gameHero->id]);
    }

    public function test_game_hero_cascades_on_hero_delete(): void
    {
        $gameHero = GameHero::factory()->create();
        $heroId = $gameHero->hero_id;

        Hero::find($heroId)->delete();

        $this->assertDatabaseMissing('game_heroes', ['id' => $gameHero->id]);
    }

    public function test_multiple_heroes_per_game(): void
    {
        $game = Game::factory()->create();
        $heroes = Hero::factory()->count(3)->create();

        foreach ($heroes as $hero) {
            GameHero::factory()->create(['game_id' => $game->id, 'hero_id' => $hero->id]);
        }

        $this->assertCount(3, $game->gameHeroes);
    }
}
