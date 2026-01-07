<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class PlayerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function player_can_be_created_and_belongs_to_a_team(): void
    {
        $team = Team::factory()->create();

        $player = Player::create([
            'naam' => 'Jan Jansen',
            'leeftijd' => 20,
            'team_id' => $team->id,
        ]);

        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'team_id' => $team->id,
        ]);

        $this->assertInstanceOf(Team::class, $player->team);
    }

    #[Test]
    public function player_requires_a_team(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Player::create([
            'naam' => 'Zonder Team',
            'leeftijd' => 18,
            'team_id' => null,
        ]);
    }

    #[Test]
    public function deleting_a_team_cascades_to_players(): void
    {
        $team = Team::factory()->create();
        $player = Player::factory()->create([
            'team_id' => $team->id,
        ]);

        $team->delete();

        $this->assertDatabaseMissing('players', [
            'id' => $player->id,
        ]);
    }

    #[Test]
    public function player_can_have_an_optional_injury(): void
    {
        $team = Team::factory()->create();

        $player = Player::create([
            'naam' => 'Geblesseerde Speler',
            'leeftijd' => 22,
            'team_id' => $team->id,
            'blessure' => 'Knie',
        ]);

        $this->assertEquals('Knie', $player->blessure);
    }
}
