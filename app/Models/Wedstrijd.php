<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wedstrijd extends Model
{
    protected $table = 'wedstrijden';

    protected $fillable = [
        'team_thuis_id',
        'team_uit_id',
        'datum',
        'locatie',
    ];

    public function teamThuis()
    {
        return $this->belongsTo(Team::class, 'team_thuis_id');
    }

    public function teamUit()
    {
        return $this->belongsTo(Team::class, 'team_uit_id');
    }
}
