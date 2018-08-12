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
}
