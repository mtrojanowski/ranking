<?php
namespace App\Controller;

use App\Controller\dto\Result;
use App\Controller\dto\TournamentResults;
use App\Document\Season;
use App\Document\Tournament;
use App\Exception\IncorrectPlayersException;
use App\Exception\InvalidTournamentException;
use App\Exception\PlayerNotFoundException;
use App\Repository\RankingRepository;
use App\Repository\ResultsRepository;
use App\Repository\TournamentRepository;
use App\Service\RankingService;
use App\Service\ResultsService;
use App\Service\TournamentsService;
use Doctrine\ODM\MongoDB\DocumentManager;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ResultsController extends AppController
{
    /**
     * @param Request $request
     * @param ResultsService $resultsService
     * @param RankingService $rankingService
     * @param TournamentsService $tournamentsService
     * @return JsonResponse
     * @throws PlayerNotFoundException
     * @throws ExceptionInterface
     */
    public function createTournamentResults(Request $request, ResultsService $resultsService, RankingService $rankingService, TournamentsService $tournamentsService, DocumentManager $dm)
    {
        try {
            /** @var TournamentResults $tournamentResults */
            $tournamentResults = $this->getSerializer()->deserialize(
                $request->getContent(),
                TournamentResults::class,
                'json'
            );

            $resultsDto = $this->getSerializer()->denormalize(
                $tournamentResults->getResults(),
                'App\Controller\dto\Result[]'
            );

            $tournamentResults->setResults($resultsDto);
        } catch(NotEncodableValueException $e) {
            return $this->json($this->getError('Invalid data'), 400);
        }

        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $dm->getRepository('App:Tournament');

        $tournament = $tournamentRepository->getById($tournamentResults->getTournamentId());

        if (!$tournament) {
            return $this->json($this->getError('Invalid tournament'), 400);
        }

        // Make sure tournament's legacy ID is used in the results
        $tournamentResults->setTournamentId($tournament->getLegacyId());

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

        /** @var ResultsRepository $resultsRepository */
        $resultsRepository = $dm->getRepository('App:Result');
        $currentResults = $this->popCurrentTournamentResults($tournament->getLegacyId(), $resultsRepository);

        foreach ($results as $result) {
            $dm->persist($result);
        }
        $tournament->setStatus('OK');
        $dm->flush();

        /** @var Season $season */
        $season = $dm->getRepository('App:Season')->find($tournament->getSeason());

        /** @var RankingRepository $rankingRepository */
        $rankingRepository = $dm->getRepository('App:Ranking');

        $resultsToRecalculate = [];

        foreach ($results as $result) {
            /** @var \App\Document\Result $result */
            $resultsToRecalculate[$result->getPlayerId()] = $result;
        }

        $currentArmies = [];

        foreach ($currentResults as $currentResult) {
            /** @var \App\Document\Result $currentResult */
            if (!isset($resultsToRecalculate[$currentResult->getPlayerId()])) {
                $resultsToRecalculate[$currentResult->getPlayerId()] = $currentResult;
            }

            $currentArmies[$currentResult->getPlayerId()] = $currentResult->getArmy();
        }

        foreach ($resultsToRecalculate as $result) {
            /** @var \App\Document\Result $result */
            $currentRanking = $rankingRepository->findOneBy([
                'playerId' => $result->getPlayerId(),
                'seasonId' => $season->getId()
            ]);

            if (!$currentRanking) {
                $currentRanking = $rankingService->createInitialRanking($result->getPlayerId(), $season->getId());
            }

            $recalculatedRanking = $rankingService->recalculateRanking($currentRanking, $season);

            if ($recalculatedRanking->getTournamentCount() > 0) {
                $dm->persist($recalculatedRanking);
            } else {
                $dm->remove($recalculatedRanking);
            }

            $currentArmy = isset($currentArmies[$result->getPlayerId()]) ? $currentArmies[$result->getPlayerId()] : null;
           if (empty($currentArmy) || $currentArmy == $result->getArmy()) {
               $this->recalculateArmyRanking($rankingService, $rankingRepository, $dm, $result, $season, $result->getArmy());
           } else {
               $this->recalculateArmyRanking($rankingService, $rankingRepository, $dm, $result, $season, $result->getArmy());
               $this->recalculateArmyRanking($rankingService, $rankingRepository, $dm, $result, $season, $currentArmy);
           }
        }

        $datetime = new \DateTime();
        $season->setRankingLastModified($datetime->getTimestamp());
        $dm->persist($season);

        $dm->flush();

        return $this->json(['message' => 'Tournament results saved'], 201);
    }

    private function popCurrentTournamentResults(string $tournamentId, ResultsRepository $resultsRepository): array {
        $currentResults = $resultsRepository->getTournamentResults($tournamentId);
        $resultsRepository->deleteTournamentResults($tournamentId);
        return $currentResults;
    }

    private function recalculateArmyRanking(RankingService $rankingService, RankingRepository $rankingRepository, DocumentManager $dm, \App\Document\Result $result, Season $season, string $army) {
        $currentArmyRanking = $rankingRepository->findOneBy([
            'playerId' => $result->getPlayerId(),
            'seasonId' => $season->getId(),
            'army' => $army
        ]);

        if (!$currentArmyRanking) {
            $currentArmyRanking = $rankingService->createInitialRanking(
                $result->getPlayerId(),
                $season->getId(),
                $army
            );
        }

        $currentArmyRecalculatedRanking = $rankingService->recalculateRanking($currentArmyRanking, $season);

        if ($currentArmyRecalculatedRanking->getTournamentCount() > 0) {
            $dm->persist($currentArmyRecalculatedRanking);
        } else {
            $dm->remove($currentArmyRecalculatedRanking);
        }
    }
}
