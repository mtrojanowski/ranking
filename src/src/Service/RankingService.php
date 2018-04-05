<?php
namespace App\Service;


use App\Document\Player;
use App\Document\Ranking;
use App\Document\RankingPlayer;
use App\Exception\PlayerNotFoundException;
use Doctrine\Common\Persistence\ManagerRegistry;

class RankingService
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function recalculateRanking(Ranking $currentRanking) : Ranking
    {

    }

    public function createInitialRanking($playerId) : Ranking
    {
        $playerRepository = $this->managerRegistry->getRepository('App:Player');
        /** @var Player $player */
        $player = $playerRepository->find($playerId);

        if (!$player) {
            throw new PlayerNotFoundException($playerId);
        }

        $rankingPlayer = new RankingPlayer();
        $rankingPlayer->setFirstName($player->getFirstName());
        $rankingPlayer->setName($player->getName());
        $rankingPlayer->setNickname($player->getNickname());
        $rankingPlayer->setAssociation($player->getAssociation());
        $rankingPlayer->setTown($player->getTown());
        $rankingPlayer->setEmail($player->getEmail());

        $ranking = new Ranking();
        $ranking->setPlayerId($playerId);
        $ranking->setPoints(0);
        $ranking->setTournamentCount(0);
        $ranking->setTournamentsIncluded([]);
        $ranking->setPlayer($rankingPlayer);

        return $ranking;
    }
}