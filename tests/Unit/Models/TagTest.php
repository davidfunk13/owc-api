<?php

namespace Tests\Unit\Models;

use App\Models\Game;
use App\Models\GameRound;
use App\Models\PlaySession;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_can_be_created_with_factory(): void
    {
        $tag = Tag::factory()->create();

        $this->assertDatabaseHas('tags', ['id' => $tag->id]);
    }

    public function test_tag_has_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $tag = Tag::create([
            'user_id' => $user->id,
            'name' => 'Tilted',
            'slug' => 'tilted',
            'color' => '#FF0000',
            'icon' => 'fire',
        ]);

        $this->assertEquals('Tilted', $tag->name);
        $this->assertEquals('tilted', $tag->slug);
        $this->assertEquals('#FF0000', $tag->color);
        $this->assertEquals('fire', $tag->icon);
    }

    public function test_tag_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $tag->user);
        $this->assertEquals($user->id, $tag->user->id);
    }

    public function test_tag_slug_unique_per_user(): void
    {
        $user = User::factory()->create();
        Tag::factory()->create(['user_id' => $user->id, 'slug' => 'tilted']);

        $this->expectException(QueryException::class);
        Tag::factory()->create(['user_id' => $user->id, 'slug' => 'tilted']);
    }

    public function test_tag_same_slug_different_users(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Tag::factory()->create(['user_id' => $user1->id, 'slug' => 'tilted']);
        $tag2 = Tag::factory()->create(['user_id' => $user2->id, 'slug' => 'tilted']);

        $this->assertDatabaseHas('tags', ['id' => $tag2->id]);
    }

    public function test_tag_cascades_on_user_delete(): void
    {
        $tag = Tag::factory()->create();
        $userId = $tag->user_id;

        User::find($userId)->delete();

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    public function test_tag_color_is_nullable(): void
    {
        $tag = Tag::factory()->create(['color' => null]);

        $this->assertNull($tag->color);
    }

    public function test_tag_games_morphed_by_many(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        $game = Game::factory()->create(['user_id' => $user->id]);

        $game->tags()->attach($tag->id);

        $this->assertCount(1, $tag->games);
        $this->assertEquals($game->id, $tag->games->first()->id);
    }

    public function test_tag_play_sessions_morphed_by_many(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        $session = PlaySession::factory()->create(['user_id' => $user->id]);

        $session->tags()->attach($tag->id);

        $this->assertCount(1, $tag->playSessions);
        $this->assertEquals($session->id, $tag->playSessions->first()->id);
    }

    public function test_tag_game_rounds_morphed_by_many(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        $game = Game::factory()->create(['user_id' => $user->id]);
        $round = GameRound::factory()->create(['game_id' => $game->id]);

        $round->tags()->attach($tag->id);

        $this->assertCount(1, $tag->gameRounds);
        $this->assertEquals($round->id, $tag->gameRounds->first()->id);
    }
}
