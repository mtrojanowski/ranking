<?php
namespace App\Controller;

use App\Controller\dto\Result;
use App\Controller\dto\TournamentResults;
use App\Document\Season;
use App\Document\Tournament;
use App\Exception\IncorrectPlayersException;
use App\Exception\InvalidTournamentException;
use App\Repository\ResultsRepository;
use App\Service\RankingService;
use App\Service\ResultsService;
use App\Service\TournamentsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ResultsController extends AppController
{
    public function createTournamentResults(Request $request, ResultsService $resultsService, RankingService $rankingService, TournamentsService $tournamentsService)
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

        try {
            $tournamentsService->verifyTournamentPlayers($tournamentResults);
        } catch (IncorrectPlayersException $e) {
            return $this->json($this->getError($e->getMessage()), 422);
        }

        $this->removeCurrentTournamentResults($tournament->getLegacyId());

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
            $currentRanking = $rankingRepository->findOneBy([
                'playerId' => $result->getPlayerId(),
                'seasonId' => $season->getId()
            ]);


            if (!$currentRanking) {
                $currentRanking = $rankingService->createInitialRanking($result->getPlayerId(), $season->getId());
            }

            $em->persist($rankingService->recalculateRanking($currentRanking, $season));

            $currentArmyRanking = $rankingRepository->findOneBy([
                'playerId' => $result->getPlayerId(),
                'seasonId' => $season->getId(),
                'army' => $result->getArmy()
            ]);

            if (!$currentArmyRanking) {
                $currentArmyRanking = $rankingService->createInitialRanking(
                    $result->getPlayerId(),
                    $season->getId(),
                    $result->getArmy()
                );
            }

            $em->persist($rankingService->recalculateRanking($currentArmyRanking, $season));
        }

        $datetime = new \DateTime();
        $season->setRankingLastModified($datetime->getTimestamp());
        $em->persist($season);

        $em->flush();

        return $this->json(['message' => 'Tournament results saved'], 201);
    }

    private function removeCurrentTournamentResults(string $tournamentId) {
        /** @var ResultsRepository $resultsRepository */
        $resultsRepository = $this->getMongo()->getRepository('App:Result');
        $resultsRepository->deleteTournamentResults($tournamentId);
    }
}