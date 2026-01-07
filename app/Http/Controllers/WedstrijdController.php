<?php

namespace App\Http\Controllers;

use App\Models\Wedstrijd;
use Illuminate\Http\Request;

class WedstrijdController extends Controller
{
    // Alle wedstrijden ophalen
    public function index()
    {
        return response()->json(
            Wedstrijd::with(['teamThuis', 'teamUit'])->get()
        );
    }

    // Nieuwe wedstrijd aanmaken
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_thuis_id' => 'required|exists:teams,id',
            'team_uit_id'   => 'required|exists:teams,id|different:team_thuis_id',
            'datum'         => 'required|date',
            'locatie'       => 'required|string|max:255',
            'uitslag_thuis' => 'nullable|integer|min:0',
            'uitslag_uit'   => 'nullable|integer|min:0',
        ]);

        $wedstrijd = Wedstrijd::create($validated);
        
        // Laad de teams direct voordat je de response terugstuurt
        $wedstrijd->load(['teamThuis', 'teamUit']);

        return response()->json($wedstrijd, 201);
    }

    // Eén wedstrijd tonen
    public function show(Wedstrijd $wedstrijd)
    {
        return response()->json(
            $wedstrijd->load(['teamThuis', 'teamUit'])
        );
    }

    // Wedstrijd updaten
    public function update(Request $request, Wedstrijd $wedstrijd)
    {
        $validated = $request->validate([
            'team_thuis_id' => 'required|exists:teams,id',
            'team_uit_id'   => 'required|exists:teams,id|different:team_thuis_id',
            'datum'         => 'required|date',
            'locatie'       => 'required|string|max:255',
            'uitslag_thuis' => 'nullable|integer|min:0',
            'uitslag_uit'   => 'nullable|integer|min:0',
        ]);

        $wedstrijd->update($validated);
        
        // Laad de teams ook hier
        $wedstrijd->load(['teamThuis', 'teamUit']);

        return response()->json($wedstrijd);
    }

    // Wedstrijd verwijderen
    public function destroy(Wedstrijd $wedstrijd)
    {
        $wedstrijd->delete();

        return response()->json([
            'message' => 'Wedstrijd verwijderd'
        ]);
    }
}