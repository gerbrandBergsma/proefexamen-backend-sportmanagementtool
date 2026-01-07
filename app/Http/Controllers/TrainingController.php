<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingAttendance;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    /**
     * Alle trainingen ophalen
     * (met team + spelers + aanwezigheid)
     */
    public function index()
    {
        return response()->json(
            Training::with(['team', 'players'])
                ->orderBy('training_date', 'desc')
                ->get()
        );
    }

    /**
     * Nieuwe training aanmaken
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'title' => 'required|string|max:255',
            'training_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $training = Training::create($validated);

        // Voor alle spelers van het team automatisch attendance aanmaken
        foreach ($training->team->players as $player) {
            TrainingAttendance::create([
                'training_id' => $training->id,
                'player_id' => $player->id,
                'status' => 'unknown',
            ]);
        }

        return response()->json(
            $training->load('team', 'players'),
            201
        );
    }

    /**
     * 1 training ophalen
     */
    public function show(Training $training)
    {
        return response()->json(
            $training->load('team', 'players', 'attendances')
        );
    }

    /**
     * Training bijwerken
     */
    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'training_date' => 'sometimes|required|date',
            'start_time' => 'sometimes|required',
            'end_time' => 'sometimes|required',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $training->update($validated);

        return response()->json(
            $training->load('team', 'players')
        );
    }

    /**
     * Training verwijderen
     */
    public function destroy(Training $training)
    {
        $training->delete();

        return response()->json([
            'message' => 'Training verwijderd'
        ]);
    }

    /**
     * Aanwezigheid opslaan per speler
     */
    public function updateAttendance(Request $request, Training $training)
    {
        $attendance = $request->input('attendance', []);

        foreach ($attendance as $att) {
            TrainingAttendance::updateOrCreate(
                [
                    'training_id' => $training->id,
                    'player_id' => $att['player_id'],
                ],
                [
                    'status' => $att['status'],
                ]
            );
        }

        return response()->json([
            'message' => 'Aanwezigheid opgeslagen'
        ]);
    }
}
