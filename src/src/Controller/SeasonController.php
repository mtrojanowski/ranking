<?php
namespace App\Controller;


use App\Document\Ranking;
use App\Document\Season;
use App\Service\RankingService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;

class SeasonController extends AppController
{
    public function recalculateRanking(RankingService $rankingService, Request $request, DocumentManager $dm)
    {
        $seasonId = $request->query->get('seasonId');

        if (empty($seasonId)) {
            /** @var Season $season */
            $season = $dm->getRepository('App:Season')->getActiveSeason();
            $seasonId = $season->getId();
        } else {
            $season = $dm->getRepository('App:Season')->find($seasonId);
        }

        $rankingRepository = $dm->getRepository('App:Ranking');
        $rankings = $rankingRepository->findBy(['seasonId' => $seasonId]);

        foreach ($rankings as $currentRanking) {
            /** @var Ranking $currentRanking */

            $recalculatedRanking = $rankingService->recalculateRanking($currentRanking, $season);

            if ($recalculatedRanking->getTournamentCount() > 0) {
                $dm->persist($recalculatedRanking);
            } else {
                $dm->remove($recalculatedRanking);
            }
        }

        $dm->flush();

        return $this->json(['message' => 'Ranking recalculated'], 200);
    }

    public function initializeArmyRankings(RankingService $rankingService, DocumentManager $dm) {
        /** @var Season $season */
        $season = $dm->getRepository('App:Season')->getActiveSeason();

        $rankingRepository = $dm->getRepository('App:Ranking');
        $rankings = $rankingRepository->findBy([
            'seasonId' => $season->getId()]);
        $playersInRanking = [];

        foreach ($rankings as $playerInRanking) {
            /** @var Ranking $playerInRanking */
            $playersInRanking[] = $playerInRanking->getPlayerId();
        }

        $resultsRepository = $dm->getRepository('App:Result');

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

                $dm->persist($rankingService->recalculateRanking($currentRanking, $season));
            }
        }

        $dm->flush();

        return $this->json(['message' => 'Ranking recalculated'], 200);
    }
}
