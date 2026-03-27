<?php

namespace Database\Factories;

use App\Enums\OptionType;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'type' => fake()->randomElement(OptionType::cases())->value,
            'value' => fake()->numberBetween(0, 3),
            'survey_id' => Survey::factory(),
        ];
    }
}
