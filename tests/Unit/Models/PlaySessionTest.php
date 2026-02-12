<?php

namespace Tests\Unit\Models;

use App\Models\Game;
use App\Models\PlaySession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaySessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_play_session_can_be_created_with_factory(): void
    {
        $session = PlaySession::factory()->create();

        $this->assertDatabaseHas('play_sessions', ['id' => $session->id]);
        $this->assertNotNull($session->user_id);
    }

    public function test_play_session_has_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $session = PlaySession::create([
            'user_id' => $user->id,
            'title' => 'Saturday grind',
            'notes' => 'Going for rank up',
            'started_at' => '2026-02-10 18:00:00',
            'ended_at' => '2026-02-10 22:00:00',
        ]);

        $this->assertEquals('Saturday grind', $session->title);
        $this->assertEquals('Going for rank up', $session->notes);
    }

    public function test_play_session_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $session = PlaySession::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $session->user);
        $this->assertEquals($user->id, $session->user->id);
    }

    public function test_play_session_has_many_games(): void
    {
        $session = PlaySession::factory()->create();
        Game::factory()->count(3)->create(['user_id' => $session->user_id, 'play_session_id' => $session->id]);

        $this->assertCount(3, $session->games);
    }

    public function test_play_session_cascades_on_user_delete(): void
    {
        $session = PlaySession::factory()->create();
        $userId = $session->user_id;

        User::find($userId)->delete();

        $this->assertDatabaseMissing('play_sessions', ['id' => $session->id]);
    }

    public function test_play_session_title_is_nullable(): void
    {
        $session = PlaySession::factory()->create(['title' => null]);

        $this->assertNull($session->title);
        $this->assertDatabaseHas('play_sessions', ['id' => $session->id]);
    }

    public function test_play_session_casts_dates(): void
    {
        $session = PlaySession::factory()->create([
            'started_at' => '2026-02-10 18:00:00',
            'ended_at' => '2026-02-10 22:00:00',
        ]);

        $this->assertInstanceOf(Carbon::class, $session->started_at);
        $this->assertInstanceOf(Carbon::class, $session->ended_at);
    }

    public function test_play_session_games_count(): void
    {
        $session = PlaySession::factory()->create();
        Game::factory()->count(5)->create(['user_id' => $session->user_id, 'play_session_id' => $session->id]);

        $sessionWithCount = PlaySession::withCount('games')->find($session->id);

        $this->assertEquals(5, $sessionWithCount->games_count);
    }
}
