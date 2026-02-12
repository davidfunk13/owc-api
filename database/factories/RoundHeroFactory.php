<?php

namespace Database\Factories;

use App\Models\GameRound;
use App\Models\Hero;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoundHero>
 */
class RoundHeroFactory extends Factory
{
    public function definition(): array
    {
        return [
            'game_round_id' => GameRound::factory(),
            'hero_id' => Hero::factory(),
        ];
    }
}
