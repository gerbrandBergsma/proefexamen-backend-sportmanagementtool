<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id',
        'player_id',
        'status',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
