<?php
namespace App\Controller;

use App\Controller\dto\Result;
use App\Controller\dto\TournamentResults;
use App\Document\Season;
use App\Document\Tournament;
use App\Exception\InvalidTournamentException;
use App\Service\RankingService;
use App\Service\ResultsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ResultsController extends AppController
{
    public function createTournamentResults(Request $request, ResultsService $resultsService, RankingService $rankingService)
    {
        try {
            /** @var TournamentResults $tournamentResults */
            $tournamentResults = $this->getSerializer()->deserialize(
                $request->getContent(),
                TournamentResults::class,
                'json'
            );

            $results = $this->getSerializer()->denormalize(
                $tournamentResults->getResults(),
                'App\Controller\dto\Result[]'
            );

            $tournamentResults->setResults($results);
        } catch(NotEncodableValueException $e) {
            return $this->json($this->getError('Invalid data'), 400);
        }

        $tournamentRepository = $this->getMongo()->getRepository('App:Tournament');
        /** @var Tournament $tournament */
        $tournament = $tournamentRepository->find((int) $tournamentResults->getTournamentId());

        if (!$tournament) {
            $tournament = $tournamentRepository->findOneBy(['legacyId' => (int) $tournamentResults->getTournamentId()]);
        }

        if (!$tournament) {
            return $this->json($this->getError('Invalid tournament'), 400);
        }

        try {
            $results = $resultsService->createTournamentResults($tournament, $tournamentResults);
        } catch (InvalidTournamentException $e) {
            return $this->json($this->getError('Unsupported tournament type'), 400);
        }

        $em = $this->getMongo()->getManager();
        foreach ($results as $result) {
            $em->persist($result);
        }
        $tournament->setStatus('OK');
        $em->flush();

        /** @var Season $season */
        $season = $this->getMongo()->getRepository('App:Season')->find($tournament->getSeason());

        $rankingRepository = $this->getMongo()->getRepository('App:Ranking');
        foreach ($results as $result) {
            /** @var Result $result */
            $currentRanking = $rankingRepository->findOneBy(['playerId' => $result->getPlayerId()]);


            if (!$currentRanking) {
                $currentRanking = $rankingService->createInitialRanking($result->getPlayerId(), $season->getId());
            }

            $em->persist($rankingService->recalculateRanking($currentRanking, $season));
        }

        $em->flush();

        return $this->json(['message' => 'Tournament results saved'], 201);
    }
}