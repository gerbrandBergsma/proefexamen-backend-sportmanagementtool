<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function team_can_be_created(): void
    {
        $team = Team::create([
            'naam' => 'Heren 1',
            'sportsoort' => 'Voetbal',
            'categorie' => 'Senioren',
        ]);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'naam' => 'Heren 1',
        ]);
    }

    #[Test]
    public function team_has_many_players(): void
    {
        $team = Team::factory()->create();

        $players = Player::factory()->count(2)->create([
            'team_id' => $team->id,
        ]);

        $this->assertCount(2, $team->players);
        $this->assertInstanceOf(Player::class, $team->players->first());
    }

    #[Test]
    public function deleting_a_team_deletes_its_players(): void
    {
        $team = Team::factory()->create();

        $player = Player::factory()->create([
            'team_id' => $team->id,
        ]);

        $team->delete();

        $this->assertDatabaseMissing('teams', [
            'id' => $team->id,
        ]);

        $this->assertDatabaseMissing('players', [
            'id' => $player->id,
        ]);
    }
}
