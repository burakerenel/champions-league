<?php

namespace App\Interfaces;

interface TournamentScoreInterface
{
    public function deleteAll();
    public function create(array $data);

    public function getAll();

    public function findByTeamId(int $teamId);

    public function incrementPlays(int $teamId);

    public function incrementDraws(int $teamId);

    public function incrementWins(int $teamId);

    public function incrementLosess(int $teamId);

    public function updateGoalDifferences(int $teamId,int $gd);
}
