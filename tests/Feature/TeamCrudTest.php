<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class TeamCrudTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_team()
    {
        $response = $this->postJson('/api/teams', [
            'naam' => 'Heren 1',
            'sportsoort' => 'Voetbal',
            'categorie' => 'Senioren',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('teams', ['naam' => 'Heren 1']);
    }

    #[Test]
    public function it_can_read_a_team()
    {
        $team = Team::factory()->create();

        $response = $this->getJson("/api/teams/{$team->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['naam' => $team->naam]);
    }

    #[Test]
    public function it_can_update_a_team()
    {
        $team = Team::factory()->create();

        $response = $this->putJson("/api/teams/{$team->id}", [
            'naam' => 'Dames 1',
            'sportsoort' => 'Hockey',
            'categorie' => 'Senioren',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('teams', ['naam' => 'Dames 1']);
    }

    #[Test]
    public function it_can_delete_a_team()
    {
        $team = Team::factory()->create();

        $response = $this->deleteJson("/api/teams/{$team->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
    }
}
