<?php

namespace Tests\Unit\Models;

use App\Enums\DataSource;
use App\Enums\GameResult;
use App\Enums\QueueType;
use App\Enums\Role;
use App\Models\Game;
use App\Models\GameCustomStat;
use App\Models\GameGroupMember;
use App\Models\GameHero;
use App\Models\GamePlayer;
use App\Models\GameRound;
use App\Models\Hero;
use App\Models\HeroSrSnapshot;
use App\Models\Map;
use App\Models\MediaAttachment;
use App\Models\PlaySession;
use App\Models\RankSnapshot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_can_be_created_with_factory(): void
    {
        $game = Game::factory()->create();

        $this->assertDatabaseHas('games', ['id' => $game->id]);
    }

    public function test_game_has_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $game = Game::create([
            'user_id' => $user->id,
            'queue_type' => 'competitive_role_queue',
            'result' => 'win',
            'role_played' => 'support',
            'played_at' => '2026-02-10 19:00:00',
            'data_source' => 'manual',
        ]);

        $this->assertEquals($user->id, $game->user_id);
        $this->assertEquals(QueueType::CompetitiveRoleQueue, $game->queue_type);
        $this->assertEquals(GameResult::Win, $game->result);
    }

    public function test_game_casts_queue_type_to_enum(): void
    {
        $game = Game::factory()->create(['queue_type' => 'competitive_role_queue']);

        $this->assertInstanceOf(QueueType::class, $game->queue_type);
        $this->assertEquals(QueueType::CompetitiveRoleQueue, $game->queue_type);
    }

    public function test_game_casts_result_to_enum(): void
    {
        $game = Game::factory()->create(['result' => 'win']);

        $this->assertInstanceOf(GameResult::class, $game->result);
        $this->assertEquals(GameResult::Win, $game->result);
    }

    public function test_game_casts_role_played_to_enum(): void
    {
        $game = Game::factory()->create(['role_played' => 'support']);

        $this->assertInstanceOf(Role::class, $game->role_played);
        $this->assertEquals(Role::Support, $game->role_played);
    }

    public function test_game_casts_data_source_to_enum(): void
    {
        $game = Game::factory()->create(['data_source' => 'manual']);

        $this->assertInstanceOf(DataSource::class, $game->data_source);
        $this->assertEquals(DataSource::Manual, $game->data_source);
    }

    public function test_game_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $game->user);
        $this->assertEquals($user->id, $game->user->id);
    }

    public function test_game_belongs_to_play_session(): void
    {
        $session = PlaySession::factory()->create();
        $game = Game::factory()->create(['user_id' => $session->user_id, 'play_session_id' => $session->id]);

        $this->assertInstanceOf(PlaySession::class, $game->playSession);

        $gameNoSession = Game::factory()->create(['play_session_id' => null]);
        $this->assertNull($gameNoSession->playSession);
    }

    public function test_game_belongs_to_map(): void
    {
        $map = Map::factory()->create();
        $game = Game::factory()->create(['map_id' => $map->id]);

        $this->assertInstanceOf(Map::class, $game->map);

        $gameNoMap = Game::factory()->create(['map_id' => null]);
        $this->assertNull($gameNoMap->map);
    }

    public function test_game_has_many_game_heroes(): void
    {
        $game = Game::factory()->create();
        GameHero::factory()->count(2)->create(['game_id' => $game->id]);

        $this->assertCount(2, $game->gameHeroes);
    }

    public function test_game_has_many_game_rounds(): void
    {
        $game = Game::factory()->create();
        GameRound::factory()->create(['game_id' => $game->id, 'round_number' => 1]);
        GameRound::factory()->create(['game_id' => $game->id, 'round_number' => 2]);

        $this->assertCount(2, $game->gameRounds);
    }

    public function test_game_has_many_game_players(): void
    {
        $game = Game::factory()->create();
        GamePlayer::factory()->count(3)->create(['game_id' => $game->id]);

        $this->assertCount(3, $game->gamePlayers);
    }

    public function test_game_has_many_game_custom_stats(): void
    {
        $game = Game::factory()->create();
        GameCustomStat::factory()->create(['game_id' => $game->id]);

        $this->assertCount(1, $game->gameCustomStats);
    }

    public function test_game_has_many_game_group_members(): void
    {
        $game = Game::factory()->create();
        GameGroupMember::factory()->create(['game_id' => $game->id]);

        $this->assertCount(1, $game->gameGroupMembers);
    }

    public function test_game_cascades_on_user_delete(): void
    {
        $game = Game::factory()->create();
        $userId = $game->user_id;

        User::find($userId)->delete();

        $this->assertDatabaseMissing('games', ['id' => $game->id]);
    }

    public function test_game_sets_null_on_play_session_delete(): void
    {
        $session = PlaySession::factory()->create();
        $game = Game::factory()->create(['user_id' => $session->user_id, 'play_session_id' => $session->id]);

        $session->delete();

        $game->refresh();
        $this->assertNull($game->play_session_id);
        $this->assertDatabaseHas('games', ['id' => $game->id]);
    }

    public function test_game_played_at_is_cast_to_datetime(): void
    {
        $game = Game::factory()->create(['played_at' => '2026-02-10 19:00:00']);

        $this->assertInstanceOf(Carbon::class, $game->played_at);
    }

    public function test_game_is_placement_defaults_to_false(): void
    {
        $user = User::factory()->create();
        $game = Game::create([
            'user_id' => $user->id,
            'queue_type' => 'competitive_role_queue',
            'result' => 'win',
            'played_at' => now(),
        ]);

        $this->assertFalse($game->is_placement);
    }

    public function test_game_data_source_defaults_to_manual(): void
    {
        $user = User::factory()->create();
        $game = Game::create([
            'user_id' => $user->id,
            'queue_type' => 'competitive_role_queue',
            'result' => 'win',
            'played_at' => now(),
        ]);

        $this->assertEquals(DataSource::Manual, $game->data_source);
    }

    public function test_game_heroes_belongs_to_many(): void
    {
        $game = Game::factory()->create();
        $hero = Hero::factory()->create();
        GameHero::factory()->create(['game_id' => $game->id, 'hero_id' => $hero->id]);

        $this->assertCount(1, $game->heroes);
        $this->assertEquals($hero->id, $game->heroes->first()->id);
    }

    public function test_game_has_many_rank_snapshots(): void
    {
        $game = Game::factory()->create();
        RankSnapshot::factory()->create(['user_id' => $game->user_id, 'game_id' => $game->id]);

        $this->assertCount(1, $game->rankSnapshots);
        $this->assertInstanceOf(RankSnapshot::class, $game->rankSnapshots->first());
    }

    public function test_game_has_many_hero_sr_snapshots(): void
    {
        $game = Game::factory()->create();
        HeroSrSnapshot::factory()->create(['user_id' => $game->user_id, 'game_id' => $game->id]);

        $this->assertCount(1, $game->heroSrSnapshots);
        $this->assertInstanceOf(HeroSrSnapshot::class, $game->heroSrSnapshots->first());
    }

    public function test_game_has_many_media_attachments(): void
    {
        $game = Game::factory()->create();
        MediaAttachment::factory()->create([
            'user_id' => $game->user_id,
            'attachable_id' => $game->id,
            'attachable_type' => Game::class,
        ]);

        $this->assertCount(1, $game->mediaAttachments);
        $this->assertInstanceOf(MediaAttachment::class, $game->mediaAttachments->first());
    }
}
