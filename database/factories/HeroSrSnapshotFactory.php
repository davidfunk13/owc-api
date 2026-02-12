<?php

namespace Database\Factories;

use App\Models\Hero;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HeroSrSnapshot>
 */
class HeroSrSnapshotFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'hero_id' => Hero::factory(),
            'game_id' => null,
            'sr_value' => fake()->numberBetween(0, 5000),
            'snapshot_type' => 'post_game',
            'season' => null,
            'recorded_at' => now(),
        ];
    }
}
