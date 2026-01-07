<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrainingAttendance; // <-- toevoegen
use App\Models\Player;             // <-- toevoegen
use App\Models\Team;               // <-- toevoegen

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'title',
        'training_date',
        'start_time',
        'end_time',
        'location',
        'notes',
    ];

    /**
     * Het team waartoe deze training behoort
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Alle aanwezigen bij deze training
     */
    public function attendances()
    {
        return $this->hasMany(TrainingAttendance::class);
    }

    /**
     * Spelers van het team met aanwezigheidsstatus
     */
    public function players()
    {
        return $this->belongsToMany(Player::class, 'training_attendances')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
