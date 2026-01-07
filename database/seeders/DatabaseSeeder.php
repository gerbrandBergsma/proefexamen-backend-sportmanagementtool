<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Player;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Lijst van voorbeeldteams
        $teamsData = [
            ['naam' => 'Rood Leeuwen', 'sportsoort' => 'Voetbal', 'categorie' => 'Junioren'],
            ['naam' => 'Blauwe Tijgers', 'sportsoort' => 'Basketbal', 'categorie' => 'Senioren'],
            ['naam' => 'Gele Vlammen', 'sportsoort' => 'Hockey', 'categorie' => 'Junioren'],
            ['naam' => 'Groene Slangen', 'sportsoort' => 'Volleybal', 'categorie' => 'Senioren'],
            ['naam' => 'Zwarte Panters', 'sportsoort' => 'Rugby', 'categorie' => 'Senioren'],
        ];

        foreach ($teamsData as $teamData) {
            $team = Team::create($teamData);

            // Voeg 2 spelers per team toe
            for ($i = 1; $i <= 2; $i++) {
                Player::create([
                    'naam' => $team->naam . " Speler $i",
                    'leeftijd' => rand(18, 30),
                    'team_id' => $team->id,
                    'blessure' => null,
                ]);
            }
        }
    }
}
