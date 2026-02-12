<?php

namespace Tests\Unit\Models;

use App\Enums\RankTier;
use App\Models\Game;
use App\Models\RankSnapshot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RankSnapshotTest extends TestCase
{
    use RefreshDatabase;

    public function test_rank_snapshot_can_be_created_with_factory(): void
    {
        $snapshot = RankSnapshot::factory()->create();

        $this->assertDatabaseHas('rank_snapshots', ['id' => $snapshot->id]);
    }

    public function test_rank_snapshot_has_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $snapshot = RankSnapshot::create([
            'user_id' => $user->id,
            'role' => 'support',
            'tier' => 'gold',
            'division' => 3,
            'snapshot_type' => 'post_game',
            'recorded_at' => now(),
        ]);

        $this->assertEquals($user->id, $snapshot->user_id);
        $this->assertEquals('support', $snapshot->role);
        $this->assertEquals(RankTier::Gold, $snapshot->tier);
        $this->assertEquals(3, $snapshot->division);
    }

    public function test_rank_snapshot_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $snapshot = RankSnapshot::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $snapshot->user);
        $this->assertEquals($user->id, $snapshot->user->id);
    }

    public function test_rank_snapshot_belongs_to_game(): void
    {
        $game = Game::factory()->create();
        $snapshot = RankSnapshot::factory()->create(['user_id' => $game->user_id, 'game_id' => $game->id]);

        $this->assertInstanceOf(Game::class, $snapshot->game);

        $snapshotNoGame = RankSnapshot::factory()->create(['game_id' => null]);
        $this->assertNull($snapshotNoGame->game);
    }

    public function test_rank_snapshot_casts_tier_to_enum(): void
    {
        $snapshot = RankSnapshot::factory()->create(['tier' => 'gold']);

        $this->assertInstanceOf(RankTier::class, $snapshot->tier);
        $this->assertEquals(RankTier::Gold, $snapshot->tier);
    }

    public function test_rank_snapshot_cascades_on_user_delete(): void
    {
        $snapshot = RankSnapshot::factory()->create();
        $userId = $snapshot->user_id;

        User::find($userId)->delete();

        $this->assertDatabaseMissing('rank_snapshots', ['id' => $snapshot->id]);
    }

    public function test_rank_snapshot_sets_null_on_game_delete(): void
    {
        $game = Game::factory()->create();
        $snapshot = RankSnapshot::factory()->create(['user_id' => $game->user_id, 'game_id' => $game->id]);

        $game->delete();

        $snapshot->refresh();
        $this->assertNull($snapshot->game_id);
        $this->assertDatabaseHas('rank_snapshots', ['id' => $snapshot->id]);
    }

    public function test_rank_snapshot_computes_rank_value(): void
    {
        $user = User::factory()->create();

        $bronze5 = RankSnapshot::create([
            'user_id' => $user->id,
            'role' => 'tank',
            'tier' => 'bronze',
            'division' => 5,
            'recorded_at' => now(),
        ]);
        $this->assertEquals(105, $bronze5->rank_value);

        $gold1 = RankSnapshot::create([
            'user_id' => $user->id,
            'role' => 'support',
            'tier' => 'gold',
            'division' => 1,
            'recorded_at' => now(),
        ]);
        $this->assertEquals(325, $gold1->rank_value);

        $champion = RankSnapshot::create([
            'user_id' => $user->id,
            'role' => 'damage',
            'tier' => 'champion',
            'division' => null,
            'recorded_at' => now(),
        ]);
        $this->assertEquals(800, $champion->rank_value);
    }

    public function test_rank_snapshot_defaults(): void
    {
        $user = User::factory()->create();
        $snapshot = RankSnapshot::create([
            'user_id' => $user->id,
            'role' => 'tank',
            'tier' => 'silver',
            'division' => 3,
            'recorded_at' => now(),
        ]);

        $this->assertEquals('post_game', $snapshot->snapshot_type);
    }

    public function test_rank_snapshot_recorded_at_is_datetime(): void
    {
        $snapshot = RankSnapshot::factory()->create();

        $this->assertInstanceOf(Carbon::class, $snapshot->recorded_at);
    }

    public function test_rank_snapshot_recomputes_rank_value_on_update(): void
    {
        $user = User::factory()->create();
        $snapshot = RankSnapshot::create([
            'user_id' => $user->id,
            'role' => 'tank',
            'tier' => 'bronze',
            'division' => 5,
            'recorded_at' => now(),
        ]);
        $this->assertEquals(105, $snapshot->rank_value);

        $snapshot->update(['tier' => 'gold', 'division' => 1]);

        $this->assertEquals(325, $snapshot->rank_value);
    }

    public function test_rank_snapshot_progress_percent_is_nullable(): void
    {
        $snapshot = RankSnapshot::factory()->create(['progress_percent' => null]);

        $this->assertNull($snapshot->progress_percent);
        $this->assertDatabaseHas('rank_snapshots', [
            'id' => $snapshot->id,
            'progress_percent' => null,
        ]);
    }

    public function test_rank_snapshot_stores_progress_percent(): void
    {
        $snapshot = RankSnapshot::factory()->create(['progress_percent' => 86]);

        $this->assertEquals(86, $snapshot->progress_percent);
        $this->assertDatabaseHas('rank_snapshots', [
            'id' => $snapshot->id,
            'progress_percent' => 86,
        ]);
    }

    public function test_rank_snapshot_progress_percent_updates_independently(): void
    {
        $snapshot = RankSnapshot::factory()->create([
            'tier' => 'platinum',
            'division' => 1,
            'progress_percent' => 86,
        ]);

        $this->assertEquals(RankTier::Platinum, $snapshot->tier);
        $this->assertEquals(1, $snapshot->division);
        $this->assertEquals(86, $snapshot->progress_percent);

        $snapshot->update(['progress_percent' => 92]);
        $snapshot->refresh();

        $this->assertEquals(92, $snapshot->progress_percent);
        $this->assertEquals(RankTier::Platinum, $snapshot->tier);
    }
}
