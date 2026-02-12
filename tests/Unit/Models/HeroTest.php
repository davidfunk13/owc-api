<?php

namespace Tests\Unit\Models;

use App\Enums\Role;
use App\Enums\SubRole;
use App\Models\GameHero;
use App\Models\GamePlayerHero;
use App\Models\Hero;
use App\Models\RoundHero;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_hero_can_be_created_with_factory(): void
    {
        $hero = Hero::factory()->create();

        $this->assertDatabaseHas('heroes', ['id' => $hero->id]);
        $this->assertNotNull($hero->name);
        $this->assertNotNull($hero->slug);
        $this->assertNotNull($hero->role);
        $this->assertNotNull($hero->sub_role);
    }

    public function test_hero_has_fillable_attributes(): void
    {
        $hero = Hero::create([
            'name' => 'Ana',
            'slug' => 'ana',
            'role' => 'support',
            'sub_role' => 'tactician',
        ]);

        $this->assertEquals('Ana', $hero->name);
        $this->assertEquals('ana', $hero->slug);
        $this->assertEquals('support', $hero->role->value);
        $this->assertEquals('tactician', $hero->sub_role->value);
    }

    public function test_hero_casts_role_to_enum(): void
    {
        $hero = Hero::factory()->create(['role' => 'tank']);

        $this->assertInstanceOf(Role::class, $hero->role);
        $this->assertEquals(Role::Tank, $hero->role);
    }

    public function test_hero_casts_sub_role_to_enum(): void
    {
        $hero = Hero::factory()->create(['role' => 'tank', 'sub_role' => 'bruiser']);

        $this->assertInstanceOf(SubRole::class, $hero->sub_role);
        $this->assertEquals(SubRole::Bruiser, $hero->sub_role);
    }

    public function test_hero_slug_must_be_unique(): void
    {
        Hero::factory()->create(['slug' => 'ana']);

        $this->expectException(QueryException::class);
        Hero::factory()->create(['slug' => 'ana']);
    }

    public function test_hero_has_many_game_heroes(): void
    {
        $hero = Hero::factory()->create();
        GameHero::factory()->create(['hero_id' => $hero->id]);

        $this->assertCount(1, $hero->gameHeroes);
        $this->assertInstanceOf(GameHero::class, $hero->gameHeroes->first());
    }

    public function test_hero_has_many_game_player_heroes(): void
    {
        $hero = Hero::factory()->create();
        GamePlayerHero::factory()->create(['hero_id' => $hero->id]);

        $this->assertCount(1, $hero->gamePlayerHeroes);
        $this->assertInstanceOf(GamePlayerHero::class, $hero->gamePlayerHeroes->first());
    }

    public function test_hero_has_many_round_heroes(): void
    {
        $hero = Hero::factory()->create();
        RoundHero::factory()->create(['hero_id' => $hero->id]);

        $this->assertCount(1, $hero->roundHeroes);
        $this->assertInstanceOf(RoundHero::class, $hero->roundHeroes->first());
    }
}
