<?php

namespace Database\Factories;

use App\Models\Training;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingFactory extends Factory
{
    protected $model = Training::class;

    public function definition()
    {
        return [
            'team_id' => Team::factory(), // maakt automatisch een team als er nog geen is
            'title' => $this->faker->sentence(3),
            'training_date' => $this->faker->date(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time('H:i', '23:59'),
            'location' => $this->faker->city(),
            'notes' => $this->faker->paragraph(),
        ];
    }
}
