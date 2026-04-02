<?php

namespace Tests\Feature;

use Database\Seeders\HeroSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeroControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_heroes(): void
    {
        $this->seed(HeroSeeder::class);

        $response = $this->getJson('/api/heroes');

        $response->assertOk();
        $response->assertJsonCount(50);
    }

    public function test_index_returns_hero_fields(): void
    {
        $this->seed(HeroSeeder::class);

        $response = $this->getJson('/api/heroes');

        $response->assertOk();
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'slug', 'role', 'sub_role'],
        ]);
    }

    public function test_index_is_ordered_by_role_and_name(): void
    {
        $this->seed(HeroSeeder::class);

        $response = $this->getJson('/api/heroes');

        $heroes = $response->json();
        $firstHero = $heroes[0];

        $this->assertEquals('damage', $firstHero['role']);
    }

    public function test_index_does_not_require_authentication(): void
    {
        $response = $this->getJson('/api/heroes');

        $response->assertOk();
    }
}
