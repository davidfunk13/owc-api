<?php

namespace Tests\Unit\Models;

use App\Models\Game;
use App\Models\Hero;
use App\Models\HeroSrSnapshot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeroSrSnapshotTest extends TestCase
{
    use RefreshDatabase;

    public function test_hero_sr_snapshot_can_be_created_with_factory(): void
    {
        $snapshot = HeroSrSnapshot::factory()->create();

        $this->assertDatabaseHas('hero_sr_snapshots', ['id' => $snapshot->id]);
    }

    public function test_hero_sr_snapshot_has_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $hero = Hero::factory()->create();
        $snapshot = HeroSrSnapshot::create([
            'user_id' => $user->id,
            'hero_id' => $hero->id,
            'sr_value' => 2500,
            'snapshot_type' => 'post_game',
            'recorded_at' => now(),
        ]);

        $this->assertEquals(2500, $snapshot->sr_value);
    }

    public function test_hero_sr_snapshot_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $snapshot = HeroSrSnapshot::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $snapshot->user);
        $this->assertEquals($user->id, $snapshot->user->id);
    }

    public function test_hero_sr_snapshot_belongs_to_hero(): void
    {
        $hero = Hero::factory()->create();
        $snapshot = HeroSrSnapshot::factory()->create(['hero_id' => $hero->id]);

        $this->assertInstanceOf(Hero::class, $snapshot->hero);
        $this->assertEquals($hero->id, $snapshot->hero->id);
    }

    public function test_hero_sr_snapshot_belongs_to_game(): void
    {
        $game = Game::factory()->create();
        $snapshot = HeroSrSnapshot::factory()->create(['user_id' => $game->user_id, 'game_id' => $game->id]);

        $this->assertInstanceOf(Game::class, $snapshot->game);

        $snapshotNoGame = HeroSrSnapshot::factory()->create(['game_id' => null]);
        $this->assertNull($snapshotNoGame->game);
    }

    public function test_hero_sr_snapshot_cascades_on_user_delete(): void
    {
        $snapshot = HeroSrSnapshot::factory()->create();
        $userId = $snapshot->user_id;

        User::find($userId)->delete();

        $this->assertDatabaseMissing('hero_sr_snapshots', ['id' => $snapshot->id]);
    }

    public function test_hero_sr_snapshot_cascades_on_hero_delete(): void
    {
        $snapshot = HeroSrSnapshot::factory()->create();
        $heroId = $snapshot->hero_id;

        Hero::find($heroId)->delete();

        $this->assertDatabaseMissing('hero_sr_snapshots', ['id' => $snapshot->id]);
    }

    public function test_hero_sr_snapshot_sets_null_on_game_delete(): void
    {
        $game = Game::factory()->create();
        $snapshot = HeroSrSnapshot::factory()->create(['user_id' => $game->user_id, 'game_id' => $game->id]);

        $game->delete();

        $snapshot->refresh();
        $this->assertNull($snapshot->game_id);
        $this->assertDatabaseHas('hero_sr_snapshots', ['id' => $snapshot->id]);
    }

    public function test_hero_sr_snapshot_recorded_at_is_datetime(): void
    {
        $snapshot = HeroSrSnapshot::factory()->create();

        $this->assertInstanceOf(Carbon::class, $snapshot->recorded_at);
    }
}
