<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use App\Models\Section;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $surveys = Survey::factory(10)->create()->each(function ($survey) {
            Section::factory()
                ->count(3)
                ->sequence(fn($sequence) => ['order' => $sequence->index + 1, 'survey_id' => $survey->id])
                ->create()
                ->each(function ($section, $index) {
                    $section->order = $index + 1;
                    $section->save();

                    $lastOrder = Question::where('section_id', $section->id)->max('order') ?? 0;

                    Question::factory()
                        ->count(5)
                        ->sequence(fn($sequence) => [
                            'order' => $lastOrder + $sequence->index + 1,
                            'section_id' => $section->id,
                        ])
                        ->create();
                });
            Option::factory()
                ->count(4)
                ->sequence(fn($sequence) => ['survey_id' => $survey->id, 'value' => $sequence->index + 1])
                ->create();
        });
    }
}
