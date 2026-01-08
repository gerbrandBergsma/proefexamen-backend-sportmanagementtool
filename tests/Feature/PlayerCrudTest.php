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

        $response = $this->postJson('/api/players', [
            'naam' => 'Jan Jansen',
            'leeftijd' => 20,
            'team_id' => $team->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('players', ['naam' => 'Jan Jansen', 'team_id' => $team->id]);
    }

    #[Test]
    public function it_can_read_a_player()
    {
        $player = Player::factory()->create();

        $response = $this->getJson("/api/players/{$player->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['naam' => $player->naam]);
    }

    #[Test]
    public function it_can_update_a_player()
    {
        $player = Player::factory()->create();
        $team = Team::factory()->create();

        $response = $this->putJson("/api/players/{$player->id}", [
            'naam' => 'Piet Pietersen',
            'leeftijd' => 22,
            'team_id' => $team->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('players', ['naam' => 'Piet Pietersen', 'team_id' => $team->id]);
    }

    #[Test]
    public function it_can_delete_a_player()
    {
        $player = Player::factory()->create();

        $response = $this->deleteJson("/api/players/{$player->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('players', ['id' => $player->id]);
    }
}
