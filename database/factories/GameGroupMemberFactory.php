<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameGroupMember>
 */
class GameGroupMemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'game_id' => Game::factory(),
            'battletag' => fake()->userName().'#'.fake()->numberBetween(1000, 9999),
            'user_id' => null,
        ];
    }
}
