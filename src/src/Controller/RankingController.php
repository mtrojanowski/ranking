<?php
namespace App\Controller;

use App\Controller\dto\RankingDto;
use App\Controller\dto\RankingPlayerDto;
use App\Document\Ranking;
use App\Document\RankingPlayer;

class RankingController extends AppController
{

    public function list() {
        $players = $this->getMongo()
            ->getRepository('App:Ranking')
            ->getRanking();

        $ranking = [];

        foreach ($players as $player) {
            /** @var Ranking $player */
            $ranking[] = new RankingDto(
                $player->getId(),
                new RankingPlayerDto($player->getPlayer()->getFirstName(), $player->getPlayer()->getTown()),
                $player->getPoints(),
                $player->getTournamentCount(),
                $player->getTournamentsIncluded()
            );
        }

        return $this->json($this->getSerializer()->normalize($ranking, 'json'));
    }
}