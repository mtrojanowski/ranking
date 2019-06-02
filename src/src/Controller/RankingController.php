<?php
namespace App\Controller;

use App\Controller\dto\IndividualRankingDto;
use App\Controller\dto\IndividualRankingTournamentDto;
use App\Controller\dto\RankingDataDto;
use App\Controller\dto\RankingDto;
use App\Controller\dto\RankingPlayerDto;
use App\Document\Ranking;
use App\Document\Result;
use App\Document\Tournament;
use App\Repository\RankingRepository;
use App\Repository\SeasonRepository;
use Symfony\Component\HttpFoundation\Request;

class RankingController extends AppController
{

    public function list(Request $request, string $seasonId = null) {
        /** @var RankingRepository $rankingRepository */
        $rankingRepository = $this->getMongo()
            ->getRepository('App:Ranking');

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $this->getMongo()->getRepository('App:Season');

        if (!$seasonId) {
            $season = $seasonRepository->getActiveSeason();
            $seasonId = $season->getId();
        } else {
            $season = $seasonRepository->find($seasonId);
        }

        $army = $request->get('army') ?: "";
        $players = $rankingRepository->getRanking($seasonId, $army);
        $ranking = [];

        foreach ($players as $player) {
            /** @var Ranking $player */
            $playerData = $player->getPlayer();
            $ranking[] = new RankingDto(
                $player->getId(),
                new RankingPlayerDto(
                    $player->getPlayerId(),
                    $playerData->getFirstName(),
                    $playerData->getNickname(),
                    $playerData->getTown(),
                    $playerData->getCountry(),
                    $playerData->getAssociation()
                ),
                $player->getPoints(),
                $player->getTournamentCount(),
                $player->getTournamentsIncluded()
            );
        }

        $rankingData = new RankingDataDto($ranking, $season->getRankingLastModified());

        return $this->json($this->getSerializer()->normalize($rankingData, 'json'));
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
        $includedTournaments = $rankingData->getTournamentsIncluded();

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
                isset($includedTournaments[$tournament->getLegacyId()]),
                isset($includedTournaments[$tournament->getLegacyId()]) ? $includedTournaments[$tournament->getLegacyId()] : 0,
                $result->getJudge() ?: 0
            );
        }

        $playerData = $rankingData->getPlayer();
        $individualRanking = new IndividualRankingDto(
            $rankingData->getPoints(),
            new RankingPlayerDto(
                $rankingData->getPlayerId(),
                $playerData->getFirstName(),
                $playerData->getNickname(),
                $playerData->getTown(),
                $playerData->getCountry(),
                $playerData->getAssociation()
            ),
            $individualTournaments
        );

        return $this->json($this->getSerializer()->normalize($individualRanking, 'json'));
    }
}