<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Player;
use App\Models\Training;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrainingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_training()
    {
        $team = Team::factory()->create();
        $players = Player::factory()->count(3)->create(['team_id' => $team->id]);

        $payload = [
            'team_id' => $team->id,
            'title' => 'Test Training',
            'training_date' => '2026-01-15',
            'start_time' => '15:00',
            'end_time' => '16:00',
            'location' => 'Heerenveen',
            'notes' => 'Geen',
        ];

        $response = $this->postJson('/api/trainings', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'title' => 'Test Training',
                     'team_id' => $team->id
                 ]);

        $this->assertDatabaseHas('trainings', ['title' => 'Test Training']);
        $this->assertDatabaseCount('training_attendances', 3); // automatisch voor team spelers
    }

    /** @test */
    public function it_can_update_a_training()
    {
        $training = Training::factory()->create(['title' => 'Old Title']);

        $response = $this->putJson("/api/trainings/{$training->id}", [
            'title' => 'New Title'
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'New Title']);

        $this->assertDatabaseHas('trainings', ['title' => 'New Title']);
    }

    /** @test */
    public function it_can_delete_a_training()
    {
        $training = Training::factory()->create();

        $response = $this->deleteJson("/api/trainings/{$training->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Training verwijderd']);

        $this->assertDatabaseMissing('trainings', ['id' => $training->id]);
    }

    /** @test */
    public function it_can_update_attendance()
    {
        $team = Team::factory()->create();
        $players = Player::factory()->count(2)->create(['team_id' => $team->id]);
        $training = Training::factory()->create(['team_id' => $team->id]);

        // Aanwezigheid automatisch aangemaakt door store? Zo niet, maak ze handmatig aan:
        foreach ($players as $p) {
            $training->attendances()->create([
                'player_id' => $p->id,
                'status' => 'unknown',
            ]);
        }

        $attendanceData = $players->map(fn($p) => [
            'player_id' => $p->id,
            'status' => 'present'
        ])->toArray();

        $response = $this->postJson("/api/trainings/{$training->id}/attendance", [
            'attendance' => $attendanceData
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Aanwezigheid opgeslagen']);

        foreach ($players as $p) {
            $this->assertDatabaseHas('training_attendances', [
                'training_id' => $training->id,
                'player_id' => $p->id,
                'status' => 'present'
            ]);
        }
    }
}
