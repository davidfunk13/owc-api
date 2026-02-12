<?php

namespace Database\Factories;

use App\Models\Map;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MapSubmap>
 */
class MapSubmapFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'map_id' => Map::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'image_url' => null,
        ];
    }
}
