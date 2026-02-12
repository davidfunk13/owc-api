<?php

namespace Tests\Unit\Models;

use App\Models\Game;
use App\Models\GameGroupMember;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameGroupMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_group_member_can_be_created_with_factory(): void
    {
        $member = GameGroupMember::factory()->create();

        $this->assertDatabaseHas('game_group_members', ['id' => $member->id]);
    }

    public function test_game_group_member_belongs_to_game(): void
    {
        $game = Game::factory()->create();
        $member = GameGroupMember::factory()->create(['game_id' => $game->id]);

        $this->assertInstanceOf(Game::class, $member->game);
        $this->assertEquals($game->id, $member->game->id);
    }

    public function test_game_group_member_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $member = GameGroupMember::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $member->user);

        $memberNoUser = GameGroupMember::factory()->create(['user_id' => null]);
        $this->assertNull($memberNoUser->user);
    }

    public function test_game_group_member_unique_per_game_and_battletag(): void
    {
        $game = Game::factory()->create();
        GameGroupMember::factory()->create(['game_id' => $game->id, 'battletag' => 'Friend#1234']);

        $this->expectException(QueryException::class);
        GameGroupMember::factory()->create(['game_id' => $game->id, 'battletag' => 'Friend#1234']);
    }

    public function test_game_group_member_cascades_on_game_delete(): void
    {
        $member = GameGroupMember::factory()->create();
        $gameId = $member->game_id;

        Game::find($gameId)->delete();

        $this->assertDatabaseMissing('game_group_members', ['id' => $member->id]);
    }

    public function test_game_group_member_sets_null_on_user_delete(): void
    {
        $user = User::factory()->create();
        $member = GameGroupMember::factory()->create(['user_id' => $user->id]);

        $user->delete();

        $member->refresh();
        $this->assertNull($member->user_id);
        $this->assertDatabaseHas('game_group_members', ['id' => $member->id]);
    }
}
