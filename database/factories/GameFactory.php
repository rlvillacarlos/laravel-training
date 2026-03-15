<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    /**
     * The starting number of lives being used by the factory.
     */
    protected static int $lives = 6;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid7()->toString(),
            'name' => 'Game '.fake()->unique()->numberBetween(1),
            'starting_lives' => static::$lives,
        ];
    }
}
