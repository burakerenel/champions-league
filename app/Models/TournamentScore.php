<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TournamentScore extends Model
{
    use HasFactory;
    public $fillable = ['team_id', 'plays', 'wins', 'draws', 'losses', 'goal_differences'];



    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    protected function prodiction(): Attribute
    {
        if ($this->plays == 0) {
            $prodiction = 0;
        } else {
            $prodiction = ($this->wins / $this->plays) * 100;
        }

        return Attribute::make(get: fn($value) => $prodiction);
    }
}
