<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Player;
use App\Models\Training;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrainingFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_training()
    {
        $team = Team::factory()->create();
        $players = Player::factory(3)->create(['team_id' => $team->id]);

        $data = [
            'team_id' => $team->id,
            'title' => 'Test Training',
            'training_date' => '2026-01-15',
            'start_time' => '15:00',
            'end_time' => '16:00',
            'location' => 'Heerenveen',
            'notes' => 'Geen',
        ];

        $response = $this->postJson('/api/trainings', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Training']);

        $this->assertDatabaseHas('trainings', ['title' => 'Test Training']);
    }

    /** @test */
    public function it_can_update_a_training()
    {
        $team = Team::factory()->create();
        $training = Training::factory()->create(['team_id' => $team->id, 'title' => 'Oude Training']);

        $response = $this->putJson("/api/trainings/{$training->id}", [
            'title' => 'Nieuwe Training',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Nieuwe Training']);

        $this->assertDatabaseHas('trainings', ['title' => 'Nieuwe Training']);
    }

    /** @test */
    public function it_can_delete_a_training()
    {
        $team = Team::factory()->create();
        $training = Training::factory()->create(['team_id' => $team->id]);

        $response = $this->deleteJson("/api/trainings/{$training->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Training verwijderd']);

        $this->assertDatabaseMissing('trainings', ['id' => $training->id]);
    }

    /** @test */
    public function it_can_update_attendance()
    {
        $team = Team::factory()->create();
        $player = Player::factory()->create(['team_id' => $team->id]);
        $training = Training::factory()->create(['team_id' => $team->id]);

        $response = $this->postJson("/api/trainings/{$training->id}/attendance", [
            'attendance' => [
                ['player_id' => $player->id, 'status' => 'present']
            ]
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Aanwezigheid opgeslagen']);

        $this->assertDatabaseHas('training_attendances', [
            'training_id' => $training->id,
            'player_id' => $player->id,
            'status' => 'present',
        ]);
    }
}
