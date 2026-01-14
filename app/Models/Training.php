<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrainingAttendance;
use App\Models\Player;
use App\Models\Team;

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
     * Team waartoe deze training behoort
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function attendances()
    {
        return $this->hasMany(TrainingAttendance::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'training_attendances')
            ->withPivot('status')
            ->withTimestamps();
    }
}
