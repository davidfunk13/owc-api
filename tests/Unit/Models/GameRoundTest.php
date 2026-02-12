<?php

namespace Tests\Unit\Models;

use App\Enums\GameResult;
use App\Models\Game;
use App\Models\GameRound;
use App\Models\MapSubmap;
use App\Models\RoundHero;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameRoundTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_round_can_be_created_with_factory(): void
    {
        $round = GameRound::factory()->create();

        $this->assertDatabaseHas('game_rounds', ['id' => $round->id]);
    }

    public function test_game_round_has_fillable_attributes(): void
    {
        $game = Game::factory()->create();
        $round = GameRound::create([
            'game_id' => $game->id,
            'round_number' => 1,
            'result' => 'win',
            'side' => 'attack',
            'score_team' => 2,
            'score_enemy' => 1,
        ]);

        $this->assertEquals($game->id, $round->game_id);
        $this->assertEquals(1, $round->round_number);
        $this->assertEquals('attack', $round->side);
    }

    public function test_game_round_belongs_to_game(): void
    {
        $game = Game::factory()->create();
        $round = GameRound::factory()->create(['game_id' => $game->id]);

        $this->assertInstanceOf(Game::class, $round->game);
        $this->assertEquals($game->id, $round->game->id);
    }

    public function test_game_round_belongs_to_map_submap(): void
    {
        $submap = MapSubmap::factory()->create();
        $round = GameRound::factory()->create(['map_submap_id' => $submap->id]);

        $this->assertInstanceOf(MapSubmap::class, $round->mapSubmap);

        $roundNoSubmap = GameRound::factory()->create(['map_submap_id' => null]);
        $this->assertNull($roundNoSubmap->mapSubmap);
    }

    public function test_game_round_casts_result_to_enum(): void
    {
        $round = GameRound::factory()->create(['result' => 'win']);

        $this->assertInstanceOf(GameResult::class, $round->result);
        $this->assertEquals(GameResult::Win, $round->result);
    }

    public function test_game_round_unique_game_round_number(): void
    {
        $game = Game::factory()->create();
        GameRound::factory()->create(['game_id' => $game->id, 'round_number' => 1]);

        $this->expectException(QueryException::class);
        GameRound::factory()->create(['game_id' => $game->id, 'round_number' => 1]);
    }

    public function test_game_round_cascades_on_game_delete(): void
    {
        $round = GameRound::factory()->create();
        $gameId = $round->game_id;

        Game::find($gameId)->delete();

        $this->assertDatabaseMissing('game_rounds', ['id' => $round->id]);
    }

    public function test_game_round_has_many_round_heroes(): void
    {
        $round = GameRound::factory()->create();
        RoundHero::factory()->count(2)->create(['game_round_id' => $round->id]);

        $this->assertCount(2, $round->roundHeroes);
    }

    public function test_game_round_side_is_nullable(): void
    {
        $round = GameRound::factory()->create(['side' => null]);

        $this->assertNull($round->side);
        $this->assertDatabaseHas('game_rounds', ['id' => $round->id]);
    }

    public function test_game_round_distance_meters_is_nullable(): void
    {
        $round = GameRound::factory()->create(['distance_meters' => null]);
        $this->assertNull($round->distance_meters);

        $round2 = GameRound::factory()->create(['distance_meters' => 85.50]);
        $this->assertEquals('85.50', $round2->distance_meters);
    }

    public function test_game_round_checkpoints_reached_is_nullable(): void
    {
        $round = GameRound::factory()->create(['checkpoints_reached' => null]);
        $this->assertNull($round->checkpoints_reached);

        $round2 = GameRound::factory()->create(['checkpoints_reached' => 2]);
        $this->assertEquals(2, $round2->checkpoints_reached);
    }

    public function test_game_round_is_overtime_defaults_to_false(): void
    {
        $game = Game::factory()->create();
        $round = GameRound::create([
            'game_id' => $game->id,
            'round_number' => 1,
        ]);

        $this->assertFalse($round->is_overtime);
    }

    public function test_game_round_tracks_escort_attack_round(): void
    {
        $round = GameRound::factory()->create([
            'side' => 'attack',
            'result' => 'win',
            'distance_meters' => 92.75,
            'checkpoints_reached' => 3,
            'score_team' => 3,
            'score_enemy' => 0,
            'is_overtime' => false,
        ]);

        $this->assertEquals('attack', $round->side);
        $this->assertEquals(GameResult::Win, $round->result);
        $this->assertEquals('92.75', $round->distance_meters);
        $this->assertEquals(3, $round->checkpoints_reached);
        $this->assertFalse($round->is_overtime);
    }

    public function test_game_round_tracks_push_distance(): void
    {
        $round = GameRound::factory()->create([
            'round_number' => 1,
            'side' => null,
            'result' => 'win',
            'distance_meters' => 108.30,
            'checkpoints_reached' => null,
            'is_overtime' => false,
        ]);

        $this->assertNull($round->side);
        $this->assertEquals('108.30', $round->distance_meters);
        $this->assertNull($round->checkpoints_reached);
    }

    public function test_game_round_tracks_overtime_round(): void
    {
        $round = GameRound::factory()->create([
            'round_number' => 3,
            'side' => 'attack',
            'is_overtime' => true,
            'distance_meters' => 25.00,
            'checkpoints_reached' => 1,
        ]);

        $this->assertTrue($round->is_overtime);
        $this->assertEquals(3, $round->round_number);
        $this->assertEquals('attack', $round->side);
    }
}
