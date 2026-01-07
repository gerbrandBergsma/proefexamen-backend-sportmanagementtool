<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'naam' => 'required|string|max:255',
            'sportsoort' => 'required|string|max:255',
            'categorie' => 'required|string|max:255',
        ]);

        $team = Team::create($validated);

        return response()->json($team, 201);
    }

    public function show($id)
    {
        $team = Team::findOrFail($id);
        return response()->json($team, 200);
    }
    public function index()
{
    return response()->json(Team::all(), 200);
}


    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $validated = $request->validate([
            'naam' => 'sometimes|required|string|max:255',
            'sportsoort' => 'sometimes|required|string|max:255',
            'categorie' => 'sometimes|required|string|max:255',
        ]);

        $team->update($validated);

        return response()->json($team, 200);
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return response()->json(['message' => 'Team verwijderd'], 200);
    }
}
