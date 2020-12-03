<?php
namespace App\Controller;

use App\Controller\dto\TournamentDataDto;
use App\Controller\dto\TournamentDataResult;
use App\Document\Player;
use App\Document\Result;
use App\Document\Tournament;
use App\Repository\PlayerRepository;
use App\Repository\ResultsRepository;
use App\Repository\SeasonRepository;
use App\Repository\TournamentRepository;
use App\Service\TournamentsService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;


class TournamentController extends AppController
{
    public function listTournaments(Request $request, DocumentManager $dm)
    {
        $previous = $request->query->get('previous');

        if ($previous === null) {
            return $this->json($this->getError('Parameter \'previous\' is required'), 400);
        }

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $dm->getRepository('App:Season');
        $activeSeason = $seasonRepository->getActiveSeason();

        /** @var TournamentRepository $repository */
        $repository = $dm->getRepository('App:Tournament');
        $tournaments = $repository->getTournaments($previous, $activeSeason->getId());

        foreach ($tournaments as $tournament) {
            $tournament->setDate($tournament->getDate()->format("d.m.Y"));
        }

        return $this->json(
            $this->getSerializer()->normalize(
                $tournaments,
                'json'
            )
        );
    }

    public function addTournament(Request $request, TournamentsService $tournamentsService, DocumentManager $manager)
    {
        try {
            /** @var Tournament $tournament */
            $tournament = $this->getSerializer()->deserialize(
                $request->getContent(),
                Tournament::class,
                'json'
            );
        } catch (NotEncodableValueException $e) {
            return $this->json($this->getError('Incorrect data'), 400);
        }

        $tournamentsService->prepareTournament($tournament);

        $manager->persist($tournament);
        $manager->flush();

        return $this->json(['id' => $tournament->getLegacyId()], 201);
    }

    public function getTournament(DocumentManager $dm, string $id)
    {
        $tournamentRepository = $dm->getRepository('App:Tournament');
        /** @var Tournament $tournament */
        $tournament = $tournamentRepository->find($id);
        /** @var ResultsRepository $resultsRepository */
        $resultsRepository = $dm->getRepository('App:Result');
        $results = $resultsRepository->findBy(['tournamentId' => (string)$tournament->getLegacyId()], ['place' => 1]);

        $playerIds = [];
        $resultMap = [];

        foreach ($results as $result) {
            /** @var Result $result */
            $playerIds[] = $result->getPlayerId();
            $resultMap[$result->getPlayerId()] = $result;
        }


        $playersRepository = $dm->getRepository('App:Player');
        /** @var PlayerRepository $playersRepository */
        $players = $playersRepository->getPlayersByIds($playerIds);

        $tournamentDataResults = [];
        foreach ($players as $player) {
            /** @var Player $player */
            $result = $resultMap[$player->getLegacyId()];

            $tournamentDataResults[] = new TournamentDataResult(
                $result->getPlace(),
                $result->getPlayerId(),
                $result->getJudge() ?: 0,
                $result->getPoints(),
                $player->getName(),
                $player->getNickname(),
                $player->getFirstName(),
                $player->getAssociation(),
                $player->getTown()
            );
        }

        usort($tournamentDataResults,
            function (TournamentDataResult $elem1, TournamentDataResult $elem2) {
                if ($elem1->getPlace() > $elem2->getPlace()) { return 1; } return -1; }
        );

        $tournamentDataDto = new TournamentDataDto(
            $tournament->getId(),
            $tournament->getLegacyId(),
            $tournament->getName(),
            $tournament->getTown(),
            $tournament->getDate()->format('d.m.Y'),
            $tournament->getVenue(),
            $tournament->getOrganiser(),
            $tournamentDataResults
        );

        return $this->json($this->getSerializer()->normalize($tournamentDataDto, 'json'));
    }
}
