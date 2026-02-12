<?php

namespace Tests\Unit\Models;

use App\Enums\MapType;
use App\Models\Game;
use App\Models\Map;
use App\Models\MapSubmap;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_can_be_created_with_factory(): void
    {
        $map = Map::factory()->create();

        $this->assertDatabaseHas('maps', ['id' => $map->id]);
        $this->assertNotNull($map->name);
        $this->assertNotNull($map->slug);
        $this->assertNotNull($map->map_type);
    }

    public function test_map_has_fillable_attributes(): void
    {
        $map = Map::create([
            'name' => 'Ilios',
            'slug' => 'ilios',
            'map_type' => 'control',
        ]);

        $this->assertEquals('Ilios', $map->name);
        $this->assertEquals('ilios', $map->slug);
        $this->assertEquals('control', $map->map_type->value);
    }

    public function test_map_casts_map_type_to_enum(): void
    {
        $map = Map::factory()->create(['map_type' => 'control']);

        $this->assertInstanceOf(MapType::class, $map->map_type);
        $this->assertEquals(MapType::Control, $map->map_type);
    }

    public function test_map_slug_must_be_unique(): void
    {
        Map::factory()->create(['slug' => 'ilios']);

        $this->expectException(QueryException::class);
        Map::factory()->create(['slug' => 'ilios']);
    }

    public function test_map_has_many_submaps(): void
    {
        $map = Map::factory()->create();
        MapSubmap::factory()->count(3)->create(['map_id' => $map->id]);

        $this->assertCount(3, $map->submaps);
    }

    public function test_map_has_many_games(): void
    {
        $map = Map::factory()->create();
        Game::factory()->count(2)->create(['map_id' => $map->id]);

        $this->assertCount(2, $map->games);
        $this->assertInstanceOf(Game::class, $map->games->first());
    }
}
