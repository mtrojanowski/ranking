<?php
namespace App\Controller;


use App\Document\Ranking;
use App\Document\Season;
use App\Service\RankingService;

class SeasonController extends AppController
{
    public function recalculateRanking(RankingService $rankingService) {
        /** @var Season $season */
        $season = $this->getMongo()->getRepository('App:Season')->getActiveSeason();

        $rankingRepository = $this->getMongo()->getRepository('App:Ranking');
        $rankings = $rankingRepository->findBy(['seasonId' => $season->getId()]);

        $em = $this->getMongo()->getManager();

        foreach ($rankings as $currentRanking) {
            /** @var Ranking $currentRanking */

            $em->persist($rankingService->recalculateRanking($currentRanking, $season));
        }

        $em->flush();

        return $this->json(['message' => 'Ranking recalculated'], 200);
    }

    public function initializeArmyRankings(RankingService $rankingService) {
        /** @var Season $season */
        $season = $this->getMongo()->getRepository('App:Season')->getActiveSeason();

        $rankingRepository = $this->getMongo()->getRepository('App:Ranking');
        $rankings = $rankingRepository->findBy([
            'seasonId' => $season->getId()]);
        $playersInRanking = [];

        foreach ($rankings as $playerInRanking) {
            /** @var Ranking $playerInRanking */
            $playersInRanking = $playerInRanking->getPlayerId();
        }

        $resultsRepository = $this->getMongo()->getRepository('App:Results');

        $armies = [
            "UD",
            "WDG",
            "DE",
            "EOS",
            "KOE",
            "VC",
            "VS",
            "SE",
            "HE",
            "OG",
            "BH",
            "DH",
            "OK",
            "DL",
            "SA",
            "ID",
        ];
        $em = $this->getMongo()->getManager();

        foreach ($armies as $army) {
            foreach ($playersInRanking as $playerId) {

                $resultsForArmy = $resultsRepository->findBy([
                    'playerId' => $playerId,
                    'seasonId' => $season->getId(),
                    'army' => $army
                ]);

                if (!isset($resultsForArmy[0])) {
                    continue;
                }

                $currentRanking = $rankingService->createInitialRanking(
                    $playerId,
                    $season->getId(),
                    $army
                );

                $em->persist($rankingService->recalculateRanking($currentRanking, $season));
            }
        }

        $em->flush();

        return $this->json(['message' => 'Ranking recalculated'], 200);
    }
}
