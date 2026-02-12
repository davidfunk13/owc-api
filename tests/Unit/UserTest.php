<?php

namespace Tests\Unit;

use App\Models\CareerStatSnapshot;
use App\Models\CustomStatDefinition;
use App\Models\Game;
use App\Models\HeroSrSnapshot;
use App\Models\MediaAttachment;
use App\Models\PlaySession;
use App\Models\RankSnapshot;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created_with_factory(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
        $this->assertNotNull($user->sub);
        $this->assertNotNull($user->battlenet_id);
        $this->assertNotNull($user->battletag);
    }

    public function test_user_has_fillable_attributes(): void
    {
        $user = User::create([
            'sub' => 'test-sub-123',
            'battlenet_id' => '999888777',
            'battletag' => 'TestUser#1234',
        ]);

        $this->assertEquals('test-sub-123', $user->sub);
        $this->assertEquals('999888777', $user->battlenet_id);
        $this->assertEquals('TestUser#1234', $user->battletag);
    }

    public function test_user_hides_remember_token(): void
    {
        $user = User::factory()->create([
            'remember_token' => 'secret_token',
        ]);

        $array = $user->toArray();

        $this->assertArrayNotHasKey('remember_token', $array);
    }

    public function test_user_can_create_api_tokens(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('test-token');

        $this->assertNotNull($token->plainTextToken);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'test-token',
        ]);
    }

    public function test_update_or_create_creates_new_user(): void
    {
        $user = User::updateOrCreate(
            ['battlenet_id' => '123456789'],
            [
                'sub' => 'new-sub',
                'battletag' => 'NewPlayer#0001',
            ]
        );

        $this->assertDatabaseCount('users', 1);
        $this->assertEquals('123456789', $user->battlenet_id);
        $this->assertEquals('new-sub', $user->sub);
        $this->assertEquals('NewPlayer#0001', $user->battletag);
    }

    public function test_update_or_create_updates_existing_user(): void
    {
        User::factory()->create([
            'battlenet_id' => '123456789',
            'sub' => 'old-sub',
            'battletag' => 'OldPlayer#0001',
        ]);

        $user = User::updateOrCreate(
            ['battlenet_id' => '123456789'],
            [
                'sub' => 'updated-sub',
                'battletag' => 'UpdatedPlayer#0002',
            ]
        );

        $this->assertDatabaseCount('users', 1);
        $this->assertEquals('updated-sub', $user->sub);
        $this->assertEquals('UpdatedPlayer#0002', $user->battletag);
    }

    public function test_user_has_many_play_sessions(): void
    {
        $user = User::factory()->create();
        PlaySession::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->playSessions);
        $this->assertInstanceOf(PlaySession::class, $user->playSessions->first());
    }

    public function test_user_has_many_games(): void
    {
        $user = User::factory()->create();
        Game::factory()->count(2)->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->games);
        $this->assertInstanceOf(Game::class, $user->games->first());
    }

    public function test_user_has_many_tags(): void
    {
        $user = User::factory()->create();
        Tag::factory()->count(4)->create(['user_id' => $user->id]);

        $this->assertCount(4, $user->tags);
        $this->assertInstanceOf(Tag::class, $user->tags->first());
    }

    public function test_user_has_many_custom_stat_definitions(): void
    {
        $user = User::factory()->create();
        CustomStatDefinition::factory()->count(2)->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->customStatDefinitions);
        $this->assertInstanceOf(CustomStatDefinition::class, $user->customStatDefinitions->first());
    }

    public function test_user_has_many_rank_snapshots(): void
    {
        $user = User::factory()->create();
        RankSnapshot::factory()->count(5)->create(['user_id' => $user->id]);

        $this->assertCount(5, $user->rankSnapshots);
        $this->assertInstanceOf(RankSnapshot::class, $user->rankSnapshots->first());
    }

    public function test_user_has_many_hero_sr_snapshots(): void
    {
        $user = User::factory()->create();
        HeroSrSnapshot::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->heroSrSnapshots);
        $this->assertInstanceOf(HeroSrSnapshot::class, $user->heroSrSnapshots->first());
    }

    public function test_user_has_many_career_stat_snapshots(): void
    {
        $user = User::factory()->create();
        CareerStatSnapshot::factory()->count(2)->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->careerStatSnapshots);
        $this->assertInstanceOf(CareerStatSnapshot::class, $user->careerStatSnapshots->first());
    }

    public function test_user_has_many_media_attachments(): void
    {
        $user = User::factory()->create();
        MediaAttachment::factory()->count(2)->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->mediaAttachments);
        $this->assertInstanceOf(MediaAttachment::class, $user->mediaAttachments->first());
    }

    public function test_user_cascade_deletes_all_related_data(): void
    {
        $user = User::factory()->create();
        PlaySession::factory()->create(['user_id' => $user->id]);
        Game::factory()->create(['user_id' => $user->id]);
        Tag::factory()->create(['user_id' => $user->id]);
        CustomStatDefinition::factory()->create(['user_id' => $user->id]);
        RankSnapshot::factory()->create(['user_id' => $user->id]);
        HeroSrSnapshot::factory()->create(['user_id' => $user->id]);
        CareerStatSnapshot::factory()->create(['user_id' => $user->id]);
        MediaAttachment::factory()->create(['user_id' => $user->id]);

        $user->delete();

        $this->assertDatabaseCount('play_sessions', 0);
        $this->assertDatabaseCount('games', 0);
        $this->assertDatabaseCount('tags', 0);
        $this->assertDatabaseCount('custom_stat_definitions', 0);
        $this->assertDatabaseCount('rank_snapshots', 0);
        $this->assertDatabaseCount('hero_sr_snapshots', 0);
        $this->assertDatabaseCount('career_stat_snapshots', 0);
        $this->assertDatabaseCount('media_attachments', 0);
    }
}
