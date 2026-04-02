<?php

namespace Tests\Feature;

use Database\Seeders\MapSeeder;
use Database\Seeders\MapSubmapSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_maps(): void
    {
        $this->seed(MapSeeder::class);

        $response = $this->getJson('/api/maps');

        $response->assertOk();
        $response->assertJsonCount(29);
    }

    public function test_index_returns_map_fields(): void
    {
        $this->seed(MapSeeder::class);

        $response = $this->getJson('/api/maps');

        $response->assertOk();
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'slug', 'map_type', 'submaps'],
        ]);
    }

    public function test_index_eager_loads_submaps(): void
    {
        $this->seed(MapSeeder::class);
        $this->seed(MapSubmapSeeder::class);

        $response = $this->getJson('/api/maps');

        $maps = collect($response->json());
        $ilios = $maps->firstWhere('slug', 'ilios');

        $this->assertCount(3, $ilios['submaps']);
        $this->assertEquals('Lighthouse', $ilios['submaps'][0]['name']);
    }

    public function test_index_is_ordered_by_map_type_and_name(): void
    {
        $this->seed(MapSeeder::class);

        $response = $this->getJson('/api/maps');

        $maps = $response->json();
        $firstMap = $maps[0];

        $this->assertEquals('control', $firstMap['map_type']);
    }

    public function test_index_does_not_require_authentication(): void
    {
        $response = $this->getJson('/api/maps');

        $response->assertOk();
    }
}
