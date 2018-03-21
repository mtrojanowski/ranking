<?php
namespace App\Controller;

use App\Repository\TournamentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


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

        return $this->json(
            $this->getSerializer()->normalize(
                $repository->getTournaments((boolean) $previous),
                'json'
            )
        );
    }

    /**
     * @Route("/tournaments", name="add_tournament", methods="POST")
     */
    public function addTournament(Request $request)
    {
        // TODO implement
    }
}