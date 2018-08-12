<?php
namespace App\Controller;

use App\Controller\dto\IndividualRankingDto;
use App\Controller\dto\IndividualRankingTournamentDto;
use App\Controller\dto\RankingDto;
use App\Controller\dto\RankingPlayerDto;
use App\Document\Ranking;
use App\Document\RankingPlayer;
use App\Document\Result;
use App\Document\Tournament;

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
                new RankingPlayerDto($player->getPlayer()->getFirstName(), $player->getPlayer()->getNickname(), $player->getPlayer()->getTown()),
                $player->getPoints(),
                $player->getTournamentCount(),
                $player->getTournamentsIncluded()
            );
        }

        return $this->json($this->getSerializer()->normalize($ranking, 'json'));
    }

    public function individual($seasonId, $playerId) {
        $playersResults = $this->getMongo()->getRepository('App:Result')
            ->findBy(['seasonId' => $seasonId, 'playerId' => $playerId]);
        /** @var Ranking $rankingData */
        $rankingData = $this->getMongo()->getRepository('App:Ranking')
            ->findOneBy(['seasonId' => $seasonId, 'playerId' => $playerId]);

        $tournamentIds = [];
        $resultsByTournament = [];

        foreach ($playersResults as $result) {
            /** @var Result $result */
            $tournamentIds[] = (int) $result->getTournamentId();
            $resultsByTournament[$result->getTournamentId()] = $result;
        }

        $tournaments = $this->getMongo()->getRepository('App:Tournament')
            ->findTournaments($tournamentIds);

        $individualTournaments = [];

        foreach ($tournaments as $tournament) {
            /** @var Tournament $tournament */
            /** @var Result $result */
            $result = $resultsByTournament[$tournament->getLegacyId()];
            $individualTournaments[] = new IndividualRankingTournamentDto(
                $tournament->getId(),
                (int) $tournament->getLegacyId(),
                $tournament->getDate()->format('d.m.Y'),
                $tournament->getName(),
                $tournament->getRank(),
                $tournament->getType(),
                $tournament->getPlayersInTeam(),
                $result->getPlace(),
                $result->getPoints(),
                $result->getArmy(),
                in_array($tournament->getLegacyId(), $rankingData->getTournamentsIncluded()),
                $result->getJudge() ?: 0
            );
        }

        $individualRanking = new IndividualRankingDto($rankingData->getPoints(),
            new RankingPlayerDto($rankingData->getPlayer()->getFirstName(), $rankingData->getPlayer()->getNickname(), $rankingData->getPlayer()->getTown()),
            $individualTournaments
        );

        return $this->json($this->getSerializer()->normalize($individualRanking, 'json'));
    }
}