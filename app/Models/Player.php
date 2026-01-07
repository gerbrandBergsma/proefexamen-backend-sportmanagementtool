<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'naam',
        'leeftijd',
        'team_id',
        'blessure',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
