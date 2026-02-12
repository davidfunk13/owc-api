<?php

namespace Database\Seeders;

use App\Models\Hero;
use Illuminate\Database\Seeder;

class HeroSeeder extends Seeder
{
    public function run(): void
    {
        $heroes = [
            // Tank — Bruiser
            ['name' => 'Mauga', 'slug' => 'mauga', 'role' => 'tank', 'sub_role' => 'bruiser'],
            ['name' => 'Orisa', 'slug' => 'orisa', 'role' => 'tank', 'sub_role' => 'bruiser'],
            ['name' => 'Roadhog', 'slug' => 'roadhog', 'role' => 'tank', 'sub_role' => 'bruiser'],
            ['name' => 'Zarya', 'slug' => 'zarya', 'role' => 'tank', 'sub_role' => 'bruiser'],

            // Tank — Initiator
            ['name' => 'D.Va', 'slug' => 'dva', 'role' => 'tank', 'sub_role' => 'initiator'],
            ['name' => 'Doomfist', 'slug' => 'doomfist', 'role' => 'tank', 'sub_role' => 'initiator'],
            ['name' => 'Winston', 'slug' => 'winston', 'role' => 'tank', 'sub_role' => 'initiator'],
            ['name' => 'Wrecking Ball', 'slug' => 'wrecking-ball', 'role' => 'tank', 'sub_role' => 'initiator'],

            // Tank — Stalwart
            ['name' => 'Domina', 'slug' => 'domina', 'role' => 'tank', 'sub_role' => 'stalwart'],
            ['name' => 'Hazard', 'slug' => 'hazard', 'role' => 'tank', 'sub_role' => 'stalwart'],
            ['name' => 'Junker Queen', 'slug' => 'junker-queen', 'role' => 'tank', 'sub_role' => 'stalwart'],
            ['name' => 'Ramattra', 'slug' => 'ramattra', 'role' => 'tank', 'sub_role' => 'stalwart'],
            ['name' => 'Reinhardt', 'slug' => 'reinhardt', 'role' => 'tank', 'sub_role' => 'stalwart'],
            ['name' => 'Sigma', 'slug' => 'sigma', 'role' => 'tank', 'sub_role' => 'stalwart'],

            // Damage — Sharpshooter
            ['name' => 'Ashe', 'slug' => 'ashe', 'role' => 'damage', 'sub_role' => 'sharpshooter'],
            ['name' => 'Cassidy', 'slug' => 'cassidy', 'role' => 'damage', 'sub_role' => 'sharpshooter'],
            ['name' => 'Hanzo', 'slug' => 'hanzo', 'role' => 'damage', 'sub_role' => 'sharpshooter'],
            ['name' => 'Sojourn', 'slug' => 'sojourn', 'role' => 'damage', 'sub_role' => 'sharpshooter'],
            ['name' => 'Widowmaker', 'slug' => 'widowmaker', 'role' => 'damage', 'sub_role' => 'sharpshooter'],

            // Damage — Flanker
            ['name' => 'Anran', 'slug' => 'anran', 'role' => 'damage', 'sub_role' => 'flanker'],
            ['name' => 'Genji', 'slug' => 'genji', 'role' => 'damage', 'sub_role' => 'flanker'],
            ['name' => 'Reaper', 'slug' => 'reaper', 'role' => 'damage', 'sub_role' => 'flanker'],
            ['name' => 'Tracer', 'slug' => 'tracer', 'role' => 'damage', 'sub_role' => 'flanker'],
            ['name' => 'Vendetta', 'slug' => 'vendetta', 'role' => 'damage', 'sub_role' => 'flanker'],
            ['name' => 'Venture', 'slug' => 'venture', 'role' => 'damage', 'sub_role' => 'flanker'],

            // Damage — Specialist
            ['name' => 'Bastion', 'slug' => 'bastion', 'role' => 'damage', 'sub_role' => 'specialist'],
            ['name' => 'Emre', 'slug' => 'emre', 'role' => 'damage', 'sub_role' => 'specialist'],
            ['name' => 'Junkrat', 'slug' => 'junkrat', 'role' => 'damage', 'sub_role' => 'specialist'],
            ['name' => 'Mei', 'slug' => 'mei', 'role' => 'damage', 'sub_role' => 'specialist'],
            ['name' => 'Soldier: 76', 'slug' => 'soldier-76', 'role' => 'damage', 'sub_role' => 'specialist'],
            ['name' => 'Symmetra', 'slug' => 'symmetra', 'role' => 'damage', 'sub_role' => 'specialist'],
            ['name' => 'Torbjorn', 'slug' => 'torbjorn', 'role' => 'damage', 'sub_role' => 'specialist'],

            // Damage — Recon
            ['name' => 'Echo', 'slug' => 'echo', 'role' => 'damage', 'sub_role' => 'recon'],
            ['name' => 'Freja', 'slug' => 'freja', 'role' => 'damage', 'sub_role' => 'recon'],
            ['name' => 'Pharah', 'slug' => 'pharah', 'role' => 'damage', 'sub_role' => 'recon'],
            ['name' => 'Sombra', 'slug' => 'sombra', 'role' => 'damage', 'sub_role' => 'recon'],

            // Support — Tactician
            ['name' => 'Ana', 'slug' => 'ana', 'role' => 'support', 'sub_role' => 'tactician'],
            ['name' => 'Baptiste', 'slug' => 'baptiste', 'role' => 'support', 'sub_role' => 'tactician'],
            ['name' => 'Jetpack Cat', 'slug' => 'jetpack-cat', 'role' => 'support', 'sub_role' => 'tactician'],
            ['name' => 'Lucio', 'slug' => 'lucio', 'role' => 'support', 'sub_role' => 'tactician'],
            ['name' => 'Zenyatta', 'slug' => 'zenyatta', 'role' => 'support', 'sub_role' => 'tactician'],

            // Support — Medic
            ['name' => 'Kiriko', 'slug' => 'kiriko', 'role' => 'support', 'sub_role' => 'medic'],
            ['name' => 'Lifeweaver', 'slug' => 'lifeweaver', 'role' => 'support', 'sub_role' => 'medic'],
            ['name' => 'Mercy', 'slug' => 'mercy', 'role' => 'support', 'sub_role' => 'medic'],
            ['name' => 'Moira', 'slug' => 'moira', 'role' => 'support', 'sub_role' => 'medic'],

            // Support — Survivor
            ['name' => 'Brigitte', 'slug' => 'brigitte', 'role' => 'support', 'sub_role' => 'survivor'],
            ['name' => 'Illari', 'slug' => 'illari', 'role' => 'support', 'sub_role' => 'survivor'],
            ['name' => 'Juno', 'slug' => 'juno', 'role' => 'support', 'sub_role' => 'survivor'],
            ['name' => 'Mizuki', 'slug' => 'mizuki', 'role' => 'support', 'sub_role' => 'survivor'],
            ['name' => 'Wuyang', 'slug' => 'wuyang', 'role' => 'support', 'sub_role' => 'survivor'],
        ];

        foreach ($heroes as $hero) {
            Hero::updateOrCreate(['slug' => $hero['slug']], $hero);
        }
    }
}
