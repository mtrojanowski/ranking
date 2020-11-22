<?php
namespace App\Service;

use App\Document\Player;
use App\Document\Ranking;
use App\Document\Result;
use App\Document\Season;
use App\Repository\PlayerRepository;
use App\Repository\ResultsRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RankingServiceTest extends TestCase
{
    private $playerId = '1234-1234';
    private $legacyId = '1234';
    private $currentRanking;
    /** @var Season */
    private $season;

    public function setUp(): void
    {
        $this->currentRanking = new Ranking();
        $this->currentRanking->setPlayerId($this->playerId);
        $this->currentRanking->setArmy("");

        $season = new Season();
        $season->setId('1234');
        $season->setLimitOfTournaments(10);
        $season->setLimitOfMasterTournaments(4);
        $season->setLimitOfTeamMasterTournaments(2);
        $season->setLimitOfPairMasterTournaments(1);
        $this->season = $season;
    }

    public function testShouldCreateInitialRankingEntry()
    {
        $player = new Player();
        $player->setId($this->playerId);
        $player->setLegacyId($this->legacyId);
        $player->setAssociation('Ad Astra');
        $player->setFirstName('Michal');

        $seasonId = "abcdefghij";

        $playerRepository = $this->getMockBuilder(PlayerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $playerRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['legacyId' => $this->legacyId])
            ->willReturn($player);

        $registry = $this->getRegistryMock('App:Player', $playerRepository);

        $rankingService = new RankingService($registry);
        $initialRanking = $rankingService->createInitialRanking($this->legacyId, $seasonId);

        $this->assertEquals($this->legacyId, $initialRanking->getPlayerId());
        $this->assertEquals(0, $initialRanking->getPoints());
        $this->assertEquals('Ad Astra', $initialRanking->getPlayer()->getAssociation());
        $this->assertEquals('Michal', $initialRanking->getPlayer()->getFirstName());
        $this->assertEquals(0, $initialRanking->getTournamentCount());
        $this->assertEquals($seasonId, $initialRanking->getSeasonId());
        $this->assertEquals([], array_keys($initialRanking->getTournamentsIncluded()));
    }

    public function testShouldCountRankingPointsForFewerThanMaxTournamentsInSeason()
    {
        $resultsRepository = $this->getResultsRepositoryMock($this->getLocalTournamentsResults(8));
        $registry = $this->getRegistryMock('App:Result', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(800, $newRanking->getPoints());
        $this->assertEquals(8, $newRanking->getTournamentCount());
        $this->assertEquals(['1', '2', '3', '4', '5', '6', '7', '8'], array_keys($newRanking->getTournamentsIncluded()));
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    public function testShouldCountRankingPointsWithJudgeBonusAsALocalTournament()
    {
        $resultsRepository = $this->getResultsRepositoryMock(
            array_merge(
                $this->getMasterTournamentsResults('single', 4),
                $this->getHeadJudgeBonus(1),
                $this->getLineJudgeBonus(2),
                $this->getLocalTournamentsResults(10))
        );
        $registry = $this->getRegistryMock('App:Result', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(1850, $newRanking->getPoints());
        $this->assertEquals(10, $newRanking->getTournamentCount());
        $this->assertEquals(['101', '102', '103', '104', '301', '401', '402', '1', '2', '3'], array_keys($newRanking->getTournamentsIncluded()));
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    public function testShouldCountRankingPointsWhithJudgeBonusAndMastersOverLimit()
    {
        $resultsRepository = $this->getResultsRepositoryMock(
            array_merge(
                $this->getMasterTournamentsResults('single', 6),
                $this->getHeadJudgeBonus(1),
                $this->getLineJudgeBonus(2),
                $this->getLocalTournamentsResults(10))
        );
        $registry = $this->getRegistryMock('App:Result', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(1850, $newRanking->getPoints());
        $this->assertEquals(10, $newRanking->getTournamentCount());
        $this->assertEquals(['101', '102', '103', '104', '301', '105', '106', '401', '402', '1'], array_keys($newRanking->getTournamentsIncluded()));
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    public function testShouldCountRankingPointsForMoreThanMaxTournamentsInSeason()
    {
        $resultsRepository = $this->getResultsRepositoryMock($this->getLocalTournamentsResults(12));
        $registry = $this->getRegistryMock('App:Result', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(1000, $newRanking->getPoints());
        $this->assertEquals(10, $newRanking->getTournamentCount());
        $this->assertEquals(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'], array_keys($newRanking->getTournamentsIncluded()));
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    public function testShouldCountRankingPointsForLessThanMaxTournamentsInSeasonAndLessThanMaxMasters()
    {
        $resultsRepository = $this->getResultsRepositoryMock(
            array_merge($this->getSingleMasterTournamentsResults(2), $this->getLocalTournamentsResults(7))
        );
        $registry = $this->getRegistryMock('App:Result', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(1300, $newRanking->getPoints());
        $this->assertEquals(9, $newRanking->getTournamentCount());
        $this->assertEquals(['101', '102', '1', '2', '3', '4', '5', '6', '7'], array_keys($newRanking->getTournamentsIncluded()));
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    public function testShouldCountRankingPointsForLessThanMaxTournamentsInSeasonAndMoreThanMaxSinglesMasters()
    {
        $resultsRepository = $this->getResultsRepositoryMock(
            array_merge($this->getSingleMasterTournamentsResults(6), $this->getLocalTournamentsResults(3))
        );
        $registry = $this->getRegistryMock('App:Result', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(1700, $newRanking->getPoints());
        $this->assertEquals(9, $newRanking->getTournamentCount());
        $this->assertEquals(['101', '102', '103', '104', '105', '106', '1', '2', '3'], array_keys($newRanking->getTournamentsIncluded()));
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    public function testShouldCountRankingPointsForMoreThanMaxTournamentsInSeasonAndOnlyBestSingleMasters()
    {
        $resultsRepository = $this->getResultsRepositoryMock(
            array_merge(
                $this->getSingleMasterTournamentsResults(3),
                $this->getLocalTournamentsResults(7),
                $this->getSingleMasterTournamentsResults(3, 20)
            )
        );
        $registry = $this->getRegistryMock('App:Result', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(1600, $newRanking->getPoints());
        $this->assertEquals(10, $newRanking->getTournamentCount());
        $this->assertEquals(['101', '102', '103', '1', '2', '3', '4', '5', '6', '7'], array_keys($newRanking->getTournamentsIncluded()));
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    public function testShouldCountRankingPointsForMoreThanMaxTournamentsInSeasonAndDividingPointsForOtherMastersByThree()
    {
        $resultsRepository = $this->getResultsRepositoryMock(
            array_merge(
                $this->getSingleMasterTournamentsResults(4),
                $this->getSingleMasterTournamentsResults(2, 270, 10000),
                $this->getLocalTournamentsResults(5),
                $this->getLocalTournamentsResults(1, 60)

            )
        );
        $registry = $this->getRegistryMock('App:Result', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(1790, $newRanking->getPoints());
        $this->assertEquals(10, $newRanking->getTournamentCount());
        $this->assertEquals(['101', '102', '103', '104', '1', '2', '3', '4', '5', '10101'], array_keys($newRanking->getTournamentsIncluded()));
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    public function testShouldCountRankingPointsForMoreThanMaxTournamentsInSeasonAndLessThanMaxMastersAndMoreThanMaxTeamMasters()
    {
        $resultsRepository = $this->getResultsRepositoryMock(
            array_merge(
                $this->getSingleMasterTournamentsResults(1),
                $this->getTeamMasterTournamentsResults(3),
                $this->getLocalTournamentsResults(8)
            )
        );
        $registry = $this->getRegistryMock('App:Result', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(1600, $newRanking->getPoints());
        $this->assertEquals(10, $newRanking->getTournamentCount());
        $this->assertEquals(['101', '201', '202', '203', '1', '2', '3', '4', '5', '6'], array_keys($newRanking->getTournamentsIncluded()));
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    public function testShouldCountRankingPointsForMoreThanMaxTournamentsInSeasonAndLessThanMaxMastersAndMoreThanMaxPairMasters()
    {
        $resultsRepository = $this->getResultsRepositoryMock(
            array_merge(
                $this->getSingleMasterTournamentsResults(1),
                $this->getPairMasterTournamentsResults(3),
                $this->getLocalTournamentsResults(8)
            )
        );
        $registry = $this->getRegistryMock('App:Result', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(1400, $newRanking->getPoints());
        $this->assertEquals(10, $newRanking->getTournamentCount());
        $this->assertEquals(['101', '501', '502', '503', '1', '2', '3', '4', '5', '6'], array_keys($newRanking->getTournamentsIncluded()));
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    public function testShouldCountRankingPointsForMoreThanMaxTournamentsInSeasonAndMoreThanMaxMastersAndMoreThanMaxTeamMasters()
    {
        $resultsRepository = $this->getResultsRepositoryMock(
            array_merge(
                $this->getSingleMasterTournamentsResults(1),
                $this->getTeamMasterTournamentsResults(3),
                $this->getLocalTournamentsResults(3),
                $this->getSingleMasterTournamentsResults(3, 90, 10000),
                $this->getLocalTournamentsResults(5, 20)
            )
        );
        $registry = $this->getRegistryMock('App:Result', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(1450, $newRanking->getPoints());
        $this->assertEquals(10, $newRanking->getTournamentCount());
        $this->assertEquals(['101', '201', '202', '203', '1', '2', '3', '10101', '10102', '10103'], array_keys($newRanking->getTournamentsIncluded()));
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    public function testShouldCountRankingPointsForMoreThanMaxTournamentsInSeasonAndMoreThanMaxMastersAndMoreThanMaxPairMasters()
    {
        $resultsRepository = $this->getResultsRepositoryMock(
            array_merge(
                $this->getSingleMasterTournamentsResults(1),
                $this->getPairMasterTournamentsResults(3),
                $this->getTeamMasterTournamentsResults(3),
                $this->getLocalTournamentsResults(3),
                $this->getSingleMasterTournamentsResults(3, 100),
                $this->getLocalTournamentsResults(5)
            )
        );
        $registry = $this->getRegistryMock('App:Result', $resultsRepository);

        $rankingService = new RankingService($registry);
        $newRanking = $rankingService->recalculateRanking($this->currentRanking, $this->season);

        $this->assertEquals(1800, $newRanking->getPoints());
        $this->assertEquals(10, $newRanking->getTournamentCount());
        $this->assertEquals(['101', '501', '201', '202', '203', '502', '503', '1', '2', '3'], array_keys($newRanking->getTournamentsIncluded()));
        $this->assertEquals($this->playerId, $newRanking->getPlayerId());
    }

    private function getResultsRepositoryMock(array $return) : MockObject
    {
        $resultsRepository = $this->getMockBuilder(ResultsRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resultsRepository->expects($this->once())
            ->method('getPlayersResults')
            ->with($this->playerId, $this->season->getId())
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

    private function getSingleMasterTournamentsResults(int $count, int $points = 300, $idprefix = 0) : array
    {
        return $this->getMasterTournamentsResults('single', $count, $points, $idprefix);
    }

    private function getTeamMasterTournamentsResults(int $count, int $points = 300) : array
    {
        return $this->getMasterTournamentsResults('team', $count, $points);
    }

    private function getPairMasterTournamentsResults(int $count, int $points = 300) : array
    {
        return $this->getMasterTournamentsResults('double', $count, $points);
    }

    private function getMasterTournamentsResults($type, int $count, int $points = 300, $idprefix = 0) : array
    {
        return $this->getTournamentsResults($count, 'master', $type, $points, 0, $idprefix);
    }

    private function getLocalTournamentsResults(int $count, int $points = 100) : array
    {
        return $this->getTournamentsResults($count, 'local', 'single',$points, 0);
    }

    private function getLineJudgeBonus(int $count) : array
    {
        return $this->getTournamentsResults($count, 'master', 'single', 100, 2);
    }

    private function getHeadJudgeBonus(int $count) : array
    {
        return $this->getTournamentsResults($count, 'master', 'single', 150, 1);
    }

    private function getTournamentsResults(int $count, string $rank, string $type, int $points, int $judge, $idPrefix = 0) : array
    {
        $results = [];

        for ($i = 1; $i <= $count; $i++) {
            $idModifier = 0;
            if ($rank == 'master') {
                if ($type == 'single') {
                    $idModifier = 100;
                } elseif ($type == 'double') {
                    $idModifier = 500;
                } else {
                    $idModifier = 200;
                }
            }

            if ($judge == 1) {
                $idModifier = 300;
            }

            if ($judge == 2) {
                $idModifier = 400;
            }

            $idModifier += $idPrefix;

            $result = new Result();
            $result->setPoints($points);
            $result->setTournamentId($i + $idModifier);
            $result->setTournamentRank($rank);
            $result->setTournamentType($type);
            $result->setJudge($judge);
            $results[] = $result;
        }

        return $results;
    }

}
