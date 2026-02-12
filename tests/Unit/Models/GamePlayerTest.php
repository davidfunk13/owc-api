<?php

namespace Tests\Unit\Models;

use App\Enums\Role;
use App\Enums\TeamSide;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GamePlayerHero;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamePlayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_player_can_be_created_with_factory(): void
    {
        $player = GamePlayer::factory()->create();

        $this->assertDatabaseHas('game_players', ['id' => $player->id]);
    }

    public function test_game_player_has_fillable_attributes(): void
    {
        $game = Game::factory()->create();
        $player = GamePlayer::create([
            'game_id' => $game->id,
            'team_side' => 'ally',
            'role' => 'support',
            'player_name' => 'TestPlayer#1234',
            'slot_number' => 2,
        ]);

        $this->assertEquals($game->id, $player->game_id);
        $this->assertEquals('TestPlayer#1234', $player->player_name);
        $this->assertEquals(2, $player->slot_number);
    }

    public function test_game_player_belongs_to_game(): void
    {
        $game = Game::factory()->create();
        $player = GamePlayer::factory()->create(['game_id' => $game->id]);

        $this->assertInstanceOf(Game::class, $player->game);
        $this->assertEquals($game->id, $player->game->id);
    }

    public function test_game_player_casts_team_side_to_enum(): void
    {
        $player = GamePlayer::factory()->create(['team_side' => 'ally']);

        $this->assertInstanceOf(TeamSide::class, $player->team_side);
        $this->assertEquals(TeamSide::Ally, $player->team_side);
    }

    public function test_game_player_casts_role_to_enum(): void
    {
        $player = GamePlayer::factory()->create(['role' => 'support']);

        $this->assertInstanceOf(Role::class, $player->role);
        $this->assertEquals(Role::Support, $player->role);
    }

    public function test_game_player_has_many_game_player_heroes(): void
    {
        $player = GamePlayer::factory()->create();
        GamePlayerHero::factory()->count(2)->create(['game_player_id' => $player->id]);

        $this->assertCount(2, $player->gamePlayerHeroes);
    }

    public function test_game_player_cascades_on_game_delete(): void
    {
        $player = GamePlayer::factory()->create();
        $gameId = $player->game_id;

        Game::find($gameId)->delete();

        $this->assertDatabaseMissing('game_players', ['id' => $player->id]);
    }

    public function test_game_player_player_name_is_nullable(): void
    {
        $player = GamePlayer::factory()->create(['player_name' => null]);

        $this->assertNull($player->player_name);
        $this->assertDatabaseHas('game_players', ['id' => $player->id]);
    }
}
