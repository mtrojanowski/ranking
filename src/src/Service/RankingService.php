<?php
namespace App\Service;

use App\Document\Player;
use App\Document\Ranking;
use App\Document\RankingPlayer;
use App\Document\Result;
use App\Document\Season;
use App\Exception\PlayerNotFoundException;
use App\Helper\RankingData;
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

        $rankingData = new RankingData($results, $season->getLimitOfTournaments());

        while (isset($rankingData->getResults()[0])) {
            $rankingData = $this->sumPointsForRanking($rankingData, $season);
        }

        $newRanking->setPoints($rankingData->getPointsSum());
        $newRanking->setTournamentsIncluded($rankingData->getTournamentsIncluded());
        $newRanking->setTournamentCount($rankingData->getTournamentsIncludedCount());
        $newRanking->setHeadJudgeBonusReceived($rankingData->getHeadJudgeBonusReceived());

        return $newRanking;
    }

    private function sumPointsForRanking(RankingData $rankingData, Season $season): RankingData {
        foreach ($rankingData->getResults() as $key => $result) {
            /** @var Result $result */
            if ($rankingData->getTournamentsIncludedCount() >= $rankingData->getTournamentLimit()) {
                break;
            }

            if ($result->getTournamentRank() == 'master' && $result->getJudge() === 0) {
                if ($rankingData->getMastersIncluded() >= $season->getLimitOfMasterTournaments()) {
                    $rankingData->setResults($this->changeMastersToLocals(array_slice($rankingData->getResults(), $key)));
                    return $rankingData;
                }

                if ($result->getTournamentType() == 'team' && $rankingData->getTeamMastersIncluded() >= $season->getLimitOfTeamMasterTournaments()) {
                    $rankingData->setResults($this->changeTeamMastersToLocals(array_slice($rankingData->getResults(), $key)));
                    return $rankingData;
                }

                if ($result->getTournamentType() == 'double' && $rankingData->getDoubleMastersIncluded() >= $season->getLimitOfPairMasterTournaments()) {
                    $rankingData->setResults($this->changeDoublesMastersToLocals(array_slice($rankingData->getResults(), $key)));
                    return $rankingData;
                }
            }

            if ($result->getJudge() && $rankingData->getHeadJudgeBonusReceived() >= 2) {
                continue;
            }

            $points = $result->getPoints();
            if ($result->getJudge() > 0) {
                $rankingData->setheadJudgeBonusReceived($rankingData->getHeadJudgeBonusReceived() + 1) ;
                if ($rankingData->getHeadJudgeBonusReceived() == 1) {
                    $points = 150;
                } else {
                    $points = 100;
                }
            }

            $rankingData->addPointsToSum($points);
            $rankingData->addIncludedTournament($result->getTournamentId());
            $rankingData->increaseTournamentsIncludedCount();

            if ($result->getTournamentRank() == 'master' && $result->getJudge() === 0) {
                $rankingData->increaseMastersIncluded();
                if ($result->getTournamentType() == 'team') {
                    $rankingData->increaseTeamMastersIncluded();
                } elseif ($result->getTournamentType() == 'double') {
                    $rankingData->increaseDoubleMastersIncluded();
                }
            }
        }

        $rankingData->setResults([]);
        return $rankingData;
    }

    public function createInitialRanking($playerId, $seasonId) : Ranking
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
        $ranking->setSeasonId($seasonId);
        $ranking->setPoints(0);
        $ranking->setTournamentCount(0);
        $ranking->setTournamentsIncluded([]);
        $ranking->setPlayer($rankingPlayer);

        return $ranking;
    }

    private function changeTeamMastersToLocals(array $results): array
    {
        return $this->changeMastersToLocals($results, 'team');
    }

    private function changeDoublesMastersToLocals(array $results): array
    {
        return $this->changeMastersToLocals($results, 'double');
    }

    private function changeMastersToLocals(array $results, string $type = null): array
    {
        $newResults = [];
        foreach ($results as $key => $result) {
            /** @var Result $result */
            if ($result->getTournamentRank() == 'master') {
                if ($type === null || ($type !== null && $result->getTournamentType() == $type)) {
                    $result->setPoints(round($result->getPoints() / 3));
                    $result->setTournamentRank('local');
                }
            }

            $newResults[] = $result;
        }

        usort($results, function(Result $elem1, Result $elem2) {
            return -($elem1->getPoints() - $elem2->getPoints());
        });

        return $results;
    }
}
