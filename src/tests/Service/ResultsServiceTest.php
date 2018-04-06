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

    public function testShouldCreateResultsForVeryLargeLocalSinglesTournament()
    {
        $tournament = $this->getTournament("local", "single", 1);
        $testData = TestsDataProvider::getVeryLargeSinglesLocalTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForSmallLocalThreePlayerTeamTournament()
    {
        $tournament = $this->getTournament("local", "team", 3);
        $testData = TestsDataProvider::getSmallThreePlayerTeamLocalTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeLocalThreePlayerTeamTournament()
    {
        $tournament = $this->getTournament("local", "team", 3);
        $testData = TestsDataProvider::getLargeThreePlayerTeamLocalTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeLocalFivePlayerTeamTournament()
    {
        $tournament = $this->getTournament("local", "team", 5);
        $testData = TestsDataProvider::getLargeFivePlayerTeamLocalTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForSmallSinglesMasterTournament()
    {
        $tournament = $this->getTournament("master", "single", 1);
        $testData = TestsDataProvider::getSmallSingleMasterTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeSinglesMasterTournament()
    {
        $tournament = $this->getTournament("master", "single", 1);
        $testData = TestsDataProvider::getLargeSingleMasterTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForVeryLargeSinglesMasterTournament()
    {
        $tournament = $this->getTournament("master", "single", 1);
        $testData = TestsDataProvider::getVeryLargeSingleMasterTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForSmallThreePlayerTeamMasterTournament()
    {
        $tournament = $this->getTournament("master", "team", 3);
        $testData = TestsDataProvider::getSmallThreePlayerTeamMasterTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeThreePlayerTeamMasterTournament()
    {
        $tournament = $this->getTournament("master", "team", 3);
        $testData = TestsDataProvider::getLargeThreePlayerTeamMasterTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForVeryLargeThreePlayerTeamMasterTournament()
    {
        $tournament = $this->getTournament("master", "team", 3);
        $testData = TestsDataProvider::getVeryLargeThreePlayerTeamMasterTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForSmallFivePlayerTeamMasterTournament()
    {
        $tournament = $this->getTournament("master", "team", 5);
        $testData = TestsDataProvider::getSmallFivePlayerTeamMasterTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeFivePlayerTeamMasterTournament()
    {
        $tournament = $this->getTournament("master", "team", 5);
        $testData = TestsDataProvider::getLargeFivePlayerTeamMasterTestData();

        $service = new ResultsService();
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForVeryLargeFivePlayerTeamMasterTournament()
    {
        $tournament = $this->getTournament("master", "team", 5);
        $testData = TestsDataProvider::getVeryLargeFivePlayerTeamMasterTestData();

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
            $this->assertEquals($expectedResults[$i]->getTournamentRank(), $result->getTournamentRank());
            $this->assertEquals($expectedResults[$i]->getTournamentType(), $result->getTournamentType());
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