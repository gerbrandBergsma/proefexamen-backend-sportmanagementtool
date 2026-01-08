<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Wedstrijd;
use App\Models\Team;

class WedstrijdFactory extends Factory
{
    protected $model = Wedstrijd::class;

    public function definition()
    {
        return [
            'team_thuis_id' => Team::factory(),
            'team_uit_id' => Team::factory(),
            'datum' => $this->faker->date(),
            'locatie' => $this->faker->city(),
            'uitslag_thuis' => $this->faker->numberBetween(0, 5),
            'uitslag_uit' => $this->faker->numberBetween(0, 5),
        ];
    }
}
