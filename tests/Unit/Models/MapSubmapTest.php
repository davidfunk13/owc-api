<?php

namespace Tests\Unit\Models;

use App\Models\GameRound;
use App\Models\Map;
use App\Models\MapSubmap;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapSubmapTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_submap_can_be_created_with_factory(): void
    {
        $submap = MapSubmap::factory()->create();

        $this->assertDatabaseHas('map_submaps', ['id' => $submap->id]);
        $this->assertNotNull($submap->name);
        $this->assertNotNull($submap->slug);
        $this->assertNotNull($submap->map_id);
    }

    public function test_map_submap_has_fillable_attributes(): void
    {
        $map = Map::factory()->create();
        $submap = MapSubmap::create([
            'map_id' => $map->id,
            'name' => 'Well',
            'slug' => 'well',
        ]);

        $this->assertEquals($map->id, $submap->map_id);
        $this->assertEquals('Well', $submap->name);
        $this->assertEquals('well', $submap->slug);
    }

    public function test_map_submap_belongs_to_map(): void
    {
        $map = Map::factory()->create();
        $submap = MapSubmap::factory()->create(['map_id' => $map->id]);

        $this->assertInstanceOf(Map::class, $submap->map);
        $this->assertEquals($map->id, $submap->map->id);
    }

    public function test_map_submap_slug_unique_per_map(): void
    {
        $map = Map::factory()->create();
        MapSubmap::factory()->create(['map_id' => $map->id, 'slug' => 'well']);

        $this->expectException(QueryException::class);
        MapSubmap::factory()->create(['map_id' => $map->id, 'slug' => 'well']);
    }

    public function test_map_submap_same_slug_different_maps(): void
    {
        $map1 = Map::factory()->create();
        $map2 = Map::factory()->create();

        MapSubmap::factory()->create(['map_id' => $map1->id, 'slug' => 'downtown']);
        $submap2 = MapSubmap::factory()->create(['map_id' => $map2->id, 'slug' => 'downtown']);

        $this->assertDatabaseHas('map_submaps', ['id' => $submap2->id]);
    }

    public function test_map_submap_cascades_on_map_delete(): void
    {
        $map = Map::factory()->create();
        $submap = MapSubmap::factory()->create(['map_id' => $map->id]);

        $map->delete();

        $this->assertDatabaseMissing('map_submaps', ['id' => $submap->id]);
    }

    public function test_map_submap_has_many_game_rounds(): void
    {
        $submap = MapSubmap::factory()->create();
        GameRound::factory()->create(['map_submap_id' => $submap->id]);

        $this->assertCount(1, $submap->gameRounds);
        $this->assertInstanceOf(GameRound::class, $submap->gameRounds->first());
    }
}
