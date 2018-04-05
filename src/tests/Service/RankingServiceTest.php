<?php
namespace App\Service;

use App\Document\Player;
use App\Repository\PlayerRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class RankingServiceTest extends TestCase
{
    private $playerId = '1234-1234';

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

        $registry = $this->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();
        $registry
            ->expects($this->exactly(1))
            ->method('getRepository')
            ->withConsecutive(['App:Player'])
            ->willReturnOnConsecutiveCalls($playerRepository);

        $rankingService = new RankingService($registry);

        $initialRanking = $rankingService->createInitialRanking($this->playerId);

        $this->assertEquals($this->playerId, $initialRanking->getPlayerId());
        $this->assertEquals(0, $initialRanking->getPoints());
        $this->assertEquals('Ad Astra', $initialRanking->getPlayer()->getAssociation());
        $this->assertEquals('Michal', $initialRanking->getPlayer()->getFirstName());
        $this->assertEquals(0, $initialRanking->getTournamentCount());
        $this->assertEquals([], $initialRanking->getTournamentsIncluded());
    }
}
