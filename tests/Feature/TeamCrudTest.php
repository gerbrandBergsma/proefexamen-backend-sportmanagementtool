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
        $response = $this->post('/teams', [
            'naam' => 'Heren 1',
            'sportsoort' => 'Voetbal',
            'categorie' => 'Senioren',
        ]);

        $response->assertStatus(302); // redirect na creatie
        $this->assertDatabaseHas('teams', ['naam' => 'Heren 1']);
    }

    #[Test]
    public function it_can_read_a_team()
    {
        $team = Team::factory()->create();

        $response = $this->get("/teams/{$team->id}");
        $response->assertStatus(200);
        $response->assertSee($team->naam);
    }

    #[Test]
    public function it_can_update_a_team()
    {
        $team = Team::factory()->create();

        $response = $this->put("/teams/{$team->id}", [
            'naam' => 'Dames 1',
            'sportsoort' => 'Hockey',
            'categorie' => 'Senioren',
        ]);

        $response->assertStatus(302); // redirect na update
        $this->assertDatabaseHas('teams', ['naam' => 'Dames 1']);
    }

    #[Test]
    public function it_can_delete_a_team()
    {
        $team = Team::factory()->create();

        $response = $this->delete("/teams/{$team->id}");
        $response->assertStatus(302); // redirect na delete
        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
    }
}
