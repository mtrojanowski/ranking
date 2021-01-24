<?php
namespace App\Service;

use App\Controller\dto\Result;
use App\Controller\dto\TournamentResults;
use App\Document\Player;
use App\Document\Ranking;
use App\Document\Season;
use App\Exception\IncorrectPlayersException;
use App\Repository\PlayerRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TournamentsServiceTest extends TestCase
{

    public function testShouldThrowExceptionWhenPlayersMissingFromDB()
    {
        $playerRepository = $this->getMockBuilder(PlayerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $playerRepository->expects($this->once())
            ->method('getPlayersIds')
            ->with(["123", "456", "789"])
            ->willReturn([["legacyId" => "123"], ["legacyId" => "456"]]);

        $registry = $this->getRegistryMock('App:Player', $playerRepository);

        $tournamentService = new TournamentsService($registry);

        $resultPlace1 = new Result();
        $resultPlace1->setPlace(1);
        $resultPlace1->setPlayerId("123");
        $resultPlace1->setArmy("DE");
        $resultPlace1->setJudge(0);

        $resultPlace2 = new Result();
        $resultPlace2->setPlace(2);
        $resultPlace2->setPlayerId("456");
        $resultPlace2->setArmy("VC");
        $resultPlace2->setJudge(0);

        $resultPlace3 = new Result();
        $resultPlace3->setPlace(3);
        $resultPlace3->setPlayerId("789");
        $resultPlace3->setArmy("SE");
        $resultPlace3->setJudge(0);

        $results = new TournamentResults();
        $results->setTournamentId("1234");
        $results->setResults([
            $resultPlace1,
            $resultPlace2,
            $resultPlace3
        ]);

        $expectedException = new IncorrectPlayersException(["789"], []);
        $this->expectException(IncorrectPlayersException::class);
        $this->expectExceptionObject($expectedException);
        $tournamentService->verifyTournamentPlayers($results);
    }

    public function testShouldThrowExceptionWhenPlayersDuplicatedInResults()
    {
        $playerRepository = $this->getMockBuilder(PlayerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $playerRepository->expects($this->once())
            ->method('getPlayersIds')
            ->with(["123", "456", "456"])
            ->willReturn([["legacyId" => "123"], ["legacyId" => "456"]]);

        $registry = $this->getRegistryMock('App:Player', $playerRepository);

        $tournamentService = new TournamentsService($registry);

        $resultPlace1 = new Result();
        $resultPlace1->setPlace(1);
        $resultPlace1->setPlayerId("123");
        $resultPlace1->setArmy("DE");
        $resultPlace1->setJudge(0);

        $resultPlace2 = new Result();
        $resultPlace2->setPlace(2);
        $resultPlace2->setPlayerId("456");
        $resultPlace2->setArmy("VC");
        $resultPlace2->setJudge(0);

        $resultPlace3 = new Result();
        $resultPlace3->setPlace(3);
        $resultPlace3->setPlayerId("456");
        $resultPlace3->setArmy("SE");
        $resultPlace3->setJudge(0);

        $results = new TournamentResults();
        $results->setTournamentId("1234");
        $results->setResults([
            $resultPlace1,
            $resultPlace2,
            $resultPlace3
        ]);

        $expectedException = new IncorrectPlayersException([], ["456"]);
        $this->expectException(IncorrectPlayersException::class);
        $this->expectExceptionObject($expectedException);
        $tournamentService->verifyTournamentPlayers($results);
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
}
