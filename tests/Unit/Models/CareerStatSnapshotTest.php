<?php

namespace Tests\Unit\Models;

use App\Models\CareerStatSnapshot;
use App\Models\Hero;
use App\Models\MediaAttachment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareerStatSnapshotTest extends TestCase
{
    use RefreshDatabase;

    public function test_career_stat_snapshot_can_be_created_with_factory(): void
    {
        $snapshot = CareerStatSnapshot::factory()->create();

        $this->assertDatabaseHas('career_stat_snapshots', ['id' => $snapshot->id]);
    }

    public function test_career_stat_snapshot_has_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $snapshot = CareerStatSnapshot::create([
            'user_id' => $user->id,
            'stats_data' => ['eliminations' => 5000, 'deaths' => 2000],
            'captured_at' => now(),
            'source' => 'api_scrape',
        ]);

        $this->assertEquals($user->id, $snapshot->user_id);
        $this->assertEquals('api_scrape', $snapshot->source);
    }

    public function test_career_stat_snapshot_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $snapshot = CareerStatSnapshot::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $snapshot->user);
        $this->assertEquals($user->id, $snapshot->user->id);
    }

    public function test_career_stat_snapshot_belongs_to_hero(): void
    {
        $hero = Hero::factory()->create();
        $snapshot = CareerStatSnapshot::factory()->create(['hero_id' => $hero->id]);

        $this->assertInstanceOf(Hero::class, $snapshot->hero);

        $snapshotNoHero = CareerStatSnapshot::factory()->create(['hero_id' => null]);
        $this->assertNull($snapshotNoHero->hero);
    }

    public function test_career_stat_snapshot_cascades_on_user_delete(): void
    {
        $snapshot = CareerStatSnapshot::factory()->create();
        $userId = $snapshot->user_id;

        User::find($userId)->delete();

        $this->assertDatabaseMissing('career_stat_snapshots', ['id' => $snapshot->id]);
    }

    public function test_career_stat_snapshot_stats_data_is_json(): void
    {
        $data = ['eliminations' => 1234, 'deaths' => 567, 'healing' => 89012];
        $snapshot = CareerStatSnapshot::factory()->create(['stats_data' => $data]);

        $snapshot->refresh();
        $this->assertEquals($data, $snapshot->stats_data);
        $this->assertIsArray($snapshot->stats_data);
    }

    public function test_career_stat_snapshot_captured_at_is_datetime(): void
    {
        $snapshot = CareerStatSnapshot::factory()->create();

        $this->assertInstanceOf(Carbon::class, $snapshot->captured_at);
    }

    public function test_career_stat_snapshot_source_defaults_to_manual(): void
    {
        $user = User::factory()->create();
        $snapshot = CareerStatSnapshot::create([
            'user_id' => $user->id,
            'stats_data' => ['test' => 1],
            'captured_at' => now(),
        ]);

        $this->assertEquals('manual', $snapshot->source);
    }

    public function test_career_stat_snapshot_has_many_media_attachments(): void
    {
        $snapshot = CareerStatSnapshot::factory()->create();
        MediaAttachment::factory()->create([
            'user_id' => $snapshot->user_id,
            'attachable_id' => $snapshot->id,
            'attachable_type' => CareerStatSnapshot::class,
        ]);

        $this->assertCount(1, $snapshot->mediaAttachments);
        $this->assertInstanceOf(MediaAttachment::class, $snapshot->mediaAttachments->first());
    }
}
