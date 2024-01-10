<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\LeagueController;
use App\Repositories\FixtureRepository;
use App\Repositories\TeamRepository;
use App\Repositories\TournamentMatchRepository;
use App\Repositories\TournamentScoreRepository;

class LeagueTest extends TestCase
{
    public function testConstructor()
    {
        $teamRepositoryMock = $this->getMockBuilder(TeamRepository::class)->getMock();
        $fixtureRepositoryMock = $this->getMockBuilder(FixtureRepository::class)->getMock();
        $tournamentMatchRepositoryMock = $this->getMockBuilder(TournamentMatchRepository::class)->getMock();
        $tournamentScoreRepositoryMock = $this->getMockBuilder(TournamentScoreRepository::class)->getMock();

        $controller = new LeagueController($teamRepositoryMock, $fixtureRepositoryMock, $tournamentMatchRepositoryMock, $tournamentScoreRepositoryMock);

        $this->assertInstanceOf(TeamRepository::class, $controller->getTeamRepository());
        $this->assertInstanceOf(FixtureRepository::class, $controller->getFixtureRepository());
        $this->assertInstanceOf(TournamentMatchRepository::class, $controller->getTournamentMatchRepository());
        $this->assertInstanceOf(TournamentScoreRepository::class, $controller->getTournamentScoreRepository());
    }


    public function testFixtureWeeks()
    {
        $teamRepositoryMock = $this->getMockBuilder(TeamRepository::class)->getMock();
        $fixtureRepositoryMock = $this->getMockBuilder(FixtureRepository::class)->getMock();
        $tournamentMatchRepositoryMock = $this->getMockBuilder(TournamentMatchRepository::class)->getMock();
        $tournamentScoreRepositoryMock = $this->getMockBuilder(TournamentScoreRepository::class)->getMock();

        $teams = [1, 2, 3, 4];
        $leagueController = new LeagueController($teamRepositoryMock, $fixtureRepositoryMock, $tournamentMatchRepositoryMock, $tournamentScoreRepositoryMock);
        $fixtures = $leagueController->fixtureWeeks($teams);

        $this->assertIsArray($fixtures);

        foreach ($fixtures as $round => $matches) {
            $this->assertIsArray($matches);
            $this->assertCount(count($teams) / 2, $matches);

            $playedHomeTeams = [];
            foreach ($matches as [$home, $away]) {
                $this->assertContains($home, $teams);
                $this->assertContains($away, $teams);
                $this->assertNotContains($home, $playedHomeTeams);
                $playedHomeTeams[] = $home;
            }
        }
    }

}
