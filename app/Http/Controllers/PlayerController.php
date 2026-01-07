<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;


class PlayerController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'naam' => 'required|string|max:255',
            'leeftijd' => 'required|integer|min:0',
            'team_id' => 'required|exists:teams,id',
        ]);

        $player = Player::create($validated);

        return response()->json($player, 201);
    }

    public function show($id)
    {
        $player = Player::findOrFail($id);
        return response()->json($player, 200);
    }

    public function update(Request $request, $id)
    {
        $player = Player::findOrFail($id);

        $validated = $request->validate([
            'naam' => 'sometimes|required|string|max:255',
            'leeftijd' => 'sometimes|required|integer|min:0',
            'team_id' => 'sometimes|required|exists:teams,id',
        ]);

        $player->update($validated);

        return response()->json($player, 200);
    }

    public function index()
{
    return response()->json(Player::all(), 200);
}


    public function destroy($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();

        return response()->json(['message' => 'Player verwijderd'], 200);
    }
}
