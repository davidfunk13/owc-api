<?php

namespace Tests\Unit\Seeders;

use App\Enums\MapType;
use App\Models\Map;
use Database\Seeders\MapSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_seeder_creates_all_maps(): void
    {
        $this->seed(MapSeeder::class);

        $this->assertEquals(29, Map::count());
    }

    public function test_map_seeder_is_idempotent(): void
    {
        $this->seed(MapSeeder::class);
        $this->seed(MapSeeder::class);

        $this->assertEquals(29, Map::count());
    }

    public function test_map_seeder_assigns_correct_types(): void
    {
        $this->seed(MapSeeder::class);

        $ilios = Map::where('slug', 'ilios')->first();
        $kingsRow = Map::where('slug', 'kings-row')->first();
        $circuitRoyal = Map::where('slug', 'circuit-royal')->first();

        $this->assertEquals(MapType::Control, $ilios->map_type);
        $this->assertEquals(MapType::Hybrid, $kingsRow->map_type);
        $this->assertEquals(MapType::Escort, $circuitRoyal->map_type);
    }
}
