<?php
namespace App\Service;


use App\Document\Player;
use App\Document\Ranking;
use App\Document\RankingPlayer;
use App\Document\Result;
use App\Document\Season;
use App\Exception\PlayerNotFoundException;
use Doctrine\Common\Persistence\ManagerRegistry;

class RankingService
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function recalculateRanking(Ranking $currentRanking, Season $season) : Ranking
    {
        $newRanking = $currentRanking;

        $resultsRepository = $this->managerRegistry->getRepository('App:Result');
        $results = $resultsRepository->findBy(['playerId' => $newRanking->getPlayerId()]);

        $tournamentLimit = $season->getLimitOfTournaments();
        $tournamentsIncluded = [];
        $tournamentsIncludedCount = 0;
        $mastersIncluded = 0;
        $teamMastersIncluded = 0;
        $pointsSum = 0;
        $headJudgeBonusReceived = 0;

        foreach ($results as $result) {
            /** @var Result $result */
            if ($tournamentsIncludedCount >= $tournamentLimit) {
                break;
            }

            if ($result->getTournamentRank() == 'master' && $result->getJudge() === 0) {
                if ($mastersIncluded >= $season->getLimitOfMasterTournaments()) {
                    continue;
                }

                if ($result->getTournamentType() == 'team' && $teamMastersIncluded >= $season->getLimitOfTeamMasterTournaments()) {
                    continue;
                }
            }

            if ($result->getJudge() === 1 && $headJudgeBonusReceived === 1) {
                continue;
            }

            $pointsSum += $result->getPoints();
            $tournamentsIncluded[] = $result->getTournamentId();
            $tournamentsIncludedCount++;

            if ($result->getTournamentRank() == 'master' && $result->getJudge() === 0) {
                $mastersIncluded++;
                if ($result->getTournamentType() == 'team') {
                    $teamMastersIncluded++;
                }
            }

            if ($result->getJudge() === 1) {
                $headJudgeBonusReceived = 1;
            }
        }

        $newRanking->setPoints($pointsSum);
        $newRanking->setTournamentsIncluded($tournamentsIncluded);
        $newRanking->setTournamentCount($tournamentsIncludedCount);
        $newRanking->setHeadJudgeBonusReceived($headJudgeBonusReceived);

        return $newRanking;
    }

    public function createInitialRanking($playerId) : Ranking
    {
        $playerRepository = $this->managerRegistry->getRepository('App:Player');
        /** @var Player $player */
        $player = $playerRepository->findOneBy([ 'legacyId' => $playerId ]);

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