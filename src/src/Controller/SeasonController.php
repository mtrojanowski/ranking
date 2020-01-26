<?php
namespace App\Controller;


use App\Document\Ranking;
use App\Document\Season;
use App\Service\RankingService;
use Symfony\Component\HttpFoundation\Request;

class SeasonController extends AppController
{
    public function recalculateRanking(RankingService $rankingService, Request $request)
    {
        $seasonId = $request->query->get('seasonId');

        if (empty($seasonId)) {
            /** @var Season $season */
            $season = $this->getMongo()->getRepository('App:Season')->getActiveSeason();
            $seasonId = $season->getId();
        } else {
            $season = $this->getMongo()->getRepository('App:Season')->find($seasonId);
        }

        $rankingRepository = $this->getMongo()->getRepository('App:Ranking');
        $rankings = $rankingRepository->findBy(['seasonId' => $seasonId]);

        $em = $this->getMongo()->getManager();

        foreach ($rankings as $currentRanking) {
            /** @var Ranking $currentRanking */

            $recalculatedRanking = $rankingService->recalculateRanking($currentRanking, $season);

            if ($recalculatedRanking->getTournamentCount() > 0) {
                $em->persist($recalculatedRanking);
            } else {
                $em->remove($recalculatedRanking);
            }
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
            $playersInRanking[] = $playerInRanking->getPlayerId();
        }

        $resultsRepository = $this->getMongo()->getRepository('App:Result');

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
