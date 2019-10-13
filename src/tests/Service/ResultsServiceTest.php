<?php
namespace App\Service;

use App\Controller\dto\Result;
use App\Controller\dto\TournamentResults;
use App\Document\Tournament;
use App\Exception\HeadJudgeBonusException;
use App\Repository\ResultsRepository;
use App\Tests\Service\TestsDataProvider;
use Doctrine\Common\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class ResultsServiceTest extends TestCase
{
    public function testShouldCreateResultsForLocalSinglesTournament()
    {
        $tournament = $this->getTournament("local", "single", 1);
        $testData = TestsDataProvider::getSmallSinglesLocalTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeLocalSinglesTournament()
    {
        $tournament = $this->getTournament("local", "single", 1);
        $testData = TestsDataProvider::getSmallSinglesLocalTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForVeryLargeLocalSinglesTournament()
    {
        $tournament = $this->getTournament("local", "single", 1);
        $testData = TestsDataProvider::getVeryLargeSinglesLocalTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeDoublesLocalTournament()
    {
        $tournament = $this->getTournament("local", "double", 2);
        $testData = TestsDataProvider::getLargeDoubleLocalTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForSmallLocalThreePlayerTeamTournament()
    {
        $tournament = $this->getTournament("local", "team", 3);
        $testData = TestsDataProvider::getSmallThreePlayerTeamLocalTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeLocalThreePlayerTeamTournament()
    {
        $tournament = $this->getTournament("local", "team", 3);
        $testData = TestsDataProvider::getLargeThreePlayerTeamLocalTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeLocalFivePlayerTeamTournament()
    {
        $tournament = $this->getTournament("local", "team", 5);
        $testData = TestsDataProvider::getLargeFivePlayerTeamLocalTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForSmallSinglesMasterTournament()
    {
        $tournament = $this->getTournament("master", "single", 1);
        $testData = TestsDataProvider::getSmallSingleMasterTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeSinglesMasterTournament()
    {
        $tournament = $this->getTournament("master", "single", 1);
        $testData = TestsDataProvider::getLargeSingleMasterTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeDoublesMasterTournament()
    {
        $tournament = $this->getTournament("master", "double", 2);
        $testData = TestsDataProvider::getLargeDoubleMasterTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForVeryLargeSinglesMasterTournament()
    {
        $tournament = $this->getTournament("master", "single", 1);
        $testData = TestsDataProvider::getVeryLargeSingleMasterTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForSmallThreePlayerTeamMasterTournament()
    {
        $tournament = $this->getTournament("master", "team", 3);
        $testData = TestsDataProvider::getSmallThreePlayerTeamMasterTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeThreePlayerTeamMasterTournament()
    {
        $tournament = $this->getTournament("master", "team", 3);
        $testData = TestsDataProvider::getLargeThreePlayerTeamMasterTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForVeryLargeThreePlayerTeamMasterTournament()
    {
        $tournament = $this->getTournament("master", "team", 3);
        $testData = TestsDataProvider::getVeryLargeThreePlayerTeamMasterTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForSmallFivePlayerTeamMasterTournament()
    {
        $tournament = $this->getTournament("master", "team", 5);
        $testData = TestsDataProvider::getSmallFivePlayerTeamMasterTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForLargeFivePlayerTeamMasterTournament()
    {
        $tournament = $this->getTournament("master", "team", 5);
        $testData = TestsDataProvider::getLargeFivePlayerTeamMasterTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldCreateResultsForVeryLargeFivePlayerTeamMasterTournament()
    {
        $tournament = $this->getTournament("master", "team", 5);
        $testData = TestsDataProvider::getVeryLargeFivePlayerTeamMasterTestData();

        $service = new ResultsService($this->getManagerRegistry());
        $results = $service->createTournamentResults($tournament, $testData['input']);

        $this->verifyResults($results, $testData['expectedResult']);
    }

    public function testShouldThrowErrorWhenMoreThanOneHeadJudgeBonusUsedInASeason()
    {
        $tournament = $this->getTournament('master', 'single', 1);
        $testData = TestsDataProvider::getLargeSingleMasterTestData();

        /** @var TournamentResults $results */
        $results  = $testData['input'];
        $tournamentResults = $results->getResults();
        $headJudgeResult = new Result();
        $headJudgeResult->setPlace(0);
        $headJudgeResult->setPlayerId(1000);
        $headJudgeResult->setJudge(1);
        $tournamentResults[] = $headJudgeResult;
        $results->setResults($tournamentResults);

        $testData['input'] = $results;

        $resultsRepository = $this->createMock(ResultsRepository::class);
        $resultsRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['playerId' => 1000, 'seasonId' => "1234", 'judge' => 1])
            ->willReturn([new Result()]);


        $managerRegistry = $this->getManagerRegistry();
        $managerRegistry->expects($this->once())
            ->method('getRepository')
            ->willReturn($resultsRepository);

        $service = new ResultsService($managerRegistry);
        $this->expectException(HeadJudgeBonusException::class);

        $service->createTournamentResults($tournament, $testData['input']);
    }


    public function testShouldCreateResultsMasterWithJudgesTournament()
    {
        $tournament = $this->getTournament("master", "single", 1);
        $testData = TestsDataProvider::getLargeSingleMasterTestData();
        $headJudge = new Result();
        $headJudge->setPlace(0);
        $headJudge->setPlayerId(7000);
        $headJudge->setArmy("");
        $headJudge->setJudge(1);


        $lineJudge = new Result();
        $lineJudge->setPlace(0);
        $lineJudge->setPlayerId(7002);
        $lineJudge->setArmy("");
        $lineJudge->setJudge(2);

        $testResults = $testData['input']->getResults();
        $testResults[] = $headJudge;
        $testResults[] = $lineJudge;
        $testData['input']->setResults($testResults);

        /** @var \App\Document\Result $aResult */
        $aResult = $testData['expectedResult'][0];
        $newHeadJudgeResult = new \App\Document\Result();
        $newHeadJudgeResult->setTournamentId($aResult->getTournamentId());
        $newHeadJudgeResult->setPlayerId(7000);
        $newHeadJudgeResult->setArmy("");
        $newHeadJudgeResult->setPlace(0);
        $newHeadJudgeResult->setPoints(150);
        $newHeadJudgeResult->setTournamentType('single');
        $newHeadJudgeResult->setTournamentRank('master');
        $newHeadJudgeResult->setJudge(1);

        $newLineJudgeResult = new \App\Document\Result();
        $newLineJudgeResult->setTournamentId($aResult->getTournamentId());
        $newLineJudgeResult->setPlayerId(7002);
        $newLineJudgeResult->setArmy("");
        $newLineJudgeResult->setPlace(0);
        $newLineJudgeResult->setPoints(100);
        $newLineJudgeResult->setTournamentType('single');
        $newLineJudgeResult->setTournamentRank('master');
        $newLineJudgeResult->setJudge(2);

        $testData['expectedResult'][] = $newHeadJudgeResult;
        $testData['expectedResult'][] = $newLineJudgeResult;

        $resultsRepository = $this->createMock(ResultsRepository::class);
        $resultsRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['playerId' => 7000, 'seasonId' => '1234', 'judge' => 1])
            ->willReturn([]);


        $managerRegistry = $this->getManagerRegistry();
        $managerRegistry->expects($this->once())
            ->method('getRepository')
            ->willReturn($resultsRepository);

        $service = new ResultsService($managerRegistry);
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
            $this->assertEquals($expectedResults[$i]->getJudge(), $result->getJudge());
        }
    }

    private function getTournament($rank, $type, $playersInTeam) : Tournament
    {
        $tournament = new Tournament();
        $tournament->setRank($rank);
        $tournament->setType($type);
        $tournament->setPlayersInTeam($playersInTeam);
        $tournament->setSeason('1234');

        return $tournament;
    }

    private function getManagerRegistry()
    {
        return $this->createMock(ManagerRegistry::class);
    }
}