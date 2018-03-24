<?php
namespace App\Service;

use App\Controller\dto\Result;
use App\Controller\dto\TournamentResults;
use App\Document\Tournament;
use PHPUnit\Framework\TestCase;

class ResultsServiceTest extends TestCase
{
    public function testShouldCreateResultsForLocalSinglesTournament()
    {
        $tournamentId = "1234";

        $tournament = new Tournament();
        $tournament->setRank("local");
        $tournament->setType("single");
        $tournament->setPlayersInTeam(1);

        $service = new ResultsService();
        $result1 = new Result();
        $result1->setPlace(1);
        $result1->setPlayerId("123");
        $result1->setArmy("OG");

        $result2 = new Result();
        $result2->setPlace(2);
        $result2->setPlayerId("2222");
        $result2->setArmy("VC");

        $tournamentResults = new TournamentResults();
        $tournamentResults->setTournamentId($tournamentId);
        $tournamentResults->setResults([ $result1, $result2 ]);

        $expectedResult1 = new \App\Document\Result();
        $expectedResult1->setTournamentId($tournamentId);
        $expectedResult1->setPlayerId("123");
        $expectedResult1->setPlace(1);
        $expectedResult1->setArmy("OG");
        $expectedResult1->setPoints(20);

        $expectedResult2 = new \App\Document\Result();
        $expectedResult2->setTournamentId($tournamentId);
        $expectedResult2->setPlayerId("2222");
        $expectedResult2->setPlace(2);
        $expectedResult2->setArmy("VC");
        $expectedResult2->setPoints(1);

        $expectedResults = [$expectedResult1, $expectedResult2];

        $results = $service->createTournamentResults($tournament, $tournamentResults);

        $this->assertEquals(2, count($results));

        foreach ($results as $i => $result) {
            $this->assertEquals($expectedResults[$i]->getTournamentId(), $result->getTournamentId());
            $this->assertEquals($expectedResults[$i]->getPlayerId(), $result->getPlayerId());
            $this->assertEquals($expectedResults[$i]->getPlace(), $result->getPlace());
            $this->assertEquals($expectedResults[$i]->getArmy(), $result->getArmy());
            $this->assertEquals($expectedResults[$i]->getPoints(), $result->getPoints());
        }

    }
}
