<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\CalcScore;

class TournamentMatch extends Model
{
    use HasFactory;

    protected $dispatchesEvents = [
        'created' => CalcScore::class,
    ];

    public $fillable = ['team1_id', 'team2_id', 'week', 'home_goals', 'away_goals'];
}
