<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Hero;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameHero>
 */
class GameHeroFactory extends Factory
{
    public function definition(): array
    {
        return [
            'game_id' => Game::factory(),
            'hero_id' => Hero::factory(),
            'is_primary' => false,
            'playtime_seconds' => null,
        ];
    }
}
