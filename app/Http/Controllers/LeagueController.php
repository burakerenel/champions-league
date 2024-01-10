<?php

namespace App\Http\Controllers;

use App\Interfaces\TeamRepositoryInterface;
use App\Interfaces\FixtureRepositoryInterface;
use App\Interfaces\TournamentMatchInterface;
use App\Interfaces\TournamentScoreInterface;
use App\Repositories\FixtureRepository;
use App\Repositories\TeamRepository;
use App\Repositories\TournamentMatchRepository;
use App\Repositories\TournamentScoreRepository;
use Inertia\Inertia;
use Inertia\Response;

class LeagueController extends Controller
{
    private TeamRepositoryInterface $teamRepository;
    private FixtureRepositoryInterface $fixtureRepository;
    private TournamentMatchInterface $tournamentMatchRepository;
    private TournamentScoreInterface $tournamentScoreRepository;

    public function __construct(TeamRepository $teamRepository, FixtureRepository $fixtureRepository, TournamentMatchRepository $tournamentMatchRepository, TournamentScoreRepository $tournamentScoreRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->fixtureRepository = $fixtureRepository;
        $this->tournamentMatchRepository = $tournamentMatchRepository;
        $this->tournamentScoreRepository = $tournamentScoreRepository;
    }

    public function getTeamRepository(): TeamRepositoryInterface
    {
        return $this->teamRepository;
    }

    public function getFixtureRepository(): FixtureRepositoryInterface
    {
        return $this->fixtureRepository;
    }

    public function getTournamentMatchRepository(): TournamentMatchInterface
    {
        return $this->tournamentMatchRepository;
    }

    public function getTournamentScoreRepository(): TournamentScoreInterface
    {
        return $this->tournamentScoreRepository;
    }

    public function tournamentTeams(): Response
    {
        $teams = $this->teamRepository->getAll();
        return Inertia::render('TournamentTeams', ['teams' => collect($teams)]);
    }

    public function fixtureWeeks($teams)
    {
        $totalRounds = count($teams) - 1;
        $matchesPerRound = count($teams) / 2;
        $fixtures = [];

        for ($round = 0; $round < $totalRounds * 2; $round++) {
            foreach ($teams as $key => $team) {
                if ($key >= $matchesPerRound) {
                    break;
                }

                $home = $round < $totalRounds ? $team : $teams[$key + $matchesPerRound];
                $away = $round < $totalRounds ? $teams[$key + $matchesPerRound] : $team;

                if ($round > 0 && isset($fixtures[$round - 1])) {
                    $lastRoundHomeTeams = array_slice(array_column($fixtures[$round - 1], 0), 0, count($fixtures[$round - 1]));
                    if (in_array($home, $lastRoundHomeTeams)) {
                        [$home, $away] = [$away, $home];
                    }
                }
                if ($home !== null && $away !== null) {
                    $fixtures[$round][] = [$home, $away];
                }
            }
            $item = array_splice($teams, 1, 1);
            array_unshift($teams, array_pop($teams));
            array_splice($teams, 1, 0, $item);
        }

        return $fixtures;
    }

    public function generateFixtures()
    {
        $this->fixtureRepository->deleteAll();
        $this->tournamentScoreRepository->deleteAll();
        $this->tournamentMatchRepository->deleteAll();

        $teams = collect($this->teamRepository->getAll());

        $teamIdArray = $teams
            ->map(function ($item) {
                return $item['id'];
            })
            ->toArray();

        $fixtureWeeks = $this->fixtureWeeks($teamIdArray);

        foreach ($fixtureWeeks as $week => $matches) {
            for ($i = 0; $i < 2; $i++) {
                $this->fixtureRepository->create(['team1_id' => $matches[$i][0], 'team2_id' => $matches[$i][1], 'week' => $week + 1]);
            }
        }

        foreach ($teamIdArray as $team) {
            $this->tournamentScoreRepository->create(['team_id' => $team, 'plays' => 0, 'wins' => 0, 'draws' => 0, 'losses' => 0, 'goal_differences' => 0]);
        }

        return response(['status' => true, 'message' => 'Generated.', 'data' => $fixtureWeeks], 200);
    }

    public function generatedFixtures(): Response
    {
        $fixtures = $this->fixtureRepository->getAll();
        return Inertia::render('GeneratedFixtures', ['fixtures' => collect($fixtures)]);
    }

    public function getLastPlayedWeek()
    {
        $getLastPlayedWeek = $this->tournamentMatchRepository->getLastMatch();
        $lastweek = 0;
        if ($getLastPlayedWeek) {
            $lastweek = $getLastPlayedWeek->week;
        }
        return $lastweek;
    }

    public function simulation()
    {
        $tournamentScores = $this->tournamentScoreRepository->getAll();

        $lastweek = $this->getLastPlayedWeek() + 1;
        if ($lastweek > 6) {
            $lastweek = 6;
        }
        $currentWeekFixture = $this->fixtureRepository->getWeekFixture($lastweek);

        $champignshipProdictions = $tournamentScores->map(function ($score) {
            return ['team' => $score->team->name, 'prodiction' => $score->prodiction];
        });
        return Inertia::render('Simulation', ['tournamentScores' => collect($tournamentScores), 'currentWeek' => $lastweek, 'currentWeekFixture' => collect($currentWeekFixture), 'champignshipProdictions' => $champignshipProdictions]);
    }

    private function simulateGoals($probability)
    {
        $randomNumber = mt_rand() / mt_getrandmax();
        $goals = 0;
        if ($randomNumber < $probability) {
            $goals = rand(1, 4);
        }

        return $goals;
    }

    public function playNextWeek()
    {
        $lastweek = $this->getLastPlayedWeek() + 1;

        if ($lastweek > 6) {
            return response(['status' => true, 'message' => 'Tournament finished.', 'data' => null], 200);
        }

        $getWeekMatches = $this->fixtureRepository->getWeekFixture($lastweek);

        foreach ($getWeekMatches as $match) {
            $homeTeam = $this->teamRepository->findById($match->team1_id);
            $awayTeam = $this->teamRepository->findById($match->team2_id);

            $homeGoalProbability = $homeTeam->power * 0.15;
            $awayGoalProbability = $awayTeam->power * 0.1;

            $homeGoals = $this->simulateGoals($homeGoalProbability);
            $awayGoals = $this->simulateGoals($awayGoalProbability);

            $this->tournamentMatchRepository->create(['team1_id' => $match->team1_id, 'team2_id' => $match->team2_id, 'week' => $lastweek, 'home_goals' => $homeGoals, 'away_goals' => $awayGoals]);
        }
        return response(['status' => true, 'message' => 'Played.', 'data' => null], 200);
    }

    public function playAllWeeks()
    {
        for ($i = 1; $i <= 6; $i++) {
            $this->playNextWeek();
        }

        return response(['status' => true, 'message' => 'Played all weeks.', 'data' => null], 200);
    }

    public function resetData()
    {
        $this->fixtureRepository->deleteAll();
        $this->tournamentScoreRepository->deleteAll();
        $this->tournamentMatchRepository->deleteAll();
        return response(['status' => true, 'message' => 'Reset data', 'data' => null], 200);
    }
}
