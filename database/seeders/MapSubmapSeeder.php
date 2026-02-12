<?php

namespace Database\Seeders;

use App\Models\Map;
use App\Models\MapSubmap;
use Illuminate\Database\Seeder;

class MapSubmapSeeder extends Seeder
{
    public function run(): void
    {
        $submaps = [
            'antarctic-peninsula' => [
                ['name' => 'Icebreaker', 'slug' => 'icebreaker'],
                ['name' => 'Labs', 'slug' => 'labs'],
                ['name' => 'Sublevel', 'slug' => 'sublevel'],
            ],
            'busan' => [
                ['name' => 'Downtown', 'slug' => 'downtown'],
                ['name' => 'MEKA Base', 'slug' => 'meka-base'],
                ['name' => 'Sanctuary', 'slug' => 'sanctuary'],
            ],
            'ilios' => [
                ['name' => 'Lighthouse', 'slug' => 'lighthouse'],
                ['name' => 'Ruins', 'slug' => 'ruins'],
                ['name' => 'Well', 'slug' => 'well'],
            ],
            'lijiang-tower' => [
                ['name' => 'Control Center', 'slug' => 'control-center'],
                ['name' => 'Garden', 'slug' => 'garden'],
                ['name' => 'Night Market', 'slug' => 'night-market'],
            ],
            'nepal' => [
                ['name' => 'Sanctum', 'slug' => 'sanctum'],
                ['name' => 'Shrine', 'slug' => 'shrine'],
                ['name' => 'Village', 'slug' => 'village'],
            ],
            'oasis' => [
                ['name' => 'City Center', 'slug' => 'city-center'],
                ['name' => 'Gardens', 'slug' => 'gardens'],
                ['name' => 'University', 'slug' => 'university'],
            ],
            'samoa' => [
                ['name' => 'Beach', 'slug' => 'beach'],
                ['name' => 'Downtown', 'slug' => 'downtown'],
                ['name' => 'Volcano', 'slug' => 'volcano'],
            ],

            // Flashpoint maps (5 capture points each)
            'new-junk-city' => [
                ['name' => 'Arena', 'slug' => 'arena'],
                ['name' => 'The Ducts', 'slug' => 'the-ducts'],
                ['name' => 'Refinery', 'slug' => 'refinery'],
                ['name' => 'Junkyard', 'slug' => 'junkyard'],
                ['name' => 'Bomb Flats', 'slug' => 'bomb-flats'],
            ],
            'suravasa' => [
                ['name' => 'Market', 'slug' => 'market'],
                ['name' => 'Garden', 'slug' => 'garden'],
                ['name' => 'Palace', 'slug' => 'palace'],
                ['name' => 'Temple', 'slug' => 'temple'],
                ['name' => 'Ruins', 'slug' => 'ruins'],
            ],
            'aatlis' => [
                ['name' => 'Station', 'slug' => 'station'],
                ['name' => 'Garden', 'slug' => 'garden'],
                ['name' => 'Town Center', 'slug' => 'town-center'],
                ['name' => 'Bazaar', 'slug' => 'bazaar'],
                ['name' => 'Resort', 'slug' => 'resort'],
            ],
        ];

        foreach ($submaps as $mapSlug => $mapSubmaps) {
            $map = Map::where('slug', $mapSlug)->first();

            if (! $map) {
                continue;
            }

            foreach ($mapSubmaps as $submap) {
                MapSubmap::updateOrCreate(
                    ['map_id' => $map->id, 'slug' => $submap['slug']],
                    ['name' => $submap['name']]
                );
            }
        }
    }
}
