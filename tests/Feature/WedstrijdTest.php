<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Wedstrijd;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WedstrijdTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function een_wedstrijd_kan_worden_aangemaakt()
    {
        $teamThuis = Team::factory()->create(['naam' => 'Blauwe Tijgers']);
        $teamUit = Team::factory()->create(['naam' => 'Rode Tijgers']);

        $response = $this->postJson('/api/wedstrijden', [
            'team_thuis_id' => $teamThuis->id,
            'team_uit_id' => $teamUit->id,
            'datum' => '2026-01-15',
            'locatie' => 'Heerenveen',
            'uitslag_thuis' => 2,
            'uitslag_uit' => 1,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('wedstrijden', [
            'team_thuis_id' => $teamThuis->id,
            'team_uit_id' => $teamUit->id,
            'locatie' => 'Heerenveen',
            'uitslag_thuis' => 2,
            'uitslag_uit' => 1,
        ]);
    }

    /** @test */
    public function wedstrijden_kunnen_worden_opgehaald_met_teams()
    {
        $teamThuis = Team::factory()->create(['naam' => 'Blauwe Tijgers']);
        $teamUit = Team::factory()->create(['naam' => 'Rode Tijgers']);

        Wedstrijd::factory()->create([
            'team_thuis_id' => $teamThuis->id,
            'team_uit_id' => $teamUit->id,
        ]);

        $response = $this->getJson('/api/wedstrijden');

        $response->assertStatus(200)
                 ->assertJsonFragment(['naam' => 'Blauwe Tijgers'])
                 ->assertJsonFragment(['naam' => 'Rode Tijgers']);
    }

    /** @test */
    public function een_wedstrijd_kan_worden_bijgewerkt()
    {
        $teamThuis = Team::factory()->create();
        $teamUit = Team::factory()->create();
        $wedstrijd = Wedstrijd::factory()->create([
            'team_thuis_id' => $teamThuis->id,
            'team_uit_id' => $teamUit->id,
            'locatie' => 'Heerenveen'
        ]);

        $response = $this->putJson("/api/wedstrijden/{$wedstrijd->id}", [
            'team_thuis_id' => $teamThuis->id,
            'team_uit_id' => $teamUit->id,
            'datum' => '2026-01-16',
            'locatie' => 'Leeuwarden',
            'uitslag_thuis' => 3,
            'uitslag_uit' => 0,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('wedstrijden', [
            'id' => $wedstrijd->id,
            'locatie' => 'Leeuwarden',
            'uitslag_thuis' => 3,
            'uitslag_uit' => 0,
        ]);
    }

    /** @test */
    public function een_wedstrijd_kan_worden_verwijderd()
    {
        $teamThuis = Team::factory()->create();
        $teamUit = Team::factory()->create();
        $wedstrijd = Wedstrijd::factory()->create([
            'team_thuis_id' => $teamThuis->id,
            'team_uit_id' => $teamUit->id,
        ]);

        $response = $this->deleteJson("/api/wedstrijden/{$wedstrijd->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('wedstrijden', ['id' => $wedstrijd->id]);
    }
}
