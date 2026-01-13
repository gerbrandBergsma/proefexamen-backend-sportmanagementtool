<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
use App\Models\Wedstrijd;
use App\Models\TrainingAttendance;

class StatistiekenController extends Controller
{
    public function apiIndex()
    {
        /** -------------------------
         * TEAM STATISTIEKEN
         * ------------------------- */
        $teams = Team::with('players')->get()->map(function ($team) {

            $wedstrijden = Wedstrijd::where('team_thuis_id', $team->id)
                ->orWhere('team_uit_id', $team->id)
                ->get();

            $stats = [
                'gespeeld' => $wedstrijden->count(),
                'gewonnen' => 0,
                'verloren' => 0,
                'gelijk' => 0,
                'doelpunten_voor' => 0,
                'doelpunten_tegen' => 0,
            ];

            foreach ($wedstrijden as $w) {
                $isThuis = $w->team_thuis_id == $team->id;

                $voor  = $isThuis ? $w->uitslag_thuis : $w->uitslag_uit;
                $tegen = $isThuis ? $w->uitslag_uit   : $w->uitslag_thuis;

                $stats['doelpunten_voor'] += $voor;
                $stats['doelpunten_tegen'] += $tegen;

                if ($voor > $tegen) $stats['gewonnen']++;
                elseif ($voor < $tegen) $stats['verloren']++;
                else $stats['gelijk']++;
            }

            $stats['gemiddelde_doelpunten_voor'] =
                $stats['gespeeld'] ? $stats['doelpunten_voor'] / $stats['gespeeld'] : 0;

            $stats['gemiddelde_doelpunten_tegen'] =
                $stats['gespeeld'] ? $stats['doelpunten_tegen'] / $stats['gespeeld'] : 0;

            return [
                'team' => $team,
                'stats' => $stats,
            ];
        });

        /** -------------------------
         * SPELER STATISTIEKEN
         * ------------------------- */
        $players = Player::with('team')->get()->map(function ($player) {

            $trainings = TrainingAttendance::where('player_id', $player->id)->get();

            $aanwezigheidScore = 0;

            foreach ($trainings as $t) {
                if ($t->status === 'present') {
                    $aanwezigheidScore += 1;
                } elseif ($t->status === 'late') {
                    $aanwezigheidScore += 0.5;
                }
                // absent & unknown tellen niet mee
            }

            $gemiddelde_aanwezigheid = $trainings->count()
                ? $aanwezigheidScore / $trainings->count()
                : 0;

            return [
                'player' => $player,
                'blessures' => $player->blessure,
                'doelpunten' => 0,
                'aanwezigheid' => $aanwezigheidScore,
                'gemiddelde_aanwezigheid' => $gemiddelde_aanwezigheid,
            ];
        });

        return response()->json([
            'teams' => $teams,
            'players' => $players,
        ]);
    }
}
