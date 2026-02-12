<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomStatDefinition>
 */
class CustomStatDefinitionFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'data_type' => 'integer',
            'unit' => fake()->optional()->randomElement(['kills', 'deaths', 'hp', 'seconds']),
            'is_active' => true,
        ];
    }
}
