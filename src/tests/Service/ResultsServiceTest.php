<?php
namespace App\Service;

use App\Document\Tournament;
use App\Tests\Service\TestsDataProvider;
use PHPUnit\Framework\TestCase;

class ResultsServiceTest extends TestCase
{
    public function testShouldCreateResultsForLocalSinglesTournament()
    {
        $tournament = $this->getTournament("local", "single", 1);
        $testData = TestsDataProvider::getSmallSinglesLocalTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeLocalSinglesTournament()
    {
        $tournament = $this->getTournament("local", "single", 1);
        $testData = TestsDataProvider::getSmallSinglesLocalTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    private function verifyResults($results, $expectedResults)
    {
        $this->assertEquals(count($expectedResults), count($results));

        foreach ($results as $i => $result) {
            $this->assertEquals($expectedResults[$i]->getTournamentId(), $result->getTournamentId());
            $this->assertEquals($expectedResults[$i]->getPlayerId(), $result->getPlayerId());
            $this->assertEquals($expectedResults[$i]->getPlace(), $result->getPlace());
            $this->assertEquals($expectedResults[$i]->getArmy(), $result->getArmy());
            $this->assertEquals($expectedResults[$i]->getPoints(), $result->getPoints());
        }
    }

    private function getTournament($rank, $type, $playersInTeam) : Tournament
    {
        $tournament = new Tournament();
        $tournament->setRank($rank);
        $tournament->setType($type);
        $tournament->setPlayersInTeam($playersInTeam);

        return $tournament;
    }
}