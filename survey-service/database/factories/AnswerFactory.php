<?php

namespace Database\Factories;

use App\Models\Option;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'response_id' => Response::factory(),
            'option_id' => Option::factory(),
            'question_id' => Question::factory(),
        ];
    }
}
