<?php

namespace Tests\Unit\Models;

use App\Models\Game;
use App\Models\GameRound;
use App\Models\PlaySession;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TaggableTest extends TestCase
{
    use RefreshDatabase;

    public function test_taggable_can_be_created(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        $game = Game::factory()->create(['user_id' => $user->id]);

        $game->tags()->attach($tag->id);

        $this->assertDatabaseHas('taggables', [
            'tag_id' => $tag->id,
            'taggable_id' => $game->id,
            'taggable_type' => Game::class,
        ]);
    }

    public function test_taggable_unique_constraint(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        $game = Game::factory()->create(['user_id' => $user->id]);

        $game->tags()->attach($tag->id);

        $this->expectException(QueryException::class);
        DB::table('taggables')->insert([
            'tag_id' => $tag->id,
            'taggable_id' => $game->id,
            'taggable_type' => Game::class,
        ]);
    }

    public function test_taggable_cascades_on_tag_delete(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        $game = Game::factory()->create(['user_id' => $user->id]);

        $game->tags()->attach($tag->id);
        $tag->delete();

        $this->assertDatabaseMissing('taggables', ['tag_id' => $tag->id]);
    }

    public function test_game_can_have_tags(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        $game = Game::factory()->create(['user_id' => $user->id]);

        $game->tags()->attach($tag->id);

        $this->assertCount(1, $game->tags);
        $this->assertEquals($tag->id, $game->tags->first()->id);
    }

    public function test_play_session_can_have_tags(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        $session = PlaySession::factory()->create(['user_id' => $user->id]);

        $session->tags()->attach($tag->id);

        $this->assertCount(1, $session->tags);
    }

    public function test_game_round_can_have_tags(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        $game = Game::factory()->create(['user_id' => $user->id]);
        $round = GameRound::factory()->create(['game_id' => $game->id]);

        $round->tags()->attach($tag->id);

        $this->assertCount(1, $round->tags);
    }
}
