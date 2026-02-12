<?php

namespace Tests\Unit\Seeders;

use App\Models\Map;
use App\Models\MapSubmap;
use Database\Seeders\MapSeeder;
use Database\Seeders\MapSubmapSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapSubmapSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_submap_seeder_creates_all_submaps(): void
    {
        $this->seed(MapSeeder::class);
        $this->seed(MapSubmapSeeder::class);

        $this->assertEquals(36, MapSubmap::count());
    }

    public function test_map_submap_seeder_links_to_correct_maps(): void
    {
        $this->seed(MapSeeder::class);
        $this->seed(MapSubmapSeeder::class);

        $ilios = Map::where('slug', 'ilios')->first();
        $submapNames = $ilios->submaps->pluck('name')->sort()->values()->toArray();

        $this->assertEquals(['Lighthouse', 'Ruins', 'Well'], $submapNames);
    }

    public function test_map_submap_seeder_links_flashpoint_submaps(): void
    {
        $this->seed(MapSeeder::class);
        $this->seed(MapSubmapSeeder::class);

        $newJunkCity = Map::where('slug', 'new-junk-city')->first();
        $submapNames = $newJunkCity->submaps->pluck('name')->sort()->values()->toArray();

        $this->assertEquals(['Arena', 'Bomb Flats', 'Junkyard', 'Refinery', 'The Ducts'], $submapNames);
    }

    public function test_map_submap_seeder_is_idempotent(): void
    {
        $this->seed(MapSeeder::class);
        $this->seed(MapSubmapSeeder::class);
        $this->seed(MapSubmapSeeder::class);

        $this->assertEquals(36, MapSubmap::count());
    }
}
