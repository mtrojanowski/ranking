<?php
namespace App\Service;

use App\Document\Player;
use App\Document\Ranking;
use App\Document\Result;
use App\Document\Season;
use App\Repository\PlayerRepository;
use App\Repository\ResultsRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RankingServiceTest extends TestCase
{
    private $playerId = '1234-1234';
    private $currentRanking;
    private $season;

    public function setUp()
    {
        $this->currentRanking = new Ranking();
        $this->currentRanking->setPlayerId($this->playerId);

        $season = new Season();
        $season->setLimitOfTournaments(10);
        $season->setLimitOfMasterTournaments(3);
        $season->setLimitOfTeamMasterTournaments(2);
        $this->season = $season;
    }

    public function testShouldCreateInitialRankingEntry()
    {
        $player = new Player();
        $player->setId($this->playerId);
        $player->setAssociation('Ad Astra');
        $player->setFirstName('Michal');

        $playerRepository = $this->getMockBuilder(PlayerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $playerRepository->expects($this->once())
            ->method('find')
            ->with($this->playerId)
            ->willReturn($player);

        $registry = $this->getRegistryMock('App:Player', $playerRepository);

        $rankingService = new RankingService($registry);
        $initialRanking = $rankingService->createInitialRanking($this->playerId);

        $this->assertEquals($this->playerId, $initialRanking->getPlayerId());
        $this->assertEquals(0, $initialRanking->getPoints());
        $this->assertEquals('Ad Astra', $initialRanking->getPlayer()->getAssociation());
        $this->assertEquals('Michal', $initialRanking->getPlayer()->getFirstName());
        $this->assertEquals(0, $initialRanking->getTournamentCount());
        $this->assertEquals([], $initialRanking->getTournamentsIncluded());
    }

    public function testShouldCountRankingPointsForFewerThanMaxTournamentsInSeason()
    {
        $resultsRepository = $this->getResultsRepositoryMock($this->getLocalTournamentsResults(8));
        $registry = $this->getRegistryMock('App:Results', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(800, $newRanking->getPoints());
        $this->assertEquals(8, $newRanking->getTournamentCount());
        $this->assertEquals(['1', '2', '3', '4', '5', '6', '7', '8'], $newRanking->getTournamentsIncluded());
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    public function testShouldCountRankingPointsForMoreThanMaxTournamentsInSeason()
    {
        $resultsRepository = $this->getResultsRepositoryMock($this->getLocalTournamentsResults(12));
        $registry = $this->getRegistryMock('App:Results', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(1000, $newRanking->getPoints());
        $this->assertEquals(10, $newRanking->getTournamentCount());
        $this->assertEquals(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'], $newRanking->getTournamentsIncluded());
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    private function getResultsRepositoryMock(array $return) : MockObject
    {
        $resultsRepository = $this->getMockBuilder(ResultsRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resultsRepository->expects($this->once())
            ->method('findBy')
            ->with(['playerId' => $this->playerId])
            ->willReturn($return);

        return $resultsRepository;
    }

    private function getRegistryMock(string $repositoryName, MockObject $repositoryMock) : MockObject
    {
        $registry = $this->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $registry->expects($this->once())
            ->method('getRepository')
            ->with($repositoryName)
            ->willReturn($repositoryMock);

        return $registry;
    }

    private function getLocalTournamentsResults(int $count) : array
    {
        $results = [];

        for ($i = 1; $i <= $count; $i++) {
            $result = new Result();
            $result->setPoints(100);
            $result->setTournamentId($i);
            $results[] = $result;
        }

        return $results;
    }
}
