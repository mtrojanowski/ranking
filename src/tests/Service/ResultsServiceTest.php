<?php
namespace App\Service;

use App\Document\Tournament;
use App\Tests\Service\TestsDataProvider;
use PHPUnit\Framework\TestCase;

class ResultsServiceTest extends TestCase
{
    public function testShouldCreateResultsForLocalSinglesTournament()
    {
        $tournament = new Tournament();
        $tournament->setRank("local");
        $tournament->setType("single");
        $tournament->setPlayersInTeam(1);

        $service = new ResultsService();

        $testData = TestsDataProvider::getSmallSinglesLocalTestData();
        $tournamentResults = $testData['input'];
        $expectedResults = $testData['expectedResult'];

        $results = $service->createTournamentResults($tournament, $tournamentResults);

        $this->assertEquals(7, count($results));

        foreach ($results as $i => $result) {
            $this->assertEquals($expectedResults[$i]->getTournamentId(), $result->getTournamentId());
            $this->assertEquals($expectedResults[$i]->getPlayerId(), $result->getPlayerId());
            $this->assertEquals($expectedResults[$i]->getPlace(), $result->getPlace());
            $this->assertEquals($expectedResults[$i]->getArmy(), $result->getArmy());
            $this->assertEquals($expectedResults[$i]->getPoints(), $result->getPoints());
        }

    }
}
