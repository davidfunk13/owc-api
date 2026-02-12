<?php

namespace Tests\Unit\Models;

use App\Models\CustomStatDefinition;
use App\Models\Game;
use App\Models\GameCustomStat;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameCustomStatTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_custom_stat_can_be_created_with_factory(): void
    {
        $stat = GameCustomStat::factory()->create();

        $this->assertDatabaseHas('game_custom_stats', ['id' => $stat->id]);
    }

    public function test_game_custom_stat_belongs_to_game(): void
    {
        $game = Game::factory()->create();
        $stat = GameCustomStat::factory()->create(['game_id' => $game->id]);

        $this->assertInstanceOf(Game::class, $stat->game);
        $this->assertEquals($game->id, $stat->game->id);
    }

    public function test_game_custom_stat_belongs_to_custom_stat_definition(): void
    {
        $def = CustomStatDefinition::factory()->create();
        $stat = GameCustomStat::factory()->create(['custom_stat_definition_id' => $def->id]);

        $this->assertInstanceOf(CustomStatDefinition::class, $stat->customStatDefinition);
        $this->assertEquals($def->id, $stat->customStatDefinition->id);
    }

    public function test_game_custom_stat_unique_per_game_and_definition(): void
    {
        $game = Game::factory()->create();
        $def = CustomStatDefinition::factory()->create();

        GameCustomStat::factory()->create(['game_id' => $game->id, 'custom_stat_definition_id' => $def->id]);

        $this->expectException(QueryException::class);
        GameCustomStat::factory()->create(['game_id' => $game->id, 'custom_stat_definition_id' => $def->id]);
    }

    public function test_game_custom_stat_cascades_on_game_delete(): void
    {
        $stat = GameCustomStat::factory()->create();
        $gameId = $stat->game_id;

        Game::find($gameId)->delete();

        $this->assertDatabaseMissing('game_custom_stats', ['id' => $stat->id]);
    }

    public function test_game_custom_stat_cascades_on_definition_delete(): void
    {
        $stat = GameCustomStat::factory()->create();
        $defId = $stat->custom_stat_definition_id;

        CustomStatDefinition::find($defId)->delete();

        $this->assertDatabaseMissing('game_custom_stats', ['id' => $stat->id]);
    }

    public function test_game_custom_stat_stores_decimal_values(): void
    {
        $stat = GameCustomStat::factory()->create(['numeric_value' => 42.75]);

        $stat->refresh();
        $this->assertEquals('42.75', $stat->numeric_value);
    }
}
