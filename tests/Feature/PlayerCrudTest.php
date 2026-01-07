<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class PlayerCrudTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_player()
    {
        $team = Team::factory()->create();

        $response = $this->post('/players', [
            'naam' => 'Jan Jansen',
            'leeftijd' => 20,
            'team_id' => $team->id,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('players', ['naam' => 'Jan Jansen', 'team_id' => $team->id]);
    }

    #[Test]
    public function it_can_read_a_player()
    {
        $player = Player::factory()->create();

        $response = $this->get("/players/{$player->id}");
        $response->assertStatus(200);
        $response->assertSee($player->naam);
    }

    #[Test]
    public function it_can_update_a_player()
    {
        $player = Player::factory()->create();
        $team = Team::factory()->create();

        $response = $this->put("/players/{$player->id}", [
            'naam' => 'Piet Pietersen',
            'leeftijd' => 22,
            'team_id' => $team->id,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('players', ['naam' => 'Piet Pietersen', 'team_id' => $team->id]);
    }

    #[Test]
    public function it_can_delete_a_player()
    {
        $player = Player::factory()->create();

        $response = $this->delete("/players/{$player->id}");
        $response->assertStatus(302);
        $this->assertDatabaseMissing('players', ['id' => $player->id]);
    }
}
