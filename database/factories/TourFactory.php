<?php

namespace Database\Factories;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $travel = Travel::factory()->create();

        return [
            'travelId' => $travel->id,
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'startingDate' => now(),
            'endingDate' => now()->addDays(rand(1, 10)),
            'price' => rand(1000, 100000),
        ];
    }
}
