<?php
namespace App\Controller;

use App\Document\Tournament;
use App\Repository\TournamentRepository;
use App\Service\TournamentsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;


class TournamentController extends AppController
{
    public function listTournaments(Request $request)
    {
        $previous = $request->query->get('previous');

        if ($previous === null) {
            return $this->json($this->getError('Parameter \'previous\' is required'), 400);
        }

        /** @var TournamentRepository $repository */
        $repository = $this->getMongo()->getRepository('App:Tournament');
        $tournaments = $repository->getTournaments($previous);

        return $this->json(
            $this->getSerializer()->normalize(
                $tournaments,
                'json'
            )
        );
    }

    public function addTournament(Request $request, TournamentsService $tournamentsService)
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

        $manager = $this->getMongo()->getManager();
        $manager->persist($tournament);
        $manager->flush();

        return $this->json(['id' => $tournament->getLegacyId()], 201);
    }
}