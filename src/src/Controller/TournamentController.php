<?php
namespace App\Controller;

use App\Document\Tournament;
use App\Repository\TournamentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;


class TournamentController extends AppController
{
    /**
     * @Route("/tournaments", name="tournament_list", methods="GET")
     */
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

    /**
     * @Route("/tournaments", name="add_tournament", methods="POST")
     */
    public function addTournament(Request $request)
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

        $lastId = $this->getMongo()->getRepository('App:Tournament')->getLastLegacyId();
        $tournament->setLegacyId($lastId + 1);

        $manager = $this->getMongo()->getManager();
        $manager->persist($tournament);
        $manager->flush();

        return $this->json(['id' => $tournament->getLegacyId()], 201);
    }
}