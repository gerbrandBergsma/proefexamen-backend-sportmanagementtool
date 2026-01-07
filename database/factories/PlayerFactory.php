<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition(): array
    {
        return [
            'naam' => $this->faker->name(),
            'leeftijd' => 20,
            'team_id' => Team::factory(),
            'blessure' => null,
        ];
    }
}
