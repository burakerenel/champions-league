<?php

namespace App\Repositories;

use App\Interfaces\TournamentScoreInterface;
use App\Models\TournamentScore;

class TournamentScoreRepository implements TournamentScoreInterface
{
    public function deleteAll()
    {
        return TournamentScore::query()->delete();
    }

    public function create(array $data)
    {
        return TournamentScore::create($data);
    }

    public function getAll()
    {
        return TournamentScore::with(['team'])->get();
    }

    public function findByTeamId(int $teamId){
        return TournamentScore::where('team_id', $teamId)->first();
    }

    public function incrementPlays(int $teamId){
        return TournamentScore::where('team_id', $teamId)->increment('plays');
    }

    public function incrementDraws(int $teamId){
        return TournamentScore::where('team_id', $teamId)->increment('draws');
    }

    public function incrementWins(int $teamId){
        return TournamentScore::where('team_id', $teamId)->increment('wins');
    }

    public function incrementLosess(int $teamId){
        return TournamentScore::where('team_id', $teamId)->increment('losses');
    }

    public function updateGoalDifferences(int $teamId,int $gd){
        return TournamentScore::where('team_id', $teamId)->update(['goal_differences'=>$gd]);
    }
}
