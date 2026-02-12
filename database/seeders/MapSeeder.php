<?php

namespace Database\Seeders;

use App\Models\Map;
use Illuminate\Database\Seeder;

class MapSeeder extends Seeder
{
    public function run(): void
    {
        $maps = [
            // Control
            ['name' => 'Antarctic Peninsula', 'slug' => 'antarctic-peninsula', 'map_type' => 'control'],
            ['name' => 'Busan', 'slug' => 'busan', 'map_type' => 'control'],
            ['name' => 'Ilios', 'slug' => 'ilios', 'map_type' => 'control'],
            ['name' => 'Lijiang Tower', 'slug' => 'lijiang-tower', 'map_type' => 'control'],
            ['name' => 'Nepal', 'slug' => 'nepal', 'map_type' => 'control'],
            ['name' => 'Oasis', 'slug' => 'oasis', 'map_type' => 'control'],
            ['name' => 'Samoa', 'slug' => 'samoa', 'map_type' => 'control'],

            // Escort
            ['name' => 'Circuit Royal', 'slug' => 'circuit-royal', 'map_type' => 'escort'],
            ['name' => 'Dorado', 'slug' => 'dorado', 'map_type' => 'escort'],
            ['name' => 'Havana', 'slug' => 'havana', 'map_type' => 'escort'],
            ['name' => 'Junkertown', 'slug' => 'junkertown', 'map_type' => 'escort'],
            ['name' => 'Rialto', 'slug' => 'rialto', 'map_type' => 'escort'],
            ['name' => 'Route 66', 'slug' => 'route-66', 'map_type' => 'escort'],
            ['name' => 'Shambali Monastery', 'slug' => 'shambali-monastery', 'map_type' => 'escort'],
            ['name' => 'Watchpoint: Gibraltar', 'slug' => 'watchpoint-gibraltar', 'map_type' => 'escort'],

            // Hybrid
            ['name' => 'Blizzard World', 'slug' => 'blizzard-world', 'map_type' => 'hybrid'],
            ['name' => 'Eichenwalde', 'slug' => 'eichenwalde', 'map_type' => 'hybrid'],
            ['name' => 'Hollywood', 'slug' => 'hollywood', 'map_type' => 'hybrid'],
            ['name' => "King's Row", 'slug' => 'kings-row', 'map_type' => 'hybrid'],
            ['name' => 'Midtown', 'slug' => 'midtown', 'map_type' => 'hybrid'],
            ['name' => 'Numbani', 'slug' => 'numbani', 'map_type' => 'hybrid'],
            ['name' => 'Paraiso', 'slug' => 'paraiso', 'map_type' => 'hybrid'],

            // Push
            ['name' => 'Colosseo', 'slug' => 'colosseo', 'map_type' => 'push'],
            ['name' => 'Esperanca', 'slug' => 'esperanca', 'map_type' => 'push'],
            ['name' => 'New Queen Street', 'slug' => 'new-queen-street', 'map_type' => 'push'],
            ['name' => 'Runasapi', 'slug' => 'runasapi', 'map_type' => 'push'],

            // Flashpoint
            ['name' => 'New Junk City', 'slug' => 'new-junk-city', 'map_type' => 'flashpoint'],
            ['name' => 'Suravasa', 'slug' => 'suravasa', 'map_type' => 'flashpoint'],
            ['name' => 'Aatlis', 'slug' => 'aatlis', 'map_type' => 'flashpoint'],

            // Clash
            ['name' => 'Hanaoka', 'slug' => 'hanaoka', 'map_type' => 'clash'],
            ['name' => 'Throne of Anubis', 'slug' => 'throne-of-anubis', 'map_type' => 'clash'],
        ];

        foreach ($maps as $map) {
            Map::updateOrCreate(['slug' => $map['slug']], $map);
        }
    }
}
