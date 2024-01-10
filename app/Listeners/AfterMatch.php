<?php

namespace App\Listeners;

use App\Events\CalcScore;
use App\Models\TournamentScore;
use App\Interfaces\TournamentScoreInterface;
use App\Repositories\TournamentScoreRepository;

class AfterMatch
{
    /**
     * Create the event listener.
     */

     private TournamentScoreInterface $tournamentScoreRepository;
    public function __construct(TournamentScoreRepository $tournamentScoreRepository)
    {
        $this->tournamentScoreRepository = $tournamentScoreRepository;
    }

    /**
     * Handle the event.
     */
    public function handle(CalcScore $event): void
    {
        $homeGoals = $event->tournamentMatch->home_goals;
        $awayGoals = $event->tournamentMatch->away_goals;
        $team1Id= $event->tournamentMatch->team1_id;
        $team2Id= $event->tournamentMatch->team2_id;

        $homeTeamScore = $this->tournamentScoreRepository->findByTeamId($team1Id);
        $awayTeamScore = $this->tournamentScoreRepository->findByTeamId($team2Id);

        $this->tournamentScoreRepository->incrementPlays($team1Id);
        $this->tournamentScoreRepository->incrementPlays($team2Id);

        $goalDifference = $homeGoals - $awayGoals;

        if ($homeGoals == $awayGoals) {
            $this->tournamentScoreRepository->incrementDraws($team1Id);
            $this->tournamentScoreRepository->incrementDraws($team2Id);
        } elseif ($homeGoals > $awayGoals) {
            $this->tournamentScoreRepository->incrementWins($team1Id);
            $this->tournamentScoreRepository->incrementLosess($team2Id);
        } else {
            $this->tournamentScoreRepository->incrementLosess($team1Id);
            $this->tournamentScoreRepository->incrementWins($team2Id);
        }

        $this->tournamentScoreRepository->updateGoalDifferences($team1Id,$homeTeamScore->goal_differences + $goalDifference);
        $this->tournamentScoreRepository->updateGoalDifferences($team2Id,$awayTeamScore->goal_differences - $goalDifference);

    }
}
