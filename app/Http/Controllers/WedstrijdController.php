<?php

namespace App\Http\Controllers;

use App\Models\Wedstrijd;
use Illuminate\Http\Request;

class WedstrijdController extends Controller
{
    public function index()
    {
        return response()->json(
            Wedstrijd::with(['teamThuis', 'teamUit'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_thuis_id' => 'required|exists:teams,id',
            'team_uit_id'   => 'required|exists:teams,id|different:team_thuis_id',
            'datum'         => 'required|date',
            'locatie'       => 'required|string|max:255',
        ]);

        $wedstrijd = Wedstrijd::create($validated);

        return response()->json($wedstrijd, 201);
    }

    public function show(Wedstrijd $wedstrijd)
    {
        return response()->json(
            $wedstrijd->load(['teamThuis', 'teamUit'])
        );
    }

    public function update(Request $request, Wedstrijd $wedstrijd)
    {
        $validated = $request->validate([
            'team_thuis_id' => 'sometimes|exists:teams,id',
            'team_uit_id'   => 'sometimes|exists:teams,id|different:team_thuis_id',
            'datum'         => 'sometimes|date',
            'locatie'       => 'sometimes|string|max:255',
        ]);

        $wedstrijd->update($validated);

        return response()->json($wedstrijd);
    }

    public function destroy(Wedstrijd $wedstrijd)
    {
        $wedstrijd->delete();

        return response()->json(['message' => 'Wedstrijd verwijderd']);
    }
}
