<?php

namespace App\Http\Controllers;

use App\Models\Wedstrijd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        Log::info('Before update:', $wedstrijd->toArray());
        Log::info('Validated data:', $validated);
        
        $wedstrijd->update($validated);
        
        Log::info('After update (from model):', $wedstrijd->toArray());
        Log::info('After update (fresh from DB):', $wedstrijd->fresh()->toArray());

        return response()->json($wedstrijd->fresh()->load(['teamThuis', 'teamUit']));
    }

    // Wedstrijd verwijderen
    public function destroy(Wedstrijd $wedstrijd)
    {
        $id = $wedstrijd->id;
        
        Log::info('Before delete:', ['id' => $id]);
        
        $wedstrijd->delete();
        
        $still_exists = Wedstrijd::find($id);
        Log::info('After delete:', ['id' => $id, 'still_exists' => $still_exists !== null]);

        return response()->json([
            'message' => 'Wedstrijd verwijderd'
        ], 200);
    }
}